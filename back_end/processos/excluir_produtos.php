<?php
header("Content-Type: application/json; charset=UTF-8");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Retornar resposta para o JavaScript
function responder($sucesso, $mensagem) {
    echo json_encode([
        "sucesso" => $sucesso,
        "mensagem" => $mensagem
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {

    // Conectar ao banco de dados
    require_once __DIR__ . "/../conexao/conexao.php";

    if (!isset($con) || !($con instanceof mysqli)) {
        throw new Exception("Conexão com o banco não encontrada.");
    }

    $con->set_charset("utf8mb4");

    // Verificar se a requisição é POST
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        responder(false, "Requisição inválida.");
    }

    // Receber o ID do produto
    $id = filter_var(
        $_POST["id_produto"] ?? "",
        FILTER_VALIDATE_INT
    );

    // Validar o ID
    if ($id === false || $id < 1) {
        responder(false, "ID do produto inválido.");
    }

    // Preparar a exclusão
    $query = "DELETE FROM produtos WHERE id_produto = ?";

    $stmt = mysqli_prepare($con, $query);

    // Associar o ID
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Executar a exclusão
    mysqli_stmt_execute($stmt);

    // Verificar se algum produto foi excluído
    if (mysqli_stmt_affected_rows($stmt) > 0) {

        mysqli_stmt_close($stmt);

        responder(true, "Produto excluído com sucesso!");

    } else {

        mysqli_stmt_close($stmt);

        responder(false, "Produto não encontrado.");
    }

} catch (Throwable $erro) {

    error_log("Erro ao excluir produto: " . $erro->getMessage());

    // Verificar se existem movimentações vinculadas
    if ((int)$erro->getCode() === 1451) {

        responder(
            false,
            "Não é possível excluir este produto porque existem movimentações vinculadas a ele."
        );
    }

    responder(false, "Erro ao excluir produto.");
}
?>