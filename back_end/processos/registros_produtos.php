<?php
header("Content-Type: application/json; charset=UTF-8");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function responder($sucesso, $mensagem) {
    echo json_encode([
        "sucesso" => $sucesso,
        "mensagem" => $mensagem
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {

    require_once __DIR__ . "/../conexao/conexao.php";

    if (!isset($con) || !($con instanceof mysqli)) {
        throw new Exception("Conexão com o banco não encontrada.");
    }

    $con->set_charset("utf8mb4");

    // Receber os dados
    $idTexto = trim($_POST["id_produto"] ?? "");

    $nome = trim($_POST["name"] ?? "");
    $codigo = trim($_POST["code"] ?? "");
    $categoria = trim($_POST["category"] ?? "");
    $descricao = trim($_POST["descripition"] ?? "");

    $preco = trim($_POST["price"] ?? "");
    $quantidade = filter_var($_POST["quantity"] ?? "", FILTER_VALIDATE_INT);
    $estoque_minimo = filter_var($_POST["min"] ?? "", FILTER_VALIDATE_INT);

    // Validar os campos
    if (
        $nome === "" ||
        $codigo === "" ||
        $categoria === "" ||
        $descricao === "" ||
        !is_numeric($preco) ||
        (float)$preco <= 0 ||
        $quantidade === false ||
        $estoque_minimo === false ||
        $quantidade < 0 ||
        $estoque_minimo < 0
    ) {
        responder(false, "Preencha todos os campos corretamente.");
    }

    $preco = (float)$preco;

    // Este arquivo processa somente novos cadastros
    if ($idTexto !== "") {
        responder(false, "Use o arquivo de edição para atualizar produtos.");
    }

    $query = "INSERT INTO produtos
        (codigo, categoria, nome, descricao, preco, quantidade, estoque_minimo)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssdii",
        $codigo,
        $categoria,
        $nome,
        $descricao,
        $preco,
        $quantidade,
        $estoque_minimo
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    responder(true, "Produto cadastrado com sucesso!");

} catch (Throwable $erro) {

    error_log("Erro ao salvar produto: " . $erro->getMessage());

    responder(false, "Erro ao salvar produto. Verifique os dados e tente novamente.");
}
?>