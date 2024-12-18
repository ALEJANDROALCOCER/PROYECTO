<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = ""; // Mensaje inicial vacío
$puntuacion = 0; // Inicializar puntuación en 0

// Respuestas correctas
$respuestas_correctas = array(
    "q1" => "2x",                   // Pregunta 1
    "q2" => "5x^4",                 // Pregunta 2
    "q3" => "e^x",                  // Pregunta 3
    "q4" => "3x^2 + 2x"             // Pregunta 4
);

// Procesar respuestas si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir el nombre del usuario, con valor por defecto "Anonimo"
    $nombre = isset($_POST['name']) ? $_POST['name'] : "Anonimo";

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

    // Definir el ID del cuestionario (ID = 3)
    $id_cuestionario = 3;

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
        $mensaje = "$nombre, tu puntuación es: $puntuacion de 4. ";
        if ($puntuacion == 4) {
            $mensaje .= "¡Excelente!";
        } elseif ($puntuacion >= 2) {
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
echo "<head><meta charset='UTF-8'><title>Cuestionario de Cálculo Diferencial</title></head>";
echo "<body>";
echo "<h2>Cuestionario de Cálculo Diferencial</h2>";
if (!empty($mensaje)) echo "<p>$mensaje</p>";

echo "<form method='POST' action=''>";
echo "<label>Tu nombre:</label><br>";
echo "<input type='text' name='name' required><br><br>";

// Pregunta 1
echo "<label>1. ¿Cuál es la derivada de f(x) = x² + 5x?</label><br>";
echo "<input type='radio' name='q1' value='2x' required> 2x<br>";
echo "<input type='radio' name='q1' value='x + 5' required> x + 5<br><br>";

// Pregunta 2
echo "<label>2. ¿Cuál es la derivada de f(x) = x^5?</label><br>";
echo "<input type='radio' name='q2' value='5x^4' required> 5x^4<br>";
echo "<input type='radio' name='q2' value='x^4' required> x^4<br><br>";

// Pregunta 3
echo "<label>3. ¿Cuál es la derivada de f(x) = e^x?</label><br>";
echo "<input type='radio' name='q3' value='e^x' required> e^x<br>";
echo "<input type='radio' name='q3' value='e' required> e<br><br>";

// Pregunta 4
echo "<label>4. ¿Cuál es la derivada de f(x) = x^3 + x^2?</label><br>";
echo "<input type='radio' name='q4' value='3x^2 + 2x' required> 3x^2 + 2x<br>";
echo "<input type='radio' name='q4' value='x^2 + 2x' required> x^2 + 2x<br><br>";

echo "<button type='submit'>Enviar</button>";
echo "</form>";
echo "</body></html>";
?>
