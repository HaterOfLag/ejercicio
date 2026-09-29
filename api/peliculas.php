<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    echo json_encode(['error' => 'Metodo no permitido. Solo se admite GET'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo = getConnection();
    $statement = $pdo->prepare(
        'SELECT id, titulo, director, genero, anio, duracion
         FROM peliculas
         ORDER BY id'
    );
    $statement->execute();
    $peliculas = $statement->fetchAll();

    $peliculas = array_map(static function (array $pelicula): array {
        return [
            'id' => (int) $pelicula['id'],
            'titulo' => $pelicula['titulo'],
            'director' => $pelicula['director'],
            'genero' => $pelicula['genero'],
            'anio' => (int) $pelicula['anio'],
            'duracion' => (int) $pelicula['duracion'],
        ];
    }, $peliculas);

    echo json_encode($peliculas, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo consultar el catalogo.'], JSON_UNESCAPED_UNICODE);
}
