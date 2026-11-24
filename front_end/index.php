<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StockEasy</title>
  <link rel="stylesheet" href="css/base.css">
  <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
  <aside class="sidebar"><a class="brand" href="index.php"><span class="brand-mark">S</span><span>stock<span class="brand-light">easy</span></span></a>
    <p class="nav-label">MENU PRINCIPAL</p>
    <nav><a class="nav-item active" href="index.php">▦ Visão geral</a><a class="nav-item " href="produtos.php">▤ Produtos</a><a class="nav-item " href="movimentacoes.php">⇄ Movimentações</a></nav>
  </aside>
  <main class="main">
    <header class="topbar">
      <div class="breadcrumb">Estoque <span>/</span> <strong>Visão geral</strong></div>
    </header>
    <section class="page dashboard-page">
      <div class="page-heading">
        <div>
          <p class="eyebrow">SEGUNDA-FEIRA, 23 DE NOVEMBRO</p>
          <h1>Visão geral</h1>
          <p class="subtitle">Aqui está o resumo do seu estoque hoje.</p>
        </div>
      </div>
      <div class="stats-grid">
        <article class="stat-card">
          <div class="stat-top"><span>Produtos cadastrados</span><span class="stat-icon purple">▤</span></div><strong id="statProducts">0</strong><small><span class="muted">Itens diferentes no catálogo</span></small>
        </article>
        <article class="stat-card">
          <div class="stat-top"><span>Unidades em estoque</span><span class="stat-icon blue">▦</span></div><strong id="statUnits">0</strong><small><span class="muted">Somatório das quantidades</span></small>
        </article>
        <article class="stat-card">
          <div class="stat-top"><span>Estoque baixo</span><span class="stat-icon orange">!</span></div><strong id="statLow">0</strong><small><span class="warning-text">Precisam de reposição</span></small>
        </article>
        <article class="stat-card">
          <div class="stat-top"><span>Valor em estoque</span><span class="stat-icon green">$</span></div><strong id="statValue">R$ 0,00</strong><small><span class="muted">Com base no custo cadastrado</span></small>
        </article>
      </div>

      <div class="content-grid">
        <article class="panel inventory-panel">
          <div class="panel-heading">
            <div>
              <h2>Produtos recentes</h2>
              <p>Últimos produtos adicionados ao catálogo</p>
            </div><button class="text-btn" data-go="products">Ver todos <span>→</span></button>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>PRODUTO</th>
                  <th>CATEGORIA</th>
                  <th>QUANTIDADE</th>
                  <th>STATUS</th>
                </tr>
              </thead>
              <tbody id="recentProducts"></tbody>
            </table>
          </div>
        </article>
        <article class="panel alerts-panel">
          <div class="panel-heading">
            <div>
              <h2>Estoque baixo</h2>
              <p>Produtos que precisam de atenção</p>
            </div><span class="alert-count" id="alertCount">0</span>
          </div>
          <div id="lowStockList" class="low-stock-list"></div>
          <button class="btn btn-outline full-btn" data-go="products">Conferir estoque <span>→</span></button>
        </article>
      </div>
      <article class="panel activity-panel">
        <div class="panel-heading">
          <div>
            <h2>Movimentações recentes</h2>
            <p>Entradas e saídas registradas no sistema</p>
          </div><button class="text-btn" data-go="movements">Ver movimentações <span>→</span></button>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>PRODUTO</th>
                <th>TIPO</th>
                <th>QUANTIDADE</th>
                <th>DATA</th>
              </tr>
            </thead>
            <tbody id="recentMovements"></tbody>
          </table>
        </div>
      </article>
    </section>
    <div class="modal-backdrop" id="productModal" aria-hidden="true">
      <section class="modal" role="dialog" aria-modal="true" aria-labelledby="productModalTitle">
        <div class="modal-heading">
          <div>
            <h2 id="productModalTitle">Novo produto</h2>
            <p>Preencha os dados do produto.</p>
          </div><button class="close-btn" type="button" data-close-modal aria-label="Fechar">×</button>
        </div>
        <form id="productForm">
          <label>Nome do produto<input name="name" type="text" required></label>
          <div class="form-row">
            <label>Código<input name="code" type="text" required></label>
            <label>Categoria<input name="category" type="text" required></label>
          </div>
          <div class="form-row">
            <label>Preço (R$)<input name="price" type="number" min="0" step="0.01" required></label>
            <label>Quantidade<input name="quantity" type="number" min="0" step="1" required></label>
          </div>
          <div class="modal-actions"><button class="btn btn-outline" type="button" data-close-modal>Cancelar</button><button class="btn btn-primary" type="submit">Salvar produto</button></div>
        </form>
      </section>
    </div>
  </main>
  <script src="js/dashboard.js"></script>
</body>

</html>