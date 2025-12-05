<?php
class VendasCRUD {

    public function vendasMensais($conn) {
        $sql = "SELECT 
                    MONTH(dta_hora_orcamento) AS mes,
                    SUM(valor_orcado) AS total_venda,
                    SUM(quantidade * (SELECT preco_custo FROM estoque e WHERE e.id = o.id_item)) AS total_custo
                FROM orcamento_estoque o
                WHERE status = 'Aprovado'
                GROUP BY MONTH(dta_hora_orcamento)
                ORDER BY mes";
        $result = $conn->query($sql);

        $meses = array_fill(1, 12, 0);
        $custo = array_fill(1, 12, 0);

        while ($row = $result->fetch_assoc()) {
            $meses[$row['mes']] = floatval($row['total_venda']);
            $custo[$row['mes']] = floatval($row['total_custo']);
        }

        return ['vendas' => $meses, 'custos' => $custo];
    }

    public function produtosMaisVendidos($conn) {
        $sql = "SELECT 
                    nome_item,
                    SUM(quantidade) AS total
                FROM orcamento_estoque
                WHERE status = 'Aprovado'
                GROUP BY id_item
                ORDER BY total DESC
                LIMIT 10";
        $result = $conn->query($sql);

        $labels = [];
        $quantidades = [];

        while ($row = $result->fetch_assoc()) {
            $labels[] = $row['nome_item'];
            $quantidades[] = intval($row['total']);
        }

        return ['labels' => $labels, 'data' => $quantidades];
    }
}
