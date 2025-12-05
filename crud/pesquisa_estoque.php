<?php
class pesquisaEstoque
{
    function buscaEstoque($conn)
    {
        $lista = array();
        $sql = 'SELECT * FROM estoque ORDER BY nome_item ASC';
        $resultado = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($resultado)) {
            array_push($lista, $row);
        };
        return $lista;
    }

    function buscaOrcamentosPorID($conn, $ID)
    {
        $lista = array();
        $sql = "SELECT * FROM estoque WHERE id ='$ID'";
        $resultado = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($resultado)) {
            array_push($lista, $row);
        };
        return $lista;
    }

    function pesquisaDeOrcamento($conn, $cliente, $vendedor, $data_inicio, $data_fim)
    {
        $lista = array();
        $condicoes = array();
        $sql = "SELECT * FROM estoque";
        if (!empty($cliente)) {
            $condicoes[] = "cliente = '$cliente'";
        }

        if (!empty($vendedor)) {
            $condicoes[] = "vendedor = '$vendedor'";
        }

        if (!empty($data_inicio) && !empty($data_fim)) {
            $data_inicio .= " 00:00:00";
            $data_fim .= " 23:59:59";
            $condicoes[] = "dta_hora_orcamento BETWEEN '$data_inicio' AND '$data_fim'";
        }

        if (count($condicoes) > 0) {
            $sql .= " WHERE " . implode(" AND ", $condicoes);
        }
        $sql .=" ORDER BY dta_hora_orcamento ASC";
        // echo $sql;
        $resultado = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($resultado)) {
            array_push($lista, $row);
        }
        return $lista;
    }
}
