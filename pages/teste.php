<?php
function handleFormSubmission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the required fields are set
        $requiredFields = [
            'acao', 'tituloTransacao', 'descricaoTransacao', 'valorTransacao',
            'dataTransacao', 'tipoTransacao', 'statusTransacao', 'contaRemetente', 'categoriaTransacao'
        ];

        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                echo "Error: Missing required field '$field'.<br>";
                return;
            }
        }

        // Display the submitted data with their data types
        foreach ($_POST as $key => $value) {
            $dataType = gettype($value);
            echo htmlspecialchars($key) . ': ' . htmlspecialchars($value) . ' (' . $dataType . ')<br>';
        }
    } else {
        echo "No POST data received.";
    }
}

// Call the function to handle the form submission
handleFormSubmission();
?>