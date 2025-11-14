<?php
session_start();
require('conexao_DB.php');
//essa pagina só será acessada via requisiçao POST
//verificando se a chave criar_orcamento existe no meu POST quando a pagina for chamada
// if (isset($_POST['criar_orcamento'])) {
//     $cliente = trim($_POST['cliente']);
//     $vendedor = trim($_POST['vendedor']);
//     $descricao = trim($_POST['descricao']);
//     $valor = trim($_POST['valor']);
//     // Verifica se é um número válido 
//     if (!is_numeric($valor)) {
//         // Retorna mensagem informando o erro
//         $_SESSION['mensagem'] = 'Valor orçado inválido. Digite apenas números.';
//         header('Location: ../pages/criar_orcamento.php');
//         exit;
//     }
//     $sql = "INSERT INTO orcamento_estoque(cliente, dta_hora_orcamento, vendedor, descricao, valor_orcado) 
//                                     VALUES('$cliente', SYSDATE(), '$vendedor', '$descricao', '$valor')";
//     //echo $sql;
//     // Faço o insert no banco 
//     mysqli_query($conn, $sql);
//     if (mysqli_affected_rows($conn) > 0) {
//         // redireciono para index e crio uma mensagem para ser exibida para o usuario 
//         $_SESSION['mensagem'] = 'Orçamento Criado com sucesso';
//         header('location:../pages/criar_orcamento.php');
//         exit;
//     } else {
//         $_SESSION['mensagem'] = 'Orçamento não pode ser criado Procure o administrador';
//         header('location:../pages/criar_orcamento.php');
//         exit;
//     }
// }

if (isset($_POST['criar_orcamento'])) {

    session_start();

    $cliente = trim($_POST['cliente']);
    $vendedor = trim($_POST['vendedor']);
    $descricao = trim($_POST['descricao']);
    $valor = trim($_POST['valor']);

    // novos campos
    $item = trim($_POST['item']); // ID do item
    $quantidade = trim($_POST['quantidade']);

    // Verifica se valor é numero
    if (!is_numeric($valor)) {
        $_SESSION['mensagem'] = 'Valor orçado inválido. Digite apenas números.';
        header('Location: ../pages/criar_orcamento.php');
        exit;
    }

    // Verifica se quantidade e item foram selecionados
    if (empty($item) || empty($quantidade)) {
        $_SESSION['mensagem'] = 'Selecione um item e uma quantidade válida.';
        header('Location: ../pages/criar_orcamento.php');
        exit;
    }

    // Buscar o nome do item pelo ID
    $sqlNome = "SELECT nome_item FROM estoque WHERE id = '$item' LIMIT 1";
    $resultNome = mysqli_query($conn, $sqlNome);
    $dados = mysqli_fetch_assoc($resultNome);
    $nome_item = $dados['nome_item'];


    $sql = "INSERT INTO orcamento_estoque 
            (cliente, dta_hora_orcamento, vendedor, descricao, valor_orcado, id_item, nome_item, quantidade)
            VALUES
            ('$cliente', SYSDATE(), '$vendedor', '$descricao', '$valor', '$item', '$nome_item', '$quantidade')";

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = 'Orçamento Criado com sucesso';
        header('location:../pages/criar_orcamento.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Orçamento não pode ser criado. Procure o administrador.';
        header('location:../pages/criar_orcamento.php');
        exit;
    }
}


if (isset($_POST['editar_orcamento'])) {
    $id = trim($_POST['id']);
    $cliente = trim($_POST['cliente']);
    $vendedor = trim($_POST['vendedor']);
    $descricao = trim($_POST['descricao']);
    $valor = trim($_POST['valor']);
    $valorDigitado = $_POST['valor'];
    $valor = str_replace(',', '.', $valorDigitado);

    // Verifica se é um número válido 
    if (!is_numeric($valor)) {
        // Retorna mensagem informando o erro
        $_SESSION['mensagem'] = 'Valor orçado inválido. Digite apenas números.';
        header('Location: ../index.php');
        exit;
    }

    $sql = "UPDATE orcamento_estoque 
    SET cliente = '$cliente', 
    dta_hora_orcamento= SYSDATE(),
     vendedor= '$vendedor',
      descricao = '$descricao', 
      valor_orcado = '$valor' 
      WHERE id='$id'";

    mysqli_query($conn, $sql);
    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = 'Orçamento editado com Sucesso';
        header('location:../index.php');
        exit;
    } else {
        $_SESSION['mensagem'] = "O orçamento NÃO foi editado";
        header('location:../index.php');
        exit;
    }
}

// if (isset($_POST['deletar_orcamento'])) {
//     $id = trim($_POST['deletar_orcamento']);
//     $sql = "DELETE FROM orcamento_estoque WHERE id='$id'";
//     mysqli_query($conn, $sql);
//     if (mysqli_affected_rows($conn) > 0) {
//         $_SESSION['mensagem'] = "Orçamento excluido com sucesso";
//           header('Location: ../pages/orcamentos.php');
//         exit;
//     } else {
//         $_SESSION['mensagem'] = "Orçamento não foi excuido";
//          header('Location: ../pages/orcamentos.php');
//         exit;
//     }
// }

if (isset($_POST['cancelar_orcamento'])) {

    session_start();

    $id = trim($_POST['cancelar_orcamento']);

    // Atualiza status para Cancelado
    $sql = "UPDATE orcamento_estoque 
            SET status = 'Cancelado' 
            WHERE id = '$id'";

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = "Orçamento cancelado com sucesso";
        header('Location: ../pages/orcamentos.php');
        exit;
    } else {
        $_SESSION['mensagem'] = "Erro ao cancelar o orçamento";
        header('Location: ../pages/orcamentos.php');
        exit;
    }
}


if (isset($_POST['aprovar_orcamento'])) {

    session_start();
    require('conexao_DB.php');

    $id_orcamento = $_POST['aprovar_orcamento'];

    // 1 — Buscar dados do orçamento
    $sqlBusca = "SELECT id_item, quantidade FROM orcamento_estoque WHERE id = '$id_orcamento' LIMIT 1";
    $resBusca = mysqli_query($conn, $sqlBusca);

    if (mysqli_num_rows($resBusca) == 0) {
        $_SESSION['mensagem'] = 'Orçamento não encontrado.';
        header('Location: ../pages/orcamentos.php');
        exit;
    }

    $orc = mysqli_fetch_assoc($resBusca);
    $id_item = $orc['id_item'];
    $quantidadeSolicitada = $orc['quantidade'];

    // 2 — Buscar quantidade atual no estoque
    $sqlEstoque = "SELECT quantidade FROM estoque WHERE id = '$id_item' LIMIT 1";
    $resEstoque = mysqli_query($conn, $sqlEstoque);

    if (mysqli_num_rows($resEstoque) == 0) {
        $_SESSION['mensagem'] = 'Item do estoque não encontrado!';
        header('Location: ../pages/orcamentos.php');
        exit;
    }

    $estoque = mysqli_fetch_assoc($resEstoque);
    $quantidadeAtual = $estoque['quantidade'];

    // 3 — Verificar se tem estoque suficiente
    if ($quantidadeAtual < $quantidadeSolicitada) {
        $_SESSION['mensagem'] = 'Estoque insuficiente para aprovar o orçamento!';
        header('Location: ../pages/orcamentos.php');
        exit;
    }

    // 4 — Subtrair do estoque
    $novaQuantidade = $quantidadeAtual - $quantidadeSolicitada;

    $sqlUpdateEstoque = "UPDATE estoque SET quantidade = '$novaQuantidade' WHERE id = '$id_item'";
    mysqli_query($conn, $sqlUpdateEstoque);

    // 5 — Atualizar status do orçamento para "Aprovado"
    $sqlAprovar = "UPDATE orcamento_estoque SET status = 'Aprovado' WHERE id = '$id_orcamento'";
    mysqli_query($conn, $sqlAprovar);

    $_SESSION['mensagem'] = 'Orçamento aprovado e estoque atualizado!';
    header('Location: ../pages/orcamentos.php');
    exit;
}

