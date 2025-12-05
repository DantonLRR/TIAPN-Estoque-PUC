<?php
class ClientesCRUD {

    // === 1) Contagem de clientes por tipo (PF x PJ) ===
    public function contarPorTipo($conn) {
        $sql = "SELECT 
                    tipo_cliente,
                    COUNT(*) AS total
                FROM clientes
                GROUP BY tipo_cliente";

        $result = $conn->query($sql);
        $dados = ["fisica" => 0, "juridica" => 0];

        while ($row = $result->fetch_assoc()) {
            $dados[$row['tipo_cliente']] = (int)$row['total'];
        }

        return $dados;
    }

    // === 2) Contagem de clientes por estado ===
    public function contarPorEstado($conn) {
        $sql = "SELECT estado, COUNT(*) AS total
                FROM clientes
                GROUP BY estado";

        $result = $conn->query($sql);
        $dados = [];

        while ($row = $result->fetch_assoc()) {
            $dados[$row['estado']] = (int)$row['total'];
        }

        return $dados;
    }

    // === 3) Contagem por cidade ===
    public function contarPorCidade($conn) {
        $sql = "SELECT cidade, COUNT(*) AS total
                FROM clientes
                GROUP BY cidade";

        $result = $conn->query($sql);
        $dados = [];

        while ($row = $result->fetch_assoc()) {
            $dados[$row['cidade']] = (int)$row['total'];
        }

        return $dados;
    }

}
?>
