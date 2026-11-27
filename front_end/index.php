<?php
require_once __DIR__ . "/../back_end/conexao/conexao.php";
?>
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
    <nav><a class="nav-item active" href="index.php">▦ Visão geral</a><a class="nav-item " href="produtos.php">▤ Produtos</a></nav>
  </aside>
  <main class="main">
    <header class="topbar">
      <div class="breadcrumb">Estoque <span>/</span> <strong>Visão geral</strong></div>
    </header>
    <section class="page dashboard-page">
      <div class="page-heading">
        <div>
          <h1>Visão geral</h1>
          <p class="subtitle">Aqui está o resumo do seu estoque hoje.</p>
        </div>
      </div>
      <div class="stats-grid">
        <article class="stat-card">
          <div class="stat-top"><span>Produtos cadastrados</span><span class="stat-icon purple">▤</span></div><strong id="statProducts">
          <?php 
          $query ="Select count(*) as total from produtos";
          $stmt = mysqli_prepare($con, $query);
          mysqli_stmt_execute($stmt);
          $resultado = mysqli_stmt_get_result($stmt);
          $registro = mysqli_fetch_assoc($resultado);
          echo $registro["total"];
          ?>
        </strong><small><span class="muted">Itens diferentes no catálogo</span></small>
        </article>
        <article class="stat-card">
          <div class="stat-top"><span>Unidades em estoque</span><span class="stat-icon blue">▦</span></div><strong id="statUnits">
            <?php 
          $query ="Select sum(quantidade) as total from produtos";
          $stmt = mysqli_prepare($con, $query);
          mysqli_stmt_execute($stmt);
          $resultado = mysqli_stmt_get_result($stmt);
          $registro = mysqli_fetch_assoc($resultado);
          echo $registro["total"];
          ?>
          </strong><small><span class="muted">Somatório das quantidades</span></small>
        </article>
        <article class="stat-card">
          <div class="stat-top"><span>Estoque baixo</span><span class="stat-icon orange">!</span></div><strong id="statLow">
            <?php 
          $query ="Select count(*) as total from produtos where quantidade < estoque_minimo";
          $stmt = mysqli_prepare($con, $query);
          mysqli_stmt_execute($stmt);
          $resultado = mysqli_stmt_get_result($stmt);
          $registro = mysqli_fetch_assoc($resultado);
          echo $registro["total"];
          ?>
          </strong><small><span class="warning-text">Precisam de reposição</span></small>
        </article>
        <article class="stat-card">
          <div class="stat-top"><span>Valor em estoque</span><span class="stat-icon green">$</span></div><strong id="statValue">
                 <?php 
          $query ="Select sum(preco) as total from produtos";
          $stmt = mysqli_prepare($con, $query);
          mysqli_stmt_execute($stmt);
          $resultado = mysqli_stmt_get_result($stmt);
          $registro = mysqli_fetch_assoc($resultado);
          echo "R$ ".$registro["total"];
          ?>
          </strong><small><span class="muted">Com base no custo cadastrado</span></small>
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

    <?php
    $query = "SELECT * FROM produtos limit 5";
    $stmt = mysqli_prepare($con, $query);

    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    ?>

    <tbody id="recentProducts">
        <?php
        while ($registro = mysqli_fetch_assoc($resultado)) {
        ?>
            <tr>
                <td><?php echo $registro["nome"]; ?></td>

                <td><?php echo $registro["descricao"]; ?></td>

                <td><?php echo $registro["quantidade"]; ?></td>

                <td>
                    <?php
                    if ($registro["quantidade"] <= $registro["estoque_minimo"]) {
                        echo "Estoque baixo";
                    } else {
                        echo "Disponível";
                    }
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
          </div>
        </article>
     <article class="panel alerts-panel">
    <div class="panel-heading">
        <div>
            <h2>Estoque baixo</h2>
            <p>Produtos que precisam de atenção</p>
        </div>

        <?php
        $query = "SELECT * FROM produtos
                  WHERE quantidade <= estoque_minimo";

        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        $total_alertas = mysqli_num_rows($resultado);
        ?>

        <span class="alert-count">
            <?php echo $total_alertas; ?>
        </span>
    </div>

    <div class="low-stock-list">

        <table>
            <thead>
                <tr>
                    <th>PRODUTO</th>
                    <th>QUANTIDADE</th>
                    <th>ESTOQUE MÍNIMO</th>
                    <th>STATUS</th>
                </tr>
            </thead>

            <tbody>
                <?php
                while ($registro = mysqli_fetch_assoc($resultado)) {
                ?>
                    <tr>
                        <td>
                            <?php echo $registro["nome"]; ?>
                        </td>

                        <td>
                            <?php echo $registro["quantidade"]; ?>
                        </td>

                        <td>
                            <?php echo $registro["estoque_minimo"]; ?>
                        </td>

                        <td>
                            <span class="status-low">
                                Estoque baixo
                            </span>
                        </td>
                    </tr>
                <?php
                }

                if ($total_alertas == 0) {
                ?>
                    <tr>
                        <td colspan="4">
                            Nenhum produto com estoque baixo.
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

    </div>

    <button class="btn btn-outline full-btn" data-go="products">
        Conferir estoque <span>→</span>
    </button>
</article>
      </div>
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