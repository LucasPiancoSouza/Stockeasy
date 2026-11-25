<?php
header("Content-Type: application/json; charset=UTF-8");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function responderEdicao($sucesso, $mensagem) {
    echo json_encode([
        "sucesso" => $sucesso,
        "mensagem" => $mensagem
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {

    require_once __DIR__ . "/../conexao/conexao.php";

    if (!isset($con) || !($con instanceof mysqli)) {
        throw new Exception("Conexao com o banco nao encontrada.");
    }

    $con->set_charset("utf8mb4");

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        responderEdicao(false, "Requisicao invalida.");
    }

    $id = filter_var($_POST["id_produto"] ?? "", FILTER_VALIDATE_INT);

    $nome = trim($_POST["name"] ?? "");
    $codigo = trim($_POST["code"] ?? "");
    $categoria = trim($_POST["category"] ?? "");
    $descricao = trim($_POST["descripition"] ?? "");

    $preco = trim($_POST["price"] ?? "");
    $quantidade = filter_var($_POST["quantity"] ?? "", FILTER_VALIDATE_INT);
    $estoque_minimo = filter_var($_POST["min"] ?? "", FILTER_VALIDATE_INT);

    if (
        $id === false ||
        $id < 1 ||
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
        responderEdicao(false, "Preencha todos os campos corretamente.");
    }

    $preco = (float)$preco;

    $verificar = mysqli_prepare(
        $con,
        "SELECT id_produto FROM produtos WHERE id_produto = ?"
    );

    mysqli_stmt_bind_param($verificar, "i", $id);
    mysqli_stmt_execute($verificar);

    $resultado = mysqli_stmt_get_result($verificar);
    $existe = mysqli_num_rows($resultado) > 0;

    mysqli_stmt_close($verificar);

    if (!$existe) {
        responderEdicao(false, "Produto nao encontrado.");
    }

    $query = "UPDATE produtos SET
        codigo = ?,
        categoria = ?,
        nome = ?,
        descricao = ?,
        preco = ?,
        quantidade = ?,
        estoque_minimo = ?
        WHERE id_produto = ?";

    $stmt = mysqli_prepare($con, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssdiii",
        $codigo,
        $categoria,
        $nome,
        $descricao,
        $preco,
        $quantidade,
        $estoque_minimo,
        $id
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    responderEdicao(true, "Produto atualizado com sucesso!");

} catch (Throwable $erro) {

    error_log("Erro ao editar produto: " . $erro->getMessage());

    responderEdicao(
        false,
        "Erro ao editar produto. Verifique os dados e tente novamente."
    );
}
?>
