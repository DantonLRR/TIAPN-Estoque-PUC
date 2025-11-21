<?php
require_once __DIR__ . '/../vendor/autoload.php';      // Stripe (composer)
require_once __DIR__ . '/../crud/conexao_DB.php';      // cria $conn (MySQLi)
require_once __DIR__ . '/../crud/planos_CRUD.php';     // buscarPlanoAtivo
require_once __DIR__ . '/../crud/pagamento_CRUD.php';  // calcularValorPlano, criarPagamento

session_start();

// --- Proteção CSRF ---
if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
    http_response_code(400);
    echo "CSRF inválido.";
    exit;
}

// --- Captura e normaliza dados do POST ---
$plan_id     = (int)($_POST['plan_id'] ?? 0);
$billing     = ($_POST['billing_cycle'] ?? 'monthly') === 'yearly' ? 'yearly' : 'monthly';
$method      = in_array($_POST['method'] ?? 'pix', ['pix','boleto','card'], true) ? $_POST['method'] : 'pix';
$payer_name  = trim($_POST['payer_name'] ?? '');
$payer_email = trim($_POST['payer_email'] ?? '');

// --- Busca plano no BD ---
$plan = buscarPlanoAtivo($conn, $plan_id);
if (!$plan) {
    http_response_code(404);
    echo "Plano não encontrado ou inativo.";
    exit;
}

// --- Calcula valor do plano (mensal/anual) ---
$amount = calcularValorPlano($plan, $billing); // vem em reais (float)
if ($amount <= 0) {
    http_response_code(400);
    echo "Valor do plano inválido.";
    exit;
}

// ---------------  PAGAMENTO COM CARTÃO (Stripe) ---------------
if ($method === 'card') {
    \Stripe\Stripe::setApiKey('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');

    try {
        // Cria sessão de checkout
        $session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'mode'                 => 'payment',
    'customer_email'       => $payer_email ?: null,
    'line_items' => [[
        'price_data' => [
            'currency'     => 'brl',
            'product_data' => [
                'name' => $plan['name'],
            ],
            'unit_amount'  => (int) round($amount * 100),
        ],
        'quantity' => 1,
    ]],
    'success_url' => 'http://localhost/TIAPN-Estoque-PUC/pagamento_sucesso.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url'  => 'http://localhost/TIAPN-Estoque-PUC/pagamento_cancelado.php',
]);


        // Referência externa = ID da sessão do Stripe
        $external_ref = $session->id;
        $status       = 'pending'; 

        // Grava pagamento no BD
        $payment_id = criarPagamento($conn, [
            'plan_id'       => $plan['id'],
            'plan_name'     => $plan['name'],
            'billing_cycle' => $billing,
            'amount'        => $amount,
            'method'        => 'card',
            'payer_name'    => $payer_name,
            'payer_email'   => $payer_email,
            'external_ref'  => $external_ref,
            'status'        => $status,
        ]);

        // Guarda info para a proxima página que eu ainda nao sei qual é
        $_SESSION['last_payment'] = [
            'id'          => $payment_id,
            'status'      => $status,
            'method'      => 'card',
            'plan_name'   => $plan['name'],
            'amount'      => number_format($amount, 2, ',', '.'),
            'billing'     => $billing,
            'external_ref'=> $external_ref,
            'pix_code'    => null,
            'boleto_line' => null,
        ];

        // Redireciona para o Checkout do Stripe
        header('Location: ' . $session->url);
        exit;

    } catch (\Stripe\Exception\ApiErrorException $e) {
        http_response_code(500);
        echo "Erro ao criar sessão de pagamento: " . htmlspecialchars($e->getMessage());
        exit;
    }
}

// --------------- PIX / BOLETO (fluxo “fake” local) ---------------

$external_ref = strtoupper($method) . '-' . uniqid();
$status       = 'pending';

$payment_id = criarPagamento($conn, [
    'plan_id'       => $plan['id'],
    'plan_name'     => $plan['name'],
    'billing_cycle' => $billing,
    'amount'        => $amount,
    'method'        => $method,
    'payer_name'    => $payer_name,
    'payer_email'   => $payer_email,
    'external_ref'  => $external_ref,
    'status'        => $status,
]);

// Exemplo de “dados” para mostrar na tela
$pix_code = '';
$boleto_line = '34191.79001 01043.510047 91020.150008 6 12340000012345';

// Guarda informação para a página .php
$_SESSION['last_payment'] = [
    'id'           => $payment_id,
    'status'       => $status,
    'method'       => $method,
    'plan_name'    => $plan['name'],
    'amount'       => number_format($amount, 2, ',', '.'),
    'billing'      => $billing,
    'external_ref' => $external_ref,
    'pix_code'     => $method === 'pix'    ? $pix_code    : null,
    'boleto_line'  => $method === 'boleto' ? $boleto_line : null,
];

header('Location: obrigado.php');
exit;
