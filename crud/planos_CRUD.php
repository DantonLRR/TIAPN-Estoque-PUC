<?php
// crud/planos_CRUD.php

if (!function_exists('listarPlanos')) {

    function listarPlanos(mysqli $conn, bool $apenasAtivos = false): array
    {
        $sql = "SELECT id, name, description, monthly_price, yearly_price, is_active
                FROM plans";
        if ($apenasAtivos) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY monthly_price ASC";

        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            return [];
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $planos = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $planos[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $planos;
    }

    function buscarPlanoAtivo(mysqli $conn, int $id): ?array
    {
        $sql = "SELECT id, name, description, monthly_price, yearly_price, is_active
                FROM plans
                WHERE id = ? AND is_active = 1";

        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            return null;
        }

        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $plano = mysqli_fetch_assoc($result) ?: null;

        mysqli_stmt_close($stmt);
        return $plano;
    }
}
