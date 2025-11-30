<?php
require_once __DIR__ . '/../vendor/autoload.php';    
require_once __DIR__ . '/../crud/conexao_DB.php';    
require_once __DIR__ . '/../crud/planos_CRUD.php';    
require_once __DIR__ . '/../crud/pagamento_CRUD.php'; 

session_start();
if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
  http_response_code(400);
  echo "CSRF inválido.";
  exit;
}

$plan_id     = (int)($_POST['plan_id'] ?? 0);
$billing     = ($_POST['billing_cycle'] ?? 'monthly') === 'yearly' ? 'yearly' : 'monthly';
$method      = in_array($_POST['method'] ?? 'pix', ['pix','boleto','card']) ? $_POST['method'] : 'pix';
$payer_name  = trim($_POST['payer_name'] ?? '');
$payer_email = trim($_POST['payer_email'] ?? '');


// buscar plano
$plan = buscarPlanoAtivo($conn, $plan_id);
if (!$plan) {
  http_response_code(404);
  echo "Plano não encontrado ou inativo.";
  exit;
}

// calcula valor correto
$amount = calcularValorPlano($plan, $billing); 

$dadosPagamento = [
  'plan_id'       => $plan_id,
  'plan_name'     => $plan['name'],
  'billing_cycle' => $billing,
  'amount'        => $amount,
  'method'        => $method,
  'payer_name'    => $payer_name,
  'payer_email'   => $payer_email,
  'external_ref'  => null,      
  'status'        => 'pending', // padrão
];


// STRIPE CHECKOUT

if ($method === 'card') {
        //NAO MEXE NESSA PORRA AQUI
  \Stripe\Stripe::setApiKey('sk_test_51SVg9HL4qxvVg8kd3WIxR7qQATox0fV7eWfsVfFYMZFZ27HcWaO2p21YRNj8jRiMO3nrGsuuomICQd8J2mhm29ak00oRDLB64d');

  $amount_cents = (int)round($amount * 100);

  $success_url = 'http://localhost/TIAPN-Estoque-PUC/pages/pagamento_sucesso.php?session_id={CHECKOUT_SESSION_ID}';
  $cancel_url  = 'http://localhost/TIAPN-Estoque-PUC/pages/pagamento.php?plan_id=' . $plan_id . '&cancel=1';

  try {
    $checkout_session = \Stripe\Checkout\Session::create([
      'mode' => 'payment',
      'payment_method_types' => ['card'],
      'line_items' => [[
        'quantity' => 1,
        'price_data' => [
          'currency' => 'brl',
          'unit_amount' => $amount_cents,
          'product_data' => [
            'name'        => $plan['name'],
            'description' => $plan['description'] ?? '',
          ],
        ],
      ]],
      'customer_email' => $payer_email,
      'metadata' => [
        'plan_name'     => $plan['name'],
        'billing_cycle' => $billing,
        'payer_name'    => $payer_name,
      ],
      'success_url' => $success_url,
      'cancel_url'  => $cancel_url,
    ]);
  } catch (\Stripe\Exception\ApiErrorException $e) {
    http_response_code(500);
    echo "Erro ao criar sessão de pagamento na Stripe: " . htmlspecialchars($e->getMessage());
    exit;
  }

  // salvar pagamento com da sessão da Stripe
  $dadosPagamento['external_ref'] = $checkout_session->id;
  $dadosPagamento['status']       = 'pending';

  $payment_id = criarPagamento($conn, $dadosPagamento);


  $_SESSION['last_payment'] = [
    'id'          => $payment_id,
    'status'      => 'pending',
    'method'      => $method,
    'plan_name'   => $plan['name'],
    'amount'      => number_format($amount, 2, ',', '.'),
    'billing'     => $billing,
    'external_ref'=> $checkout_session->id,
  ];

  header('Location: ' . $checkout_session->url); // manda para tela da Stripe
  exit;
}


// MÉTODOS PIX / BOLETO SAO FALSOS NAO FUNCIONAN DE VDD


$pix_code    = '00020126580014BR.GOV.BCB.PIX0136demo@estoqueide...865802BR5925ESTOQUE IDEAL LTDA6009SAO PAULO62070503***6304ABCD';
$boleto_line = '34191.79001 01043.510047 91020.150008 6 12340000012345';

$dadosPagamento['external_ref'] = null;
$dadosPagamento['status']       = 'pending';

$payment_id = criarPagamento($conn, $dadosPagamento);

$_SESSION['last_payment'] = [
  'id'          => $payment_id,
  'status'      => 'pending',
  'method'      => $method,
  'plan_name'   => $plan['name'],
  'amount'      => number_format($amount, 2, ',', '.'),
  'billing'     => $billing,
  'external_ref'=> null,
  'pix_code'    => $pix_code,
  'boleto_line' => $boleto_line,
];

header('Location: pagamento_sucesso.php');
exit;
