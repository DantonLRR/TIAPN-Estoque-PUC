<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Estoque Ideal - Planos</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Seus CSS -->
  <link rel="stylesheet" href="../css/planos.css">
  <link rel="stylesheet" href="../css/style_Geral.css">

  <!-- Estilos essenciais para esta página -->
  <style>
    :root { --pink: #F7446B; }

    /* Compensa navbar fixed-top */
    body { padding-top: 70px; }

    /* HERO / CARROSSEL */
    .container-back{
      position: relative;
      width: 100%;
      height: 40em;            /* ajuste conforme quiser */
      overflow: hidden;
    }
    .container-back .hero-img{
      height: 40em;            /* igual ao container */
      object-fit: cover;
    }
    .container-back .carousel-overlay{
      position: absolute; inset: 0;
      background: linear-gradient(to bottom, rgba(247,68,107,.55), rgba(247,68,107,.35));
      pointer-events: none;
    }
    .container-back .carousel-caption{
      position: absolute; top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      text-align: center; color: #fff;
      text-shadow: 0 2px 14px rgba(0,0,0,.3);
      max-width: 900px;
    }
    .container-back .carousel-caption h2{
      font-weight: 700;
      font-size: clamp(1.6rem, 3vw, 2.6rem);
      margin-bottom: .5rem;
    }
    .container-back .carousel-caption p{
      font-size: clamp(1rem, 2vw, 1.2rem);
      margin-bottom: 1rem;
    }
    .btn-pink {
      background-color: var(--pink);
      color: #fff;
      border: none;
      border-radius: 8px;
      transition: all .2s ease;
    }
    .btn-pink:hover { background-color: #d93c5f; color: #fff; }

    .carousel-indicators [data-bs-target]{ background-color: #fff; opacity: .7; }
    .carousel-indicators .active{ background-color: var(--pink); opacity: 1; }
    .carousel-control-prev-icon, .carousel-control-next-icon{
      filter: drop-shadow(0 2px 6px rgba(0,0,0,.35));
    }

    /* CARDS DE PLANOS */
    .card { border-radius: 12px; border: 2px solid var(--pink); }
    .text-pink { color: var(--pink) !important; }
    .card-price { font-size: 1.8rem; margin-bottom: .5rem; }
    .plans-section h1 { font-weight: 700; }
    .plans-list ul { padding-left: 0; }
    .plans-list li { margin: 6px 0; list-style: none; }
  </style>
</head>
<body>

  <!-- NAVBAR fixa (sua navbar padrão) -->
  <?php include('../assets/navbar/navbar.php'); ?>

  <!-- HERO / CARROSSEL -->
  <div class="container-back">
    <div id="heroCarousel" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="4000">
      <!-- indicadores -->
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>

      <div class="carousel-inner h-100">
        <!-- Slide 1 -->
        <div class="carousel-item active h-100">
          <img src="../img/closet.png" class="d-block w-100 hero-img" alt="Gestão de estoque slide 1">
          <div class="carousel-overlay"></div>
          <div class="carousel-caption">
            <h2>Controle seu estoque sem dor de cabeça</h2>
            <p>Planos flexíveis para o tamanho do seu negócio</p>
            <a href="#planos" class="btn btn-pink px-4 py-2">Ver Planos</a>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item h-100">
          <img src="../img/modelosdecloset.jpeg" class="d-block w-100 hero-img" alt="Gestão de estoque slide 2">
          <div class="carousel-overlay"></div>
          <div class="carousel-caption">
            <h2>Relatórios claros e decisões rápidas</h2>
            <p>Visualize entradas, saídas e estoque mínimo em 1 clique</p>
            <a href="#planos" class="btn btn-pink px-4 py-2">Conhecer Planos</a>
          </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item h-100">
          <img src="../img/werehouse.jpg" class="d-block w-100 hero-img" alt="Gestão de estoque slide 3">
          <div class="carousel-overlay"></div>
          <div class="carousel-caption">
            <h2>Integração e suporte de verdade</h2>
            <p>Time pronto para ajudar sua operação</p>
            <a href="#planos" class="btn btn-pink px-4 py-2">Assinar</a>
          </div>
        </div>
      </div>

      <!-- Botoes -->
      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Anterior">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Próximo">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
    </div>
  </div>

  <!-- desce planos -->
  <section id="planos" class="container my-5 plans-section">
    <h1 class="text-center mb-4">Escolha o plano ideal para você</h1>

    <div class="row justify-content-center plans-list">
      <!-- Plano 1 -->
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body text-center">
            <h4 class="card-title text-pink fw-bold">Plano 1</h4>
            <h2 class="card-price text-pink">R$ 1/mês</h2>
            <p class="card-text mt-3">Perfeito para começar.</p>
            <ul class="list-unstyled mb-4">
        
            </ul>
            <a href="#" class="btn btn-pink w-100">Assinar Agora</a>
          </div>
        </div>
      </div>

      <!-- Plano 2 -->
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body text-center">
            <h4 class="card-title text-pink fw-bold">Plano 2</h4>
            <h2 class="card-price text-pink">R$ 500,00/mês</h2>
            <p class="card-text mt-3">Para equipes em crescimento.</p>
            <ul class="list-unstyled mb-4">
            
            </ul>
            <a href="#" class="btn btn-pink w-100">Assinar Agora</a>
          </div>
        </div>
      </div>

      <!-- Plano 3 -->
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body text-center">
            <h4 class="card-title text-pink fw-bold">Plano 3</h4>
            <h2 class="card-price text-pink">R$ 1.000,00/mês</h2>
            <p class="card-text mt-3">Para operações maiores.</p>
            <ul class="list-unstyled mb-4">
            
            </ul>
            <a href="#" class="btn btn-pink w-100">Assinar Agora</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Scroll pros planos  -->
  <script>
    document.querySelectorAll('a[href^="#planos"]').forEach(a=>{
      a.addEventListener('click', e=>{
        e.preventDefault();
        document.querySelector('#planos')?.scrollIntoView({behavior:'smooth'});
      });
    });
  </script>
</body>
</html>
