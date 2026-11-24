<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StockEasy</title>
  <link rel="stylesheet" href="css/base.css">
  <link rel="stylesheet" href="css/movements.css">
</head>

<body>
  <aside class="sidebar"><a class="brand" href="index.php"><span class="brand-mark">S</span><span>stock<span class="brand-light">easy</span></span></a>
    <p class="nav-label">MENU PRINCIPAL</p>
    <nav><a class="nav-item " href="index.php">▦ Visão geral</a><a class="nav-item " href="produtos.php">▤ Produtos</a><a class="nav-item active" href="movimentacoes.php">⇄ Movimentações</a></nav>
  </aside>
  <main class="main">
    <header class="topbar">
      <div class="breadcrumb">Estoque <span>/</span> <strong>Movimentações</strong></div>
    </header>
    <section class="page movements-page">
      <div class="page-heading">
        <div>
          <p class="eyebrow">HISTÓRICO</p>
          <h1>Movimentações</h1>
          <p class="subtitle">Acompanhe as entradas e saídas do seu estoque.</p>
        </div><button class="btn btn-primary" id="newMovementBtn">＋ Registrar movimentação</button>
      </div>
      <article class="panel">
        <div class="toolbar">
          <div class="search-box"><span>⌕</span><input id="movementSearch" type="search" placeholder="Buscar produto..."></div><select id="movementFilter" aria-label="Filtrar movimentação">
            <option value="">Todas as movimentações</option>
            <option value="Entrada">Entradas</option>
            <option value="Saída">Saídas</option>
          </select>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>PRODUTO</th>
                <th>TIPO</th>
                <th>QUANTIDADE</th>
                <th>DATA</th>
                <th>OBSERVAÇÃO</th>
              </tr>
            </thead>
            <tbody id="movementsTable"></tbody>
          </table>
        </div>
        <div class="table-footer"><span id="movementCount">0 movimentações</span>
      </article>
    </section>
    <div class="modal-backdrop" id="movementModal" aria-hidden="true">
      <section class="modal" role="dialog" aria-modal="true" aria-labelledby="movementModalTitle">
        <div class="modal-heading">
          <div>
            <h2 id="movementModalTitle">Registrar movimentação</h2>
            <p>Informe os dados da entrada ou saída.</p>
          </div><button class="close-btn" type="button" data-close-modal aria-label="Fechar">×</button>
        </div>
        <form id="movementForm">
          <label>Produto<input name="product" type="text" required></label>
          <div class="form-row">
            <label>Tipo<select name="type" required>
                <option value="">Selecione</option>
                <option>Entrada</option>
                <option>Saída</option>
              </select></label>
            <label>Quantidade<input name="quantity" type="number" min="1" step="1" required></label>
          </div>
          <label>Observação<input name="note" type="text"></label>
          <div class="modal-actions"><button class="btn btn-outline" type="button" data-close-modal>Cancelar</button><button class="btn btn-primary" type="submit">Registrar</button></div>
        </form>
      </section>
    </div>
  </main>
  <script src="js/movements.js"></script>
</body>

</html>