<?php
require_once __DIR__ . '/../crud/conexao_DB.php';
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

$lastPayment      = $_SESSION['last_payment'] ?? null;
$session_id       = $_GET['session_id'] ?? null;
$fromStripe       = false;
$senha_temporaria = null;
$email            = null;
$nome             = null;
$plan_name        = null;
$billing          = null;

// STRIPE

if ($session_id) {
    $fromStripe = true;

    // NAO ALTERAR ESSA PORRA AQUI, NAO CONSIGO VOLTAR COM ESSA CHAVE
    \Stripe\Stripe::setApiKey('sk_test_51SVg9HL4qxvVg8kd3WIxR7qQATox0fV7eWfsVfFYMZFZ27HcWaO2p21YRNj8jRiMO3nrGsuuomICQd8J2mhm29ak00oRDLB64d');

    try {
        $checkout_session = \Stripe\Checkout\Session::retrieve($session_id, [
            'expand' => ['payment_intent', 'customer']
        ]);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        die("Erro ao consultar Stripe: " . htmlspecialchars($e->getMessage()));
    }

    if ($checkout_session->payment_status !== 'paid') {
        die("Pagamento ainda não confirmado na Stripe. Status: " . htmlspecialchars($checkout_session->payment_status));
    }

    $customer_details = $checkout_session->customer_details ?? null;

    $email = $customer_details->email
          ?? $checkout_session->customer_email
          ?? null;

    $nome = $customer_details->name
          ?? ($checkout_session->metadata->payer_name ?? 'Cliente');

    $plan_name = $checkout_session->metadata->plan_name     ?? 'Seu plano';
    $billing   = $checkout_session->metadata->billing_cycle ?? 'monthly';

    if (!$email) {
        die("Pagamento aprovado, mas não foi possível obter o e-mail do comprador.");
    }

    // Criar / verificar usuário 
    if (!isset($conn) || !($conn instanceof mysqli)) {
        die("Falha na conexão com o banco de dados.");
    }

    $sqlCheck = "SELECT id FROM usuarios WHERE email = ? LIMIT 1";
    $stmtCheck = mysqli_prepare($conn, $sqlCheck);
    $usuarioEncontrado = null;

    if ($stmtCheck) {
        mysqli_stmt_bind_param($stmtCheck, "s", $email);
        mysqli_stmt_execute($stmtCheck);
        $result = mysqli_stmt_get_result($stmtCheck);
        $usuarioEncontrado = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmtCheck);
    }

    if (!$usuarioEncontrado) {
        // gerar senha temporária
        $senha_temporaria = bin2hex(random_bytes(4));
        $senha_hash       = password_hash($senha_temporaria, PASSWORD_DEFAULT);

        $usuario_login = strstr($email, '@', true) ?: $email;

        $sqlInsert = "INSERT INTO usuarios
            (nome, usuario, tipo_usuario, senha, email, must_change_password, status)
            VALUES (?, ?, 'cliente', ?, ?, 1, 1)";

        $stmtInsert = mysqli_prepare($conn, $sqlInsert);
        if ($stmtInsert) {
            mysqli_stmt_bind_param(
                $stmtInsert,
                "ssss",
                $nome,
                $usuario_login,
                $senha_hash,
                $email
            );
            mysqli_stmt_execute($stmtInsert);
            mysqli_stmt_close($stmtInsert);
        }

        // marcar payment como aprovado
        $sqlUpdate = "UPDATE payments SET status = 'approved' WHERE external_ref = ?";
        $stmtUp = mysqli_prepare($conn, $sqlUpdate);
        if ($stmtUp) {
            mysqli_stmt_bind_param($stmtUp, "s", $session_id);
            mysqli_stmt_execute($stmtUp);
            mysqli_stmt_close($stmtUp);
        }

        // enviar e-mail com senha temporária
        $loginUrl = "http://localhost/TIAPN-Estoque-PUC/pages/login.php";

        $mensagemHtml = "
        <html><head><meta charset='UTF-8'><title>Dados de acesso</title></head><body>
          <p>Olá, " . htmlspecialchars($nome) . "!</p>
          <p>Seu pagamento do plano <strong>" . htmlspecialchars($plan_name) . "</strong> foi confirmado.</p>
          <p>Segue sua senha temporária de acesso:</p>
          <p><strong>Usuário:</strong> " . htmlspecialchars($email) . "<br>
             <strong>Senha temporária:</strong> " . htmlspecialchars($senha_temporaria) . "</p>
          <p>No primeiro login você poderá alterar sua senha.</p>
          <p>Acesse: <a href='" . htmlspecialchars($loginUrl) . "'>" . htmlspecialchars($loginUrl) . "</a></p>
          <br>
          <p>Atenciosamente,<br>Sua equipe.</p>
        </body></html>";

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html; charset=UTF-8\r\n";
        $headers .= "From: Seu Sistema <nao-responder@seudominio.com.br>\r\n";

        @mail($email, "Acesso ao sistema - $plan_name", $mensagemHtml, $headers);
    }
} else {

    //STRIPE (PIX/BOL)

    if ($lastPayment) {
        $plan_name = $lastPayment['plan_name'] ?? 'Seu plano';
        $billing   = $lastPayment['billing']   ?? 'monthly';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Pagamento confirmado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <h2 class="text-success">Pagamento processado!</h2>

      <?php if ($fromStripe): ?>
        <p>O pagamento do plano <strong><?=htmlspecialchars($plan_name)?></strong> foi confirmado.</p>

        <?php if ($senha_temporaria !== null): ?>
          <p>Enviamos uma senha temporária para o e-mail
             <strong><?=htmlspecialchars($email)?></strong>.</p>
          <p>Use essa senha para fazer login e trocá-la assim que possível.</p>
        <?php else: ?>
          <p>Esse e-mail já possuía cadastro. Use sua senha atual ou recupere-a caso tenha esquecido.</p>
        <?php endif; ?>

      <?php elseif ($lastPayment): ?>
        <p>Seu pagamento foi registrado com sucesso.</p>
        <ul>
          <li><strong>Plano:</strong> <?=htmlspecialchars($lastPayment['plan_name'])?></li>
          <li><strong>Valor:</strong> R$ <?=htmlspecialchars($lastPayment['amount'])?></li>
          <li><strong>Método:</strong> <?=strtoupper(htmlspecialchars($lastPayment['method']))?></li>
          <li><strong>Status interno:</strong> <?=htmlspecialchars($lastPayment['status'])?></li>
        </ul>

        <?php if (!empty($lastPayment['pix_code']) && $lastPayment['method'] === 'pix'): ?>
          <p><strong>Código PIX (exemplo):</strong><br><?=htmlspecialchars($lastPayment['pix_code'])?></p>
        <?php elseif (!empty($lastPayment['boleto_line']) && $lastPayment['method'] === 'boleto'): ?>
          <p><strong>Linha digitável do boleto (exemplo):</strong><br><?=htmlspecialchars($lastPayment['boleto_line'])?></p>
        <?php endif; ?>

      <?php else: ?>
        <p>Não encontramos informações do pagamento na sessão.</p>
      <?php endif; ?>

      <a href="login.php" class="btn btn-primary mt-3">Ir para o login</a>
      <a href="planos.php" class="btn btn-outline-secondary mt-3 ms-2">Voltar aos planos</a>
    </div>
  </div>
</div>
</body>
</html>
