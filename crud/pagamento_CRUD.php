<?php
// crud/pagamento_CRUD.php

if (!function_exists('calcularValorPlano')) {

    function calcularValorPlano(array $plan, string $billing): float
    {
        $monthly    = (float)($plan['monthly_price'] ?? 0);
        $yearlyDb   = (float)($plan['yearly_price'] ?? 0);

        if ($billing === 'yearly') {
            // se tiver yearly_price cadastrado, usa ele
            if ($yearlyDb > 0) {
                return round($yearlyDb, 2);
            }
            // senão, calcula 12x mensal com 10% desconto (pode ajustar)
            $year = $monthly * 12;
            return round($year * 0.90, 2);
        }

        // padrão: mensal
        return round($monthly, 2);
    }

    function criarPagamento(mysqli $conn, array $dados): int
    {
        $sql = "INSERT INTO payments
                (plan_id, plan_name, billing_cycle, amount, method,
                 payer_name, payer_email, external_ref, status)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            return 0;
        }

        $plan_id      = (int)$dados['plan_id'];
        $plan_name    = $dados['plan_name'];
        $billing      = $dados['billing_cycle'];
        $amount       = (float)$dados['amount'];
        $method       = $dados['method'];
        $payer_name   = $dados['payer_name'];
        $payer_email  = $dados['payer_email'];
        $external_ref = $dados['external_ref'];
        $status       = $dados['status'];

        mysqli_stmt_bind_param(
            $stmt,
            "issdsssss",
            $plan_id,
            $plan_name,
            $billing,
            $amount,
            $method,
            $payer_name,
            $payer_email,
            $external_ref,
            $status
        );

        mysqli_stmt_execute($stmt);
        $id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        return (int)$id;
    }
}

