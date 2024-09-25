<?php
session_start();
include 'db.php';

$form_id = $_GET['id'];
$form = $db->query("SELECT * FROM forms WHERE id = $form_id")->fetch(PDO::FETCH_ASSOC);
$questions = $db->query("SELECT * FROM questions WHERE form_id = $form_id")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['responses'] as $question_id => $option_id) {
        $stmt = $db->prepare("INSERT INTO responses (form_id, question_id, option_id) VALUES (?, ?, ?)");
        $stmt->execute([$form_id, $question_id, $option_id]);
    }
    echo "Respuestas guardadas con éxito.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($form['title']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= htmlspecialchars($form['title']) ?></h1>
    <form method="POST">
        <?php foreach ($questions as $question): ?>
            <h3><?= htmlspecialchars($question['question']) ?></h3>
            <?php
            $options = $db->query("SELECT * FROM options WHERE question_id = {$question['id']}")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($options as $option):
            ?>
                <label>
                    <input type="radio" name="responses[<?= $question['id'] ?>]" value="<?= $option['id'] ?>" required>
                    <?= htmlspecialchars($option['option_text']) ?>
                </label><br>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <br>
        <input type="submit" value="Enviar respuestas">
    </form>
</body>
</html>
