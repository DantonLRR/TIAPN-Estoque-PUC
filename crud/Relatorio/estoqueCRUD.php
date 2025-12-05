<?php
class EstoqueCRUD {

    // Quantidade total por item (para gráfico)
    public function estoquePorItem($conn) {
        $sql = "SELECT 
                    nome_item,
                    SUM(quantidade) AS total
                FROM estoque
                GROUP BY nome_item
                ORDER BY nome_item ASC";

        $result = $conn->query($sql);

        $labels = [];
        $quantidades = [];

        while ($row = $result->fetch_assoc()) {
            $labels[] = $row['nome_item'];
            $quantidades[] = intval($row['total']);
        }

        return ['labels' => $labels, 'data' => $quantidades];
    }

    // Produtos com baixa quantidade (abaixo do mínimo)
    public function itensAbaixoDoMinimo($conn, $min = 5) {
        $sql = "SELECT id, nome_item, quantidade 
                FROM estoque
                WHERE quantidade <= $min
                ORDER BY quantidade ASC";

        $result = $conn->query($sql);

        $itens = [];
        while ($row = $result->fetch_assoc()) {
            $itens[] = $row;
        }

        return $itens;
    }
    // --- 3) Valor total do estoque ---
    public function valorTotalEstoque($conn) {
        $sql = "SELECT 
                    SUM(quantidade * preco_custo) AS total_custo,
                    SUM(quantidade * preco_venda) AS total_venda
                FROM estoque";

        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $total_custo = floatval($row['total_custo']);
        $total_venda = floatval($row['total_venda']);
        $lucro_potencial = $total_venda - $total_custo;

        return [
            'total_custo' => $total_custo,
            'total_venda' => $total_venda,
            'lucro_potencial' => $lucro_potencial
        ];
    }

    // --- 4) Valor por item (para gráfico) ---
    public function valorPorItem($conn) {
        $sql = "SELECT 
                    nome_item,
                    SUM(quantidade * preco_custo) AS total
                FROM estoque
                GROUP BY nome_item
                ORDER BY total DESC";

        $result = $conn->query($sql);

        $labels = [];
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $labels[] = $row['nome_item'];
            $data[] = floatval($row['total']);
        }

        return ['labels' => $labels, 'data' => $data];
    }

        // --- 5) Listar Log Completo do Estoque ---
    public function listarLogEstoque($conn) {
        $sql = "SELECT 
                    l.id_log,
                    e.nome_item,
                    l.quantidade_anterior,
                    l.quantidade_nova,
                    l.diferenca,
                    l.tipo_movimentacao,
                    l.usuario,
                    DATE_FORMAT(l.data_hora, '%d/%m/%Y %H:%i') AS data_hora
                FROM log_estoque l
                LEFT JOIN estoque e ON e.id = l.id_item
                ORDER BY l.data_hora DESC";

        $result = $conn->query($sql);

        $logs = [];
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }

        return $logs;
    }

    
}
