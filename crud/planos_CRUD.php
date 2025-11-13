<?php
// assets/crud/planos_CRUD.php

function buscarPlanoAtivo(PDO $pdo, int $id): ?array {
  $stmt = $pdo->prepare("
    SELECT id, name, description, monthly_price, yearly_price, is_active
    FROM plans
    WHERE id = ? AND is_active = 1
    LIMIT 1
  ");
  $stmt->execute([$id]);
  $plan = $stmt->fetch(PDO::FETCH_ASSOC);
  return $plan ?: null;
}

function listarPlanos(PDO $pdo, bool $somenteAtivos = true): array {
  $sql = "SELECT id, name, description, monthly_price, yearly_price, is_active FROM plans";
  if ($somenteAtivos) $sql .= " WHERE is_active = 1";
  $sql .= " ORDER BY monthly_price ASC, name ASC";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}


