<?php
// assets/crud/pagamento_CRUD.php

function calcularValorPlano(array $plan, string $billing_cycle): float {
  $billing = ($billing_cycle === 'yearly') ? 'yearly_price' : 'monthly_price';
  return (float)($plan[$billing] ?? 0);
}

function criarPagamento(PDO $pdo, array $dados): int {
  $sql = "INSERT INTO payments
            (plan_id, plan_name, billing_cycle, amount, method, payer_name, payer_email, status, external_ref)
          VALUES (?,?,?,?,?,?,?,?,?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $dados['plan_id'],
    $dados['plan_name'],
    $dados['billing_cycle'],   // 'monthly' | 'yearly'
    $dados['amount'],          // decimal
    $dados['method'],          // 'pix' | 'boleto' | 'card'
    $dados['payer_name'],
    $dados['payer_email'],
    $dados['status'],          // 'pending' | 'approved' | 'rejected' | 'cancelled'
    $dados['external_ref'],
  ]);
  return (int)$pdo->lastInsertId();
}

function buscarPagamento(PDO $pdo, int $id): ?array {
  $stmt = $pdo->prepare("SELECT * FROM payments WHERE id = ? LIMIT 1");
  $stmt->execute([$id]);
  $p = $stmt->fetch(PDO::FETCH_ASSOC);
  return $p ?: null;
}
