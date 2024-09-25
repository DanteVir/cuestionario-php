<?php
session_start();
include 'db.php';

$form_id = $_GET['id'];
$form = $db->query("SELECT * FROM forms WHERE id = $form_id")->fetch(PDO::FETCH_ASSOC);
$questions = $db->query("SELECT * FROM questions WHERE form_id = $form_id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados: <?= htmlspecialchars($form['title']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Resultados: <?= htmlspecialchars($form['title']) ?></h1>
    <?php foreach ($questions as $question): ?>
        <h3><?= htmlspecialchars($question['question']) ?></h3>
        <?php
        $options = $db->query("SELECT o.*, COUNT(r.id) as count 
                               FROM options o 
                               LEFT JOIN responses r ON o.id = r.option_id 
                               WHERE o.question_id = {$question['id']} 
                               GROUP BY o.id")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($options as $option):
        ?>
            <p>
                <?= htmlspecialchars($option['option_text']) ?>: 
                <?= $option['count'] ?> respuesta(s)
            </p>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <br>
    <a href="index.php">Volver al inicio</a>
</body>
</html>