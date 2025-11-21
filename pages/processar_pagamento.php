<?php
require_once __DIR__ . '/../crud/conexao_DB.php';     // cria $conn
require_once __DIR__ . '/../crud/planos_CRUD.php';    // funções de planos
require_once __DIR__ . '/../crud/pagamento_CRUD.php'; // funções de pagamento

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

if ($payer_name === '' || !filter_var($payer_email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(422);
  echo "Nome/E-mail inválidos.";
  exit;
}

$plan = buscarPlanoAtivo($conn, $plan_id);
if (!$plan) { http_response_code(404); echo "Plano inválido ou inativo."; exit; }

$amount = calcularValorPlano($plan, $billing);

// Simulação simples do gateway
$external_ref = strtoupper(bin2hex(random_bytes(6)));
$status = 'pending';

if ($method === 'card') {
  $cvv = preg_replace('/\D/','', $_POST['card_cvv'] ?? '');
  $status = ($cvv !== '' && ((int)substr($cvv, -1)) % 2 === 0) ? 'approved' : 'rejected';
}

$payment_id = criarPagamento($conn, [
  'plan_id'      => $plan['id'],
  'plan_name'    => $plan['name'],
  'billing_cycle'=> $billing,
  'amount'       => $amount,
  'method'       => $method,
  'payer_name'   => $payer_name,
  'payer_email'  => $payer_email,
  'status'       => $status,
  'external_ref' => $external_ref
]);

$_SESSION['last_payment'] = [
  'id' => $payment_id,
  'status' => $status,
  'method' => $method,
  'plan_name' => $plan['name'],
  'amount' => number_format($amount, 2, ',', '.'),
  'billing' => $billing,
  'external_ref' => $external_ref,
  // exemplos para mostrar na tela:
  'pix_code' => '00020126580014BR.GOV.BCB.PIX0136demo@estoqueideal.com5204000053039865802BR5925ESTOQUE IDEAL LTDA6009SAO PAULO62070503***6304ABCD',
  'boleto_line' => '34191.79001 01043.510047 91020.150008 6 12340000012345'
];

header('Location: obrigado.php');
exit;
