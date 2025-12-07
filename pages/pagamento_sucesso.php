<?php
require_once __DIR__ . '/../crud/conexao_DB.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Envia e-mail com senha para o cliente 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();


// Funçãopara envio de senha por e-mail usando PHPMailer 

function enviarSenhaPorEmail(string $email, string $nome, string $senha_temporaria, string $plan_name, string $loginUrl): void
{
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

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'estoqueideal33@gmail.com';       // remetente
        $mail->Password   = 'shqw jido vant lksg';         
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->CharSet = 'UTF-8';

        // Remetente e destinatário
        $mail->setFrom('estoqueideal33@gmail.com', 'estoqueideal');
        $mail->addAddress($email, $nome);

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = "Acesso ao sistema - $plan_name";
        $mail->Body    = $mensagemHtml;

        //  texto do email
        $mail->AltBody =
            "Olá, {$nome}!\n\n" .
            "Seu pagamento do plano {$plan_name} foi confirmado.\n\n" .
            "Usuário: {$email}\n" .
            "Senha temporária: {$senha_temporaria}\n\n" .
            "Acesse: {$loginUrl}\n\n" .
            "Atenciosamente,\nSua equipe.";

        $mail->send();
    } catch (Exception $e) {
        // Em produção, só loga; em teste, pode exibir
        error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo);
        // echo '<pre>Erro ao enviar e-mail: ' . htmlspecialchars($mail->ErrorInfo) . '</pre>';
    }
}


// ROTA DE REENVIO DE SENHA (TESTE) 


if (isset($_GET['reenviar_senha'], $_GET['email'])) {

    if (!isset($conn) || !($conn instanceof mysqli)) {
        die("Falha na conexão com o banco de dados.");
    }

    $emailReenvio = $_GET['email'];

    // Buscar usuário
    $sql = "SELECT id, nome FROM usuarios WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $emailReenvio);
    mysqli_stmt_execute($stmt);
    $result  = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$usuario) {
        die("Usuário não encontrado para o e-mail: " . htmlspecialchars($emailReenvio));
    }

    // Gerar nova senha temporária
    $senha_temporaria = bin2hex(random_bytes(4));
    $senha_hash       = password_hash($senha_temporaria, PASSWORD_DEFAULT);

    // Atualizar no banco
    $sqlUp = "UPDATE usuarios SET senha = ?, must_change_password = 1 WHERE email = ?";
    $stmtUp = mysqli_prepare($conn, $sqlUp);
    mysqli_stmt_bind_param($stmtUp, "ss", $senha_hash, $emailReenvio);
    mysqli_stmt_execute($stmtUp);
    mysqli_stmt_close($stmtUp);

    // Dados para o e-mail
    $nome      = $usuario['nome'] ?? 'Cliente';
    $plan_name = 'Seu plano';
    $loginUrl  = "http://localhost/TIAPN-Estoque-PUC/pages/login.php";

    // Enviar e-mail
    enviarSenhaPorEmail($emailReenvio, $nome, $senha_hash, $plan_name, $loginUrl);
    // Mensagem  de retorno
// Mensagem  de retorno
echo "<!DOCTYPE html>
<html lang='pt-BR'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <title>Reenvio de senha</title>
  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='bg-light'>
  <div class='container-fluid vh-100 d-flex align-items-center justify-content-center'>
    <div class='card shadow-sm' style='max-width: 480px; width: 100%;'>
      <div class='card-body text-center p-4'>
        <h1 class='h4 mb-3 text-success'>Senha reenviada</h1>
        <p class='mb-2'>
          Uma nova senha temporária foi enviada para o e-mail
          <strong>" . htmlspecialchars($emailReenvio) . "</strong>.
        </p>
        <p class='text-muted mb-4'>
          Use essa senha para acessar o sistema e alterá-la no primeiro login.
        </p>
        <a href='login.php' class='btn btn-primary w-100'>
          Ir para o login
        </a>
      </div>
    </div>
  </div>
</body>
</html>";
exit;
}


//  ORIGINAL (STRIPE)


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
        enviarSenhaPorEmail($email, $nome, $senha_hash, $plan_name, $loginUrl);
    }
} else {

    // STRIPE (PIX/BOL)

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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pagamento confirmado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
    <div class="card shadow-sm" style="max-width: 560px; width: 100%;">
      <div class="card-body p-4">
        <h1 class="h4 text-success mb-3">Pagamento processado</h1>

        <?php if ($fromStripe): ?>
          <p class="mb-2">
            O pagamento do plano <strong><?=htmlspecialchars($plan_name)?></strong> foi confirmado.
          </p>

          <?php if ($senha_temporaria !== null): ?>
            <p class="mb-1">
              Enviamos uma senha temporária para o e-mail
              <strong><?=htmlspecialchars($email)?></strong>.
            </p>
            <p class="text-muted">
              Use essa senha para fazer login e trocá-la assim que possível.
            </p>

            <p class="mt-3 mb-2 fw-semibold">Não recebeu o e-mail?</p>
            <a href="pagamento_sucesso.php?reenviar_senha=1&email=<?=urlencode($email)?>"
               class="btn btn-warning btn-sm">
              Reenviar senha para este e-mail
            </a>

          <?php else: ?>
            <p class="mb-3">
              Esse e-mail já possuía cadastro. Use sua senha atual ou recupere-a caso tenha esquecido.
            </p>

            <p class="mt-3 mb-2 fw-semibold">Precisa de ajuda com a senha?</p>
            <a href="pagamento_sucesso.php?reenviar_senha=1&email=<?=urlencode($email)?>"
               class="btn btn-warning btn-sm">
              Reenviar senha para este e-mail
            </a>
          <?php endif; ?>

        <?php elseif ($lastPayment): ?>
          <p class="mb-2">Seu pagamento foi registrado com sucesso.</p>
          <ul class="list-unstyled mb-3">
            <li><strong>Plano:</strong> <?=htmlspecialchars($lastPayment['plan_name'])?></li>
            <li><strong>Valor:</strong> R$ <?=htmlspecialchars($lastPayment['amount'])?></li>
            <li><strong>Método:</strong> <?=strtoupper(htmlspecialchars($lastPayment['method']))?></li>
            <li><strong>Status interno:</strong> <?=htmlspecialchars($lastPayment['status'])?></li>
          </ul>

          <?php if (!empty($lastPayment['pix_code']) && $lastPayment['method'] === 'pix'): ?>
            <p class="small text-muted">
              <strong>Código PIX (exemplo):</strong><br>
              <?=nl2br(htmlspecialchars($lastPayment['pix_code']))?>
            </p>
          <?php elseif (!empty($lastPayment['boleto_line']) && $lastPayment['method'] === 'boleto'): ?>
            <p class="small text-muted">
              <strong>Linha digitável do boleto (exemplo):</strong><br>
              <?=nl2br(htmlspecialchars($lastPayment['boleto_line']))?>
            </p>
          <?php endif; ?>

        <?php else: ?>
          <p class="mb-3">Não encontramos informações do pagamento na sessão.</p>
        <?php endif; ?>

        <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
          <a href="login.php" class="btn btn-primary w-100">Ir para o login</a>
          <a href="planos.php" class="btn btn-outline-secondary w-100">Voltar aos planos</a>
        </div>

      </div>
    </div>
  </div>
</body>
</html>

