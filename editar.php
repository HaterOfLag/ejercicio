<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('Identificador no valido.');
}

$pdo = getConnection();
$statement = $pdo->prepare('SELECT id, titulo, director, genero, anio, duracion FROM peliculas WHERE id = :id');
$statement->execute([':id' => $id]);
$pelicula = $statement->fetch();

if (!$pelicula) {
    http_response_code(404);
    exit('Pelicula no encontrada.');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['titulo', 'director', 'genero', 'anio', 'duracion'] as $field) {
        $pelicula[$field] = trim((string) ($_POST[$field] ?? ''));
        if ($pelicula[$field] === '') {
            $errors[] = 'El campo ' . $field . ' es obligatorio.';
        }
    }

    if ($pelicula['anio'] !== '' && filter_var($pelicula['anio'], FILTER_VALIDATE_INT) === false) {
        $errors[] = 'El anio debe ser un numero entero.';
    }
    if ($pelicula['duracion'] !== '' && filter_var($pelicula['duracion'], FILTER_VALIDATE_INT) === false) {
        $errors[] = 'La duracion debe ser un numero entero.';
    }

    if (!$errors) {
        $statement = $pdo->prepare(
            'UPDATE peliculas
             SET titulo = :titulo, director = :director, genero = :genero,
                 anio = :anio, duracion = :duracion
             WHERE id = :id'
        );
        $statement->execute([
            ':titulo' => $pelicula['titulo'],
            ':director' => $pelicula['director'],
            ':genero' => $pelicula['genero'],
            ':anio' => (int) $pelicula['anio'],
            ':duracion' => (int) $pelicula['duracion'],
            ':id' => $id,
        ]);
        header('Location: index.php?actualizada=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar pelicula</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 650px; margin: 30px auto; padding: 0 16px; }
        form { display: grid; gap: 14px; }
        label { display: flex; flex-direction: column; gap: 5px; font-weight: bold; }
        input, button, a { padding: 8px; font-size: 1rem; }
        .error { color: #a00; }
    </style>
</head>
<body>
    <h1>Editar pelicula</h1>
    <?php if ($errors): ?><div class="error"><ul><?php foreach ($errors as $error): ?><li><?= escape($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="post" action="editar.php?id=<?= (int) $id ?>">
        <label>Titulo <input type="text" name="titulo" maxlength="150" value="<?= escape((string) $pelicula['titulo']) ?>" required></label>
        <label>Director <input type="text" name="director" maxlength="100" value="<?= escape((string) $pelicula['director']) ?>" required></label>
        <label>Genero <input type="text" name="genero" maxlength="50" value="<?= escape((string) $pelicula['genero']) ?>" required></label>
        <label>Anio <input type="number" name="anio" value="<?= escape((string) $pelicula['anio']) ?>" required></label>
        <label>Duracion (minutos) <input type="number" name="duracion" min="1" value="<?= escape((string) $pelicula['duracion']) ?>" required></label>
        <button type="submit">Guardar cambios</button>
    </form>
    <p><a href="index.php">Volver al catalogo</a></p>
</body>
</html>
