<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistema de Estoque</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style_Geral.css">
  <link rel="stylesheet" href="../css/style_Login.css">
</head>
<body>

  <div class="container-fluid vh-100 d-flex p-0">
    
 
    <div class="col-lg-6 col-md-6 d-none d-md-flex align-items-center justify-content-center left-side">
      <div class="text-center text-white">
        <i class="bi bi-bag fs-1 mb-3"></i>
        <h2 class="fw-bold">Sistema de Estoque</h2>
        <p class="lead">Gerencie seu inventário de moda com elegância e eficiência</p>
      </div>
    </div>


    <div class="col-lg-6 col-md-6 d-flex align-items-center justify-content-center bg-light">
      <div class="card shadow p-4 border-0" style="width: 100%; max-width: 380px; border-radius: 12px;">
        <h5 class="fw-bold mb-1">Conecte-se</h5>
        
        <p class="text-muted small mb-4">Entre com suas credenciais para acessar o sistema</p>
        <?php include('../assets/mensagem/mensagem.php'); ?>
        <form action="../crud/autentificacao.php" method="POST">
          <div class="mb-3">
            <label for="usuario" class="form-label small fw-semibold">Usuário</label>
            <input type="text" class="form-control input-pink" id="usuario" name="usuario" placeholder="Digite seu usuário" required>
          </div>

          <div class="mb-3">
            <label for="senha" class="form-label small fw-semibold">Senha</label>
            <input type="password" class="form-control input-pink" id="senha" name="senha" placeholder="Digite sua senha" required>
          </div>

          <button type="submit" class="btn btn-pink w-100 py-2 mt-2">Entrar</button>
        </form>

        <footer class="text-center mt-4">
          <p class="text-muted small mb-0">© 2025 Sistema de Estoque de Roupas. Todos os direitos reservados.</p>
        </footer>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.js"></script>
</body>
</html>
