<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $db->prepare("INSERT INTO forms (title) VALUES (?)")->execute([$title]);
    $form_id = $db->lastInsertId();

    foreach ($_POST['questions'] as $index => $question) {
        $stmt = $db->prepare("INSERT INTO questions (form_id, question) VALUES (?, ?)");
        $stmt->execute([$form_id, $question]);
        $question_id = $db->lastInsertId();

        foreach ($_POST['options'][$index] as $option) {
            $stmt = $db->prepare("INSERT INTO options (question_id, option_text) VALUES (?, ?)");
            $stmt->execute([$question_id, $option]);
        }
    }

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Formulario</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Crear Formulario</h1>
    <form method="POST">
        <label for="title">Título del formulario:</label>
        <input type="text" id="title" name="title" required><br><br>

        <div id="questions">
            <div class="question">
                <label for="question_1">Pregunta 1:</label>
                <input type="text" name="questions[]" required><br>
                <div class="options">
                    <input type="text" name="options[0][]" placeholder="Opción 1" required>
                    <input type="text" name="options[0][]" placeholder="Opción 2" required>
                </div>
                <button type="button" onclick="addOption(this)">Añadir opción</button>
            </div>
        </div>

        <button type="button" onclick="addQuestion()">Añadir pregunta</button>
        <input type="submit" value="Crear formulario">
    </form>

    <script>
        let questionCount = 1;

        function addQuestion() {
            questionCount++;
            const questionsDiv = document.getElementById('questions');
            const newQuestion = document.createElement('div');
            newQuestion.className = 'question';
            newQuestion.innerHTML = `
                <label for="question_${questionCount}">Pregunta ${questionCount}:</label>
                <input type="text" name="questions[]" required><br>
                <div class="options">
                    <input type="text" name="options[${questionCount-1}][]" placeholder="Opción 1" required>
                    <input type="text" name="options[${questionCount-1}][]" placeholder="Opción 2" required>
                </div>
                <button type="button" onclick="addOption(this)">Añadir opción</button>
            `;
            questionsDiv.appendChild(newQuestion);
        }

        function addOption(button) {
            const optionsDiv = button.previousElementSibling;
            const newOption = document.createElement('input');
            newOption.type = 'text';
            newOption.name = optionsDiv.lastElementChild.name;
            newOption.placeholder = `Opción ${optionsDiv.children.length + 1}`;
            newOption.required = true;
            optionsDiv.appendChild(newOption);
        }
    </script>
</body>
</html>