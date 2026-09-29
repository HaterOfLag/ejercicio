<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Metodo no permitido.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('Identificador no valido.');
}

$pdo = getConnection();
$statement = $pdo->prepare('DELETE FROM peliculas WHERE id = :id');
$statement->execute([':id' => $id]);

header('Location: index.php?eliminada=1');
exit;
