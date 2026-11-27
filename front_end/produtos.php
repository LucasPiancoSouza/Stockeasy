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
  <link rel="stylesheet" href="css/products.css">
</head>

<body>
  <aside class="sidebar"><a class="brand" href="index.php"><span class="brand-mark">S</span><span>stock<span class="brand-light">easy</span></span></a>
    <p class="nav-label">MENU PRINCIPAL</p>
    <nav><a class="nav-item " href="index.php">▦ Visão geral</a><a class="nav-item active" href="produtos.php">▤ Produtos</a></nav>
  </aside>
  <main class="main">
    <header class="topbar">
      <div class="breadcrumb">Estoque <span>/</span> <strong>Produtos</strong></div>
    </header>
    <section class="page products-page">
      <div class="page-heading">
        <div>
          <p class="eyebrow">CATÁLOGO</p>
          <h1>Produtos</h1>
          <p class="subtitle">Gerencie os produtos cadastrados no seu estoque.</p>
        </div><button class="btn btn-primary" id="addProductBtn">＋ Novo produto</button>
      </div>
      <article class="panel">
        <div class="toolbar">
          <div class="search-box"><span>⌕</span><input id="productSearch" type="search" placeholder="Buscar por nome ou código..."></div><select id="categoryFilter" aria-label="Filtrar categoria">
            <option value="">Todas as categorias</option>
          </select><select id="stockFilter" aria-label="Filtrar estoque">
            <option value="">Todos os status</option>
            <option value="normal">Estoque normal</option>
            <option value="low">Estoque baixo</option>
            <option value="out">Sem estoque</option>
          </select>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>PRODUTO</th>
                <th>CÓDIGO</th>
                <th>CATEGORIA</th>
                <th>DESCRIÇÂO</th>
                <th>PREÇO</th>
                <th>QUANTIDADE</th>
                <th>STATUS</th>
                <th>AÇÕES</th>
                <th></th>
              </tr>
            </thead>
            <?php
    $query = "SELECT * FROM produtos";
    $stmt = mysqli_prepare($con, $query);

    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    ?>
<tbody id="recentProducts">

    <?php
    while ($registro = mysqli_fetch_assoc($resultado)) {

        $quantidade = (int) $registro["quantidade"];
        $estoque_minimo = (int) $registro["estoque_minimo"];

        if ($quantidade === 0) {
            $statusEstoque = "out";
            $textoStatus = "Sem estoque";

        } elseif ($quantidade <= $estoque_minimo) {
            $statusEstoque = "low";
            $textoStatus = "Estoque baixo";

        } else {
            $statusEstoque = "normal";
            $textoStatus = "Disponível";
        }
    ?>

               <tr
    data-id="<?php echo (int)$registro['id_produto']; ?>"
    data-stock="<?php echo $statusEstoque; ?>"
    data-min="<?php echo $estoque_minimo; ?>"
>
    <td><?php echo htmlspecialchars($registro["nome"], ENT_QUOTES, "UTF-8"); ?></td>

    <td><?php echo htmlspecialchars($registro["codigo"], ENT_QUOTES, "UTF-8"); ?></td>

    <td><?php echo htmlspecialchars($registro["categoria"], ENT_QUOTES, "UTF-8"); ?></td>

    <td><?php echo htmlspecialchars($registro["descricao"], ENT_QUOTES, "UTF-8"); ?></td>

    <td><?php echo $registro["preco"]; ?></td>

    <td><?php echo $quantidade; ?></td>

    <td><?php echo $textoStatus; ?></td>

    <td>
    <div class="acoes-produto">

        <button type="button" class="btn btn-outline btn-edit-product">
            Editar
        </button>

        <button type="button" class="btn btn-delete-product">
            Excluir
        </button>

    </div>
</td>

<td></td>
</tr>

    <?php
    }
    ?>

</tbody>
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

        <input type="hidden" name="id_produto" id="idProduto">

          <label>Nome do produto<input name="name" type="text" required></label>
          <div class="form-row">
            <label>Código<input name="code" type="text" required></label>
            <label>Categoria<input name="category" type="text" required></label>
          </div>
          <div class="form-row">
            <label>Preco<input name="price" type="number" min="0.01" step="0.01" required></label>
            <label>Quantidade<input name="quantity" type="number" min="0" step="1" required></label>
          </div>
          <div class="form-row">
            <label>Estoque minino<input name="min" type="number" min="0" step="1" required></label>
            <label>Descrição<input name="descripition" type="text" required></label>
          </div>
          <div class="modal-actions"><button class="btn btn-outline" type="button" data-close-modal>Cancelar</button><button class="btn btn-primary" type="submit" id="cadastrar">Salvar produto</button></div>
        </form>
        
      </section>
    </div>
  </main>

  <div id="popupMensagem" class="popup-mensagem" role="status" aria-live="polite"></div>
  <script src="js/products.js"></script>
</body>

</html>