<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = ""; // Mensaje inicial vacío
$puntuacion = 0; // Inicializar puntuación en 0

// Respuestas correctas
$respuestas_correctas = array(
    "q1" => "b",  // Pregunta 1
    "q2" => "b",  // Pregunta 2
    "q3" => "b",  // Pregunta 3
    "q4" => "b",  // Pregunta 4
    "q5" => "b",  // Pregunta 5
    "q6" => "a",  // Pregunta 6
    "q7" => "b",  // Pregunta 7
    "q8" => "b",  // Pregunta 8
    "q9" => "a",  // Pregunta 9
    "q10" => "a"   // Pregunta 10
);

// Procesar respuestas si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir el nombre del usuario, con valor por defecto "Anonimo"
    $nombre = isset($_POST['userName']) ? $_POST['userName'] : "Anonimo";

    // Comprobar respuestas correctas
    foreach ($respuestas_correctas as $key => $value) {
        if (isset($_POST[$key]) && $_POST[$key] === $value) {
            $puntuacion++;
        }
    }

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "puntajes");
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Definir el ID del cuestionario
    $id_cuestionario = 8; // ID actualizado del cuestionario

    // Preparar la consulta (sin el campo 'id', ya que es autoincremental)
    $stmt = $conexion->prepare("INSERT INTO resultados (nombre_usuario, puntuacion, id_cuestionario) VALUES (?, ?, ?)");

    if ($stmt === false) {
        // Si prepare falla, mostramos el error
        die("Error al preparar la consulta: " . $conexion->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("sii", $nombre, $puntuacion, $id_cuestionario);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Generar el mensaje dependiendo de la puntuación
        $mensaje = "$nombre, tu puntuación es: $puntuacion de 10. ";
        if ($puntuacion == 10) {
            $mensaje .= "¡Excelente!";
        } elseif ($puntuacion >= 7) {
            $mensaje .= "¡Bien hecho!";
        } else {
            $mensaje .= "Sigue practicando.";
        }
    } else {
        $mensaje = "Error al guardar los datos: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->close();
}

// Generar el cuestionario en PHP
echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Cuestionario de Química 3</title><style>body{font-family: Arial, sans-serif; margin: 20px; padding: 0; background-color: #f4f4f9;} .container{max-width: 700px; margin: 0 auto; background: #ffffff; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 5px;} h1{text-align: center; color: #333;} .question{margin: 15px 0;} .question label{display: block; margin-bottom: 5px;} .result{margin-top: 20px; text-align: center; font-size: 1.2em; color: #007b00;} .btn{display: block; width: 100%; padding: 10px; background: #007bff; color: #fff; text-align: center; border: none; border-radius: 5px; cursor: pointer; font-size: 1em;} .btn:hover { background: #0056b3; }</style></head>";
echo "<body>";
echo "<div class='container'>";
echo "<h1>Cuestionario de Química 3</h1>";
if (!empty($mensaje)) echo "<p class='result'>$mensaje</p>";

echo "<form method='POST' action=''>";

// Campo para el nombre del usuario
echo "<div class='question'><label for='userName'>Ingresa tu nombre:</label><input type='text' id='userName' name='userName' required></div>";

// Preguntas
echo "<div class='question'><label>1. ¿Qué es una reacción química?</label><input type='radio' name='q1' value='a'> Un proceso físico sin cambios en las sustancias<br><input type='radio' name='q1' value='b'> Un proceso donde sustancias se transforman en otras<br><input type='radio' name='q1' value='c'> Un proceso que solo involucra energía<br><input type='radio' name='q1' value='d'> Ninguna de las anteriores</div>";
echo "<div class='question'><label>2. Balancea la ecuación: H2 + O2 → H2O.</label><input type='radio' name='q2' value='a'> H2 + O2 → H2O<br><input type='radio' name='q2' value='b'> 2H2 + O2 → 2H2O<br><input type='radio' name='q2' value='c'> H2 + 2O2 → 2H2O<br><input type='radio' name='q2' value='d'> 2H2 + 2O2 → 2H2O</div>";
echo "<div class='question'><label>3. ¿Qué tipo de compuesto es el benceno?</label><input type='radio' name='q3' value='a'> Un hidrocarburo saturado<br><input type='radio' name='q3' value='b'> Un compuesto aromático<br><input type='radio' name='q3' value='c'> Un compuesto inorgánico<br><input type='radio' name='q3' value='d'> Un ácido orgánico</div>";
echo "<div class='question'><label>4. Describe la estructura del benceno según el modelo de Kekulé.</label><input type='radio' name='q4' value='a'> Un anillo hexagonal con enlaces simples entre carbono<br><input type='radio' name='q4' value='b'> Un anillo hexagonal con enlaces dobles alternados<br><input type='radio' name='q4' value='c'> Un anillo de carbono con enlaces triples alternados<br><input type='radio' name='q4' value='d'> Ninguna de las anteriores</div>";
echo "<div class='question'><label>5. Calcula los moles de CO2 producidos al quemar completamente 2 moles de benceno (C6H6).</label><input type='radio' name='q5' value='a'> 6 moles<br><input type='radio' name='q5' value='b'> 12 moles<br><input type='radio' name='q5' value='c'> 18 moles<br><input type='radio' name='q5' value='d'> 2 moles</div>";
echo "<div class='question'><label>6. ¿Qué es una reacción de sustitución en el contexto del benceno?</label><input type='radio' name='q6' value='a'> Reemplazo de un átomo de hidrógeno en el benceno<br><input type='radio' name='q6' value='b'> Reacción con oxígeno para formar CO2<br><input type='radio' name='q6' value='c'> Reacción con un ácido para formar un alcohol<br><input type='radio' name='q6' value='d'> Ninguna de las anteriores</div>";
echo "<div class='question'><label>7. ¿Cuál es el producto principal cuando el benceno reacciona con ácido nítrico en presencia de ácido sulfúrico?</label><input type='radio' name='q7' value='a'> Ácido benzoico<br><input type='radio' name='q7' value='b'> Nitrobenzeno<br><input type='radio' name='q7' value='c'> Ácido acetilsalicílico<br><input type='radio' name='q7' value='d'> Poliestireno</div>";
echo "<div class='question'><label>8. Balancea la ecuación de combustión del benceno: C6H6 + O2 → CO2 + H2O.</label><input type='radio' name='q8' value='a'> C6H6 + O2 → CO2 + H2O<br><input type='radio' name='q8' value='b'> 2C6H6 + 15O2 → 12CO2 + 6H2O<br><input type='radio' name='q8' value='c'> C6H6 + O2 → 6CO2 + 6H2O<br><input type='radio' name='q8' value='d'> 2C6H6 + 10O2 → 12CO2 + 6H2O</div>";
echo "<div class='question'><label>9. ¿Qué es un compuesto aromático?</label><input type='radio' name='q9' value='a'> Un compuesto con anillos de carbono conjugados<br><input type='radio' name='q9' value='b'> Un compuesto con grupos funcionales alternados<br><input type='radio' name='q9' value='c'> Un compuesto con enlaces simples<br><input type='radio' name='q9' value='d'> Ninguna de las anteriores</div>";
echo "<div class='question'><label>10. ¿Cuál es la principal propiedad de los hidrocarburos aromáticos?</label><input type='radio' name='q10' value='a'> Son solubles en agua<br><input type='radio' name='q10' value='b'> Son inflamables<br><input type='radio' name='q10' value='c'> Son sólidos a temperatura ambiente<br><input type='radio' name='q10' value='d'> Son insolubles en disolventes orgánicos</div>";

// Botón de enviar
echo "<div class='question'><button type='submit' class='btn'>Enviar Respuestas</button></div>";

echo "</form>";
echo "</div>";
echo "</body>";
echo "</html>";
?>
