<?php
// ====== CONEXÃO E CRUD DOS PLANOS ======
require_once __DIR__ . '/../crud/conexao_DB.php';
require_once __DIR__ . '/../crud/planos_CRUD.php';
if (!isset($pdo) || !($pdo instanceof PDO)) {
  die('[ERRO] Conexão PDO não carregada. Verifique ../crud/conexao_DB.php');
}

$planos = listarPlanos($pdo, true);


$ordem = ['Mensal'=>1,'Trimestral'=>2,'Semestral'=>3,'Anual'=>4];
usort($planos, function($a,$b) use($ordem){
  $na = trim($a['name'] ?? '');
  $nb = trim($b['name'] ?? '');
  $oa = $ordem[$na] ?? 99;
  $ob = $ordem[$nb] ?? 99;
  return $oa <=> $ob;
});


$labels = [
  'Mensal'     => 'mês',
  'Trimestral' => 'tri',
  'Semestral'  => 'sem',
  'Anual'      => 'ano'
];


$savings = [
  'Mensal'     => 0,
  'Trimestral' => 10,
  'Semestral'  => 15,
  'Anual'      => 18
];


function normaliza_nome_plano(string $nome): string {
  $nome = trim($nome);
  $nome = mb_strtolower($nome, 'UTF-8');
  $nome = ucfirst($nome); 
  return $nome;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Estoque Ideal - Assinaturas</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style_Geral.css">
  
  <style>
    /* ================= HERO / CARROSSEL =============== */
    .container-back {
      width: 100%;
      max-height: 380px !important;
      overflow: hidden;
      position: relative;
    }

    .container-back .carousel-inner,
    .container-back .carousel-item {
      height: 100%;
    }

    .hero-img {
      width: 100%;
      height: 380px !important;     
      object-fit: cover !important;  
      display: block;
    }

    /* ================== PLANS SECTION ================= */
    .plans-section .row {
      justify-content: center;
      align-items: stretch;
    }

    .plan-card {
      border: 2px solid var(--pink);
      border-radius: 12px;
      transition: all 0.2s ease;
      position: relative;
      overflow: visible;
      background: #fff;
 
    }

    .plan-card:hover {
      transform: none;
      box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }

    .plans-section .card-body {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .plans-section .card-title {
      font-size: 1.25rem;
    }

    .plans-section .card-price {
      font-size: 1.8rem;
      margin-bottom: .5rem;
    }

    /* ===== texto de economia abaixo do botão ===== */
    .save-text {
      font-size: .9rem;
      font-weight: 600;
      color: #777;    
    }

    /* ================= RIBBON "MAIS POPULAR" ============ */
    .ribbon {
      position: absolute;
      top: 12px;
      left: -8px;
      padding: 6px 14px;
      color: #fff;
      font-weight: 700;
      font-size: .8rem;
      background: linear-gradient(90deg, #f7446b, #ff7a95);
      border-top-right-radius: 8px;
      border-bottom-right-radius: 8px;
      box-shadow: 0 3px 10px rgba(0,0,0,.12);
      z-index: 6;
    }

    .ribbon:before {
      content: "";
      position: absolute;
      left: 0;
      bottom: -6px;
      border-top: 6px solid #b8314c;
      border-left: 8px solid transparent;
    }

    @media (max-width: 768px) {
      .hero-img {
        height: 260px !important;
      }
      .container-back {
        max-height: 260px !important;
      }
    }

    @media (max-width: 480px) {
      .hero-img {
        height: 200px !important;
      }
      .container-back {
        max-height: 200px !important;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <?php include('../assets/navbar/navbar.php'); ?>

  <!-- ==================== HERO / CARROSSEL ==================== -->
  <div class="container-back">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">

      <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>

      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="../img/closet.png" class="hero-img" alt="Controle de estoque">
          <div class="carousel-caption">
            <h2>Controle seu estoque sem dor de cabeça</h2>
            <p>Assine o plano que se encaixa no seu negócio</p>
          </div>
        </div>

        <div class="carousel-item">
          <img src="../img/modelosdecloset.jpeg" class="hero-img" alt="Relatórios e gestão">
          <div class="carousel-caption">
            <h2>Relatórios claros e decisões rápidas</h2>
            <p>Visualize entradas, saídas e estoque mínimo com facilidade</p>

          </div>
        </div>

        <div class="carousel-item">
          <img src="../img/werehouse.jpg" class="hero-img" alt="Integração e suporte">
          <div class="carousel-caption">
            <h2>Integração e suporte de verdade</h2>
            <p>Time pronto para ajudar sua operação a crescer</p>
          </div>
        </div>
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Anterior">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Próximo">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
    </div>
  </div>

  <!-- ==================== SEÇÃO PLANOS ==================== -->
  <section id="planos" class="container my-5 plans-section">
    <h1 class="text-center mb-4">Escolha sua assinatura</h1>

    <?php if (empty($planos)): ?>
      <div class="alert alert-warning text-center">
        Nenhum plano ativo encontrado.
      </div>
    <?php else: ?>
      <div class="row g-4 justify-content-center align-items-stretch">
        <?php foreach ($planos as $p): 
          $rawName   = $p['name'] ?? '';
          $normName  = normaliza_nome_plano($rawName);
          $label     = $labels[$normName] ?? 'período';
          $price     = number_format((float)$p['monthly_price'], 2, ',', '.');
          $save      = (int)($savings[$normName] ?? 0);
          $isPopular = ($normName === 'Semestral');
        ?>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm plan-card">

              <?php if ($isPopular): ?>
                <div class="ribbon">Mais popular</div>
              <?php endif; ?>

              <div class="card-body text-center d-flex flex-column">
                <h4 class="card-title text-pink fw-bold">
                  <?= htmlspecialchars($normName) ?>
                </h4>

                <h2 class="card-price text-pink">
                  R$ <?= $price ?>/<?= $label ?>
                </h2>

                <p class="card-text mt-3">
                  <?= htmlspecialchars($p['description'] ?? 'Assinatura válida por período.') ?>
                </p>

                <a href="../pages/pagamento.php?plan_id=<?= (int)$p['id'] ?>"
                   class="btn btn-pink w-100 mt-auto">
                  Assinar Agora
                </a>

                <?php if ($save > 0): ?>
                  <p class="save-text mt-2 mb-0">
                    Economize <?= $save ?>%
                  </p>
                <?php endif; ?>

              </div>

            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // scroll suave
    document.querySelectorAll('a[href^="#planos"]').forEach(a => {
      a.addEventListener('click', e => {
        e.preventDefault();
        document.querySelector('#planos')?.scrollIntoView({behavior: 'smooth'});
      });
    });
  </script>
</body>
</html>
