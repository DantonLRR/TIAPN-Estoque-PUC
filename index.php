<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque Ideal - Sistema de Gestão de Roupas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style_Geral.css">
</head>

<body>
    <?php include('assets/navbar/navbar2.php');  ?>
    <section class="hero text-center d-flex flex-column align-items-center justify-content-center">
        <div class="container mt-5 pt-5">
            <h1 class="fw-bold display-5">Gerencie Seu Estoque de Roupas<br><span class="text-pink">Com Elegância</span></h1>
            <p class="text-muted mt-3">Sistema completo para controle de inventário, gestão de produtos e análise de vendas para sua loja de moda.</p>
            <a href="cadastro.php" class="btn btn-pink mt-4 px-4 py-2">Começar Agora</a>
            <a href="pages/login.php" class="btn btn-pink mt-4 px-4 py-2">Entrar</a>
        </div>
    </section>

    <!-- Cards -->
    <section class="features py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 text-center p-4 border-0 shadow-sm">
                        <div class="icon mb-3">
                            <i class="bi bi-box-seam fs-1 text-pink"></i>
                            <svg class="feature-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>

                        <h5 class="fw-bold">Controle de Estoque</h5>
                        <p class="text-muted small">Gerencie seu inventário em tempo real com alertas automáticos de baixo estoque.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center p-4 border-0 shadow-sm">
                        <div class="icon mb-3">
                            <i class="bi bi-graph-up-arrow fs-1 text-pink"></i>
                            <svg class="feature-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>
                        </div>

                        <h5 class="fw-bold">Análise de Vendas</h5>
                        <p class="text-muted small">Acompanhe suas vendas e identifique tendências para tomar decisões estratégicas.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center p-4 border-0 shadow-sm">
                        <div class="icon mb-3">
                            <i class="bi bi-bar-chart-line fs-1 text-pink"></i>
                            <svg class="feature-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                        </div>
                        <h5 class="fw-bold">Relatórios Detalhados</h5>
                        <p class="text-muted small">Visualize dados importantes através de relatórios completos e dashboards intuitivos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.js"></script>
</body>

</html>