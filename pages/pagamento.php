<?php
require_once __DIR__ . '/../crud/conexao_DB.php';     
require_once __DIR__ . '/../crud/planos_CRUD.php';    
require_once __DIR__ . '/../crud/pagamento_CRUD.php'; 

session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
function csrf_token(){ return $_SESSION['csrf']; }

$plan_id = (int)($_GET['plan_id'] ?? 0);
$plan = buscarPlanoAtivo($conn, $plan_id);

if (!$plan) {
  http_response_code(404);
  echo "Plano não encontrado ou inativo.";
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pagamento - <?=htmlspecialchars($plan['name'])?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style_Geral.css">
  <link rel="stylesheet" href="../css/pagamento.css">

  <style>
    .card{
    margin-top: 4rem;
    }
    .card:hover{
    transform: none;
    }
  </style>
</head>
<body>
  <?php include('../assets/navbar/navbar.php'); ?>

  <div class="container checkout-wrapper">
    <div class="row justify-content-center">
      <!-- Finalizar pagamento -->
      <div class="col-12">
        <div class="card shadow-sm p-4">
          <h4 class="mb-3 text-center">Finalizar pagamento</h4>

          <form action="processar_pagamento.php" method="post" id="checkoutForm" novalidate>
            <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
            <input type="hidden" name="plan_id" value="<?=$plan['id']?>">
            <!-- cobrança recorrente -->
            <input type="hidden" name="billing_cycle" value="monthly">

            <!-- Dados básicos -->
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nome completo</label>
                <input type="text" class="form-control" name="payer_name" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">E-mail</label>
                <input type="email" class="form-control" name="payer_email" required>
              </div>
            </div>

            <hr class="my-4">

            <!-- Cobrança recorrente -->
            <div class="mb-3">
              <label class="form-label">Cobrança recorrente</label>
              <p class="text-muted small mb-1">
                A assinatura será renovada automaticamente a cada mês. Você poderá cancelar a qualquer momento.
              </p>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="recurring" name="recurring" checked>
                <label class="form-check-label" for="recurring">Ativar cobrança recorrente</label>
              </div>
            </div>

            <!-- Método de pagamento -->
            <div class="mb-3">
              <label class="form-label">Método de pagamento</label>
              <div class="d-flex flex-wrap gap-3">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="method" id="payPix" value="pix" checked>
                  <label class="form-check-label" for="payPix">PIX</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="method" id="payBoleto" value="boleto">
                  <label class="form-check-label" for="payBoleto">Boleto</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="method" id="payCard" value="card">
                  <label class="form-check-label" for="payCard">Cartão</label>
                </div>
              </div>
            </div>
            <hr class="my-4">

            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="muted">Total estimado</div>
                <div class="fs-4 fw-bold text-pink" id="totalPreview">
                  R$ <?=number_format($plan['monthly_price'],2,',','.')?>
                </div>
              </div>
              <button type="submit" class="btn btn-pink btn-lg">Pagar</button>
            </div>

            <input type="hidden" name="client_total_preview" id="clientTotalPreview"
                   value="<?=htmlspecialchars($plan['monthly_price'])?>">
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>

    // total estimado fixo mensal (servidor ainda recalcula)
    const totalPreview = document.getElementById('totalPreview');
    const clientTotal = document.getElementById('clientTotalPreview');
    const monthly = <?=json_encode((float)$plan['monthly_price'])?>;
    totalPreview.textContent = 'R$ ' + monthly.toFixed(2).replace('.', ',');
    clientTotal.value = monthly.toFixed(2);
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
