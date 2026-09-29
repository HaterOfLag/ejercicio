<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$errors = [];
$values = [
    'titulo' => '',
    'director' => '',
    'genero' => '',
    'anio' => '',
    'duracion' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $field => $value) {
        $values[$field] = trim((string) ($_POST[$field] ?? ''));
        if ($values[$field] === '') {
            $errors[] = 'El campo ' . $field . ' es obligatorio.';
        }
    }

    if ($values['anio'] !== '' && filter_var($values['anio'], FILTER_VALIDATE_INT) === false) {
        $errors[] = 'El anio debe ser un numero entero.';
    }
    if ($values['duracion'] !== '' && filter_var($values['duracion'], FILTER_VALIDATE_INT) === false) {
        $errors[] = 'La duracion debe ser un numero entero.';
    }

    if (!$errors) {
        $pdo = getConnection();
        $statement = $pdo->prepare(
            'INSERT INTO peliculas (titulo, director, genero, anio, duracion)
             VALUES (:titulo, :director, :genero, :anio, :duracion)'
        );
        $statement->execute([
            ':titulo' => $values['titulo'],
            ':director' => $values['director'],
            ':genero' => $values['genero'],
            ':anio' => (int) $values['anio'],
            ':duracion' => (int) $values['duracion'],
        ]);
        header('Location: index.php?creada=1');
        exit;
    }
}

$pdo = getConnection();
$statement = $pdo->prepare('SELECT id, titulo, director, genero, anio, duracion FROM peliculas ORDER BY id DESC');
$statement->execute();
$peliculas = $statement->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo de cine</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1100px; margin: 30px auto; padding: 0 16px; color: #222; }
        h1 { margin-bottom: 8px; }
        form { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; align-items: end; margin: 24px 0; }
        label { display: flex; flex-direction: column; gap: 5px; font-weight: bold; }
        input, button, a { padding: 8px; font-size: 1rem; }
        button { cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #eee; }
        .acciones { white-space: nowrap; }
        .error { color: #a00; }
        .ok { color: #176b2c; }
    </style>
</head>
<body>
    <h1>Catalogo de peliculas</h1>
    <p>Gestion de peliculas almacenadas en la base de datos <strong>cine</strong>.</p>

    <?php if ($errors): ?>
        <div class="error"><strong>Corrige los siguientes errores:</strong><ul>
            <?php foreach ($errors as $error): ?><li><?= escape($error) ?></li><?php endforeach; ?>
        </ul></div>
    <?php endif; ?>
    <?php if (isset($_GET['creada'])): ?><p class="ok">Pelicula creada correctamente.</p><?php endif; ?>
    <?php if (isset($_GET['eliminada'])): ?><p class="ok">Pelicula eliminada correctamente.</p><?php endif; ?>
    <?php if (isset($_GET['actualizada'])): ?><p class="ok">Pelicula actualizada correctamente.</p><?php endif; ?>

    <h2>Añadir pelicula</h2>
    <form method="post" action="index.php">
        <label>Titulo <input type="text" name="titulo" maxlength="150" value="<?= escape($values['titulo']) ?>" required></label>
        <label>Director <input type="text" name="director" maxlength="100" value="<?= escape($values['director']) ?>" required></label>
        <label>Genero <input type="text" name="genero" maxlength="50" value="<?= escape($values['genero']) ?>" required></label>
        <label>Anio <input type="number" name="anio" value="<?= escape($values['anio']) ?>" required></label>
        <label>Duracion (minutos) <input type="number" name="duracion" min="1" value="<?= escape($values['duracion']) ?>" required></label>
        <button type="submit">Guardar pelicula</button>
    </form>

    <h2>Peliculas</h2>
    <?php if (!$peliculas): ?>
        <p>No hay peliculas registradas.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>ID</th><th>Titulo</th><th>Director</th><th>Genero</th><th>Anio</th><th>Duracion</th><th>Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($peliculas as $pelicula): ?>
                <tr>
                    <td><?= (int) $pelicula['id'] ?></td>
                    <td><?= escape($pelicula['titulo']) ?></td>
                    <td><?= escape($pelicula['director']) ?></td>
                    <td><?= escape($pelicula['genero']) ?></td>
                    <td><?= (int) $pelicula['anio'] ?></td>
                    <td><?= (int) $pelicula['duracion'] ?> min</td>
                    <td class="acciones">
                        <a href="editar.php?id=<?= (int) $pelicula['id'] ?>">Editar</a>
                        <form method="post" action="eliminar.php" style="display:inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta pelicula?');">
                            <input type="hidden" name="id" value="<?= (int) $pelicula['id'] ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="api/peliculas.php">Consultar API JSON</a></p>
</body>
</html>
