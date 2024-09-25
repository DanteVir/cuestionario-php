<?php
session_start();
include 'db.php';

$forms = $db->query("SELECT * FROM forms")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Formularios</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Sistema de Formularios</h1>
    <a href="create_form.php">Crear nuevo formulario</a>
    <h2>Formularios existentes:</h2>
    <ul>
        <?php foreach ($forms as $form): ?>
            <li>
                <a href="view_form.php?id=<?= $form['id'] ?>"><?= htmlspecialchars($form['title']) ?></a>
                - <a href="results.php?id=<?= $form['id'] ?>">Ver resultados</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>