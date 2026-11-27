
<?php
ob_start();

ini_set("display_errors", "0");

header("Content-Type: application/json; charset=UTF-8");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function responder($sucesso, $mensagem) {

    if (ob_get_length() !== false) {
        ob_clean();
    }

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

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        responder(false, "Requisição inválida.");
    }

    $id = filter_var(
        $_POST["id_produto"] ?? "",
        FILTER_VALIDATE_INT
    );

    if ($id === false || $id < 1) {
        responder(false, "ID do produto inválido.");
    }

    // Verificar se o produto existe
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
        responder(false, "Produto não encontrado.");
    }

    // Excluir produto
    $query = "DELETE FROM produtos WHERE id_produto = ?";

    $stmt = mysqli_prepare($con, $query);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    responder(true, "Produto excluído com sucesso!");

} catch (Throwable $erro) {

    error_log("Erro ao excluir produto: " . $erro->getMessage());

    responder(false, "Erro ao excluir produto.");
}
?>
