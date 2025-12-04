<?php
session_start();
require('conexao_DB.php');

// ============================
// CRIAR ITEM NO ESTOQUE
// ============================
if (isset($_POST['criar_item'])) {
    $nome_item = trim($_POST['nome_item']);
    $descricao = trim($_POST['descricao']);
    $quantidade = trim($_POST['quantidade']);
    $preco_custo = trim($_POST['preco_custo']);
    $preco_venda = trim($_POST['preco_venda']);

    // Verifica se os valores são numéricos
    if (!is_numeric($quantidade) || !is_numeric($preco_custo) || !is_numeric($preco_venda)) {
        $_SESSION['mensagem'] = 'Erro: Quantidade e preços devem ser números válidos.';
        header('Location: ../pages/criar_item_estoque.php');
        exit;
    }

    $sql = "INSERT INTO estoque (nome_item, descricao, quantidade, preco_custo, preco_venda, data_criacao, data_atualizacao)
            VALUES ('$nome_item', '$descricao', '$quantidade', '$preco_custo', '$preco_venda', SYSDATE(), SYSDATE())";

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = 'Item adicionado ao estoque com sucesso!';
        header('Location: ../pages/criar_item_estoque.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Erro ao criar item. Tente novamente.';
        header('Location: ../pages/criar_item_estoque.php');
        exit;
    }
}

// ============================
// EDITAR ITEM NO ESTOQUE
// ============================
if (isset($_POST['editar_item'])) {
    session_start();

    $id         = trim($_POST['id']);
    $nome       = trim($_POST['nome_item']);
    $descricao  = trim($_POST['descricao']);
    $quantidade = trim($_POST['quantidade']);
    $custo      = trim($_POST['preco_custo']);
    $venda      = trim($_POST['preco_venda']);

    // Usuário logado responsável pela alteração
    $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'desconhecido';

    // 1️⃣ Buscar quantidade antiga
    $sql_old = "SELECT quantidade FROM estoque WHERE id = '$id'";
    $res_old = mysqli_query($conn, $sql_old);
    $dados_old = mysqli_fetch_assoc($res_old);
    $quant_old = $dados_old['quantidade'];

    // 2️⃣ Atualizar o item no estoque
    $sql_update = "UPDATE estoque SET 
                        nome_item='$nome',
                        descricao='$descricao',
                        quantidade='$quantidade',
                        preco_custo='$custo',
                        preco_venda='$venda'
                    WHERE id='$id'";

    mysqli_query($conn, $sql_update);

    // 3️⃣ Verificar se realmente alterou algo
    if (mysqli_affected_rows($conn) > 0) {

        // 4️⃣ Calcular diferença (+ ou -)
        // Calcula a diferença
        $diferenca_num = $quantidade - $quant_old;

        // Converte para string com sinal
        if ($diferenca_num > 0) {
            $diferenca = "+" . $diferenca_num;
        } else {
            $diferenca = (string)$diferenca_num; // negativo já vem com sinal
        }

        // Ex: 50 - 20 = +30 | 10 - 40 = -30

        // 5️⃣ Identificar tipo
        if ($diferenca > 0) {
            $tipo = "Entrada";
        } elseif ($diferenca < 0) {
            $tipo = "Saída";
        } else {
            $tipo = "Edição";
        }

        // 6️⃣ Inserir no log
        $sql_log = "INSERT INTO log_estoque 
                        (id_item, quantidade_anterior, quantidade_nova, diferenca, tipo_movimentacao, usuario)
                    VALUES 
                        ('$id', '$quant_old', '$quantidade', '$diferenca', '$tipo', '$usuario')";

        mysqli_query($conn, $sql_log);

        $_SESSION['mensagem'] = "Item atualizado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Nenhuma alteração realizada.";
    }

    // 7️⃣ Redirecionar
    header("Location: ../pages/gerenciar_estoque.php");
    exit;
}




// ============================
// DELETAR ITEM DO ESTOQUE
// ============================
if (isset($_POST['deletar_item'])) {
    $id = trim($_POST['deletar_item']);

    $sql = "DELETE FROM estoque WHERE id = '$id'";
    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = 'Item excluído com sucesso!';
        header('Location: ../pages/gerenciar_estoque.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Erro ao excluir item.';
        header('Location: ../pages/gerenciar_estoque.php');
        exit;
    }
}

// ============================
// ADICIONAR QUANTIDADE
// ============================
if (isset($_POST['adicionar_qtd'])) {
    $id = trim($_POST['id']);
    $quantidade_add = trim($_POST['quantidade_add']);

    if (!is_numeric($quantidade_add) || $quantidade_add <= 0) {
        $_SESSION['mensagem'] = 'Quantidade inválida.';
        header('Location: ../pages/gerenciar_estoque.php');
        exit;
    }

    $sql = "UPDATE estoque SET 
                quantidade = quantidade + $quantidade_add, 
                data_atualizacao = SYSDATE() 
            WHERE id = '$id'";

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = 'Quantidade adicionada com sucesso!';
        header('Location: ../pages/gerenciar_estoque.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Erro ao adicionar quantidade.';
        header('Location: ../pages/gerenciar_estoque.php');
        exit;
    }
}

// ============================
// REMOVER QUANTIDADE
// ============================
if (isset($_POST['remover_qtd'])) {
    $id = trim($_POST['id']);
    $quantidade_remover = trim($_POST['quantidade_remover']);

    if (!is_numeric($quantidade_remover) || $quantidade_remover <= 0) {
        $_SESSION['mensagem'] = 'Quantidade inválida.';
        header('Location: ../pages/gerenciar_estoque.php');
        exit;
    }

    // Evita número negativo
    $sqlCheck = "SELECT quantidade FROM estoque WHERE id = '$id'";
    $result = mysqli_query($conn, $sqlCheck);
    $row = mysqli_fetch_assoc($result);

    if ($row['quantidade'] < $quantidade_remover) {
        $_SESSION['mensagem'] = 'Erro: Quantidade a remover é maior que a disponível.';
        header('Location: ../pages/gerenciar_estoque.php');
        exit;
    }

    $sql = "UPDATE estoque SET 
                quantidade = quantidade - $quantidade_remover, 
                data_atualizacao = SYSDATE() 
            WHERE id = '$id'";

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = 'Quantidade removida com sucesso!';
        header('Location: ../pages/gerenciar_estoque.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Erro ao remover quantidade.';
        header('Location: ../pages/gerenciar_estoque.php');
        exit;
    }
}
