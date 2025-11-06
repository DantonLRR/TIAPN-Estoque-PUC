<?php
// pagamento.php
require __DIR__ . '/../assets/crud/conexao_DB.php';

session_start();


if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
function csrf_token(){ return $_SESSION['csrf']; }

$plan_id = (int)($_GET['plan_id'] ?? 0);

// Busca plano
$stmt = $pdo->prepare("SELECT id, name, description, monthly_price, yearly_price FROM plans WHERE id=? AND is_active=1");
$stmt->execute([$plan_id]);
$plan = $stmt->fetch();

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

</head>
<body>
  <?php include('../assets/navbar/navbar.php'); ?>

  <div class="container my-4">
    <div class="row g-4">
      <!-- Resumo do plano -->
      <div class="col-lg-4">
        <div class="card card-summary shadow-sm">
          <div class="card-body">
            <span class="badge badge-pink">Plano</span>
            <h4 class="mt-2 mb-1"><?=htmlspecialchars($plan['name'])?></h4>
            <p class="text-muted mb-3"><?=htmlspecialchars($plan['description'] ?? 'Plano selecionado')?></p>

            <div class="d-grid gap-2">
              <div class="p-3 border rounded">
                <div class="d-flex justify-content-between">
                  <span>Mensal</span>
                  <strong>R$ <?=number_format($plan['monthly_price'],2,',','.')?></strong>
                </div>
                <small class="text-muted">Cobre 30 dias de uso</small>
              </div>
              <div class="p-3 border rounded">
                <div class="d-flex justify-content-between">
                  <span>Anual</span>
                  <strong>R$ <?=number_format($plan['yearly_price'],2,',','.')?></strong>
                </div>
                <small class="text-muted">Economia vs. mensal</small>
              </div>
            </div>

            <hr>
            <p class="muted mb-0">Os valores acima são carregados do banco. O total final é validado no servidor.</p>
          </div>
        </div>
      </div>

      <!-- Formulário de pagamento -->
      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="mb-3">Finalizar pagamento</h4>

            <form action="processar_pagamento.php" method="post" id="checkoutForm" novalidate>
              <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
              <input type="hidden" name="plan_id" value="<?=$plan['id']?>">

              <!-- Dados do pagador -->
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

              <!-- Ciclo de cobrança -->
              <div class="mb-3">
                <label class="form-label">Ciclo de cobrança</label>
                <div class="d-flex gap-3">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="billing_cycle" id="cycleMonthly" value="monthly" checked>
                    <label class="form-check-label" for="cycleMonthly">Mensal (R$ <?=number_format($plan['monthly_price'],2,',','.')?>)</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="billing_cycle" id="cycleYearly" value="yearly">
                    <label class="form-check-label" for="cycleYearly">Anual (R$ <?=number_format($plan['yearly_price'],2,',','.')?>)</label>
                  </div>
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

              <!-- Campos do cartão (visíveis só se cartão for selecionado) -->
              <div id="cardFields" class="row g-3 d-none">
                <div class="col-md-6">
                  <label class="form-label">Número do cartão</label>
                  <input type="text" class="form-control" name="card_number" inputmode="numeric" maxlength="19" placeholder="0000 0000 0000 0000">
                </div>
                <div class="col-md-3">
                  <label class="form-label">Validade</label>
                  <input type="text" class="form-control" name="card_exp" placeholder="MM/AA">
                </div>
                <div class="col-md-3">
                  <label class="form-label">CVV</label>
                  <input type="password" class="form-control" name="card_cvv" maxlength="4" inputmode="numeric">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Nome impresso</label>
                  <input type="text" class="form-control" name="card_name">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Parcelas</label>
                  <select name="installments" class="form-select">
                    <option value="1">À vista</option>
                    <option value="2">2x</option>
                    <option value="3">3x</option>
                    <option value="4">4x</option>
                    <option value="5">5x</option>
                    <option value="6">6x</option>
                  </select>
                </div>
              </div>

              <hr class="my-4">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <div class="muted">Total estimado</div>
                  <div class="fs-4 fw-bold text-pink" id="totalPreview">R$ <?=number_format($plan['monthly_price'],2,',','.')?></div>
                </div>
                <button type="submit" class="btn btn-pink btn-lg">Pagar</button>
              </div>

              <input type="hidden" name="client_total_preview" id="clientTotalPreview" value="<?=htmlspecialchars($plan['monthly_price'])?>">
            </form>
          </div>
        </div>

        <!-- Dica PIX (mock visual) -->
        <div class="alert alert-light border mt-3">
          <strong>Demo:</strong> no método <em>PIX</em> e <em>Boleto</em>, o sistema registra o pagamento como <code>pending</code>
          e redireciona para a página de “Obrigado” mostrando um código/linha digitável de exemplo.
        </div>
      </div>
    </div>
  </div>

  <script>
    // troca de método: exibe/esconde campos do cartão
    const payCard = document.getElementById('payCard');
    const cardFields = document.getElementById('cardFields');
    document.querySelectorAll('input[name="method"]').forEach(r => {
      r.addEventListener('change', () => {
        cardFields.classList.toggle('d-none', !payCard.checked);
      });
    });

    // preview do total (não confiável — servidor recalcula)
    const totalPreview = document.getElementById('totalPreview');
    const clientTotal = document.getElementById('clientTotalPreview');
    document.querySelectorAll('input[name="billing_cycle"]').forEach(r => {
      r.addEventListener('change', () => {
        const monthly = <?=json_encode((float)$plan['monthly_price'])?>;
        const yearly  = <?=json_encode((float)$plan['yearly_price'])?>;
        const v = r.value === 'monthly' ? monthly : yearly;
        totalPreview.textContent = 'R$ ' + v.toFixed(2).replace('.', ',');
        clientTotal.value = v.toFixed(2);
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
