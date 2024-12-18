<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = ""; // Mensaje inicial vacío
$puntuacion = 0; // Inicializar puntuación en 0

// Respuestas correctas
$respuestas_correctas = array(
    "q1" => "15",                   // Pregunta 1
    "q2" => "cuadrado",             // Pregunta 2
    "q3" => "1",                    // Pregunta 3
    "q4" => "1",                    // Pregunta 4
    "q5" => "90",                   // Pregunta 5
    "q6" => "360",                  // Pregunta 6
    "q7" => "5",                    // Pregunta 7
    "q8" => "2"                     // Pregunta 8
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

    // Definir el ID del cuestionario (ID 2)
    $id_cuestionario = 2;

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
        $mensaje = "$nombre, tu puntuación es: $puntuacion de 8. ";
        if ($puntuacion == 8) {
            $mensaje .= "¡Excelente!";
        } elseif ($puntuacion >= 5) {
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
echo "<head><meta charset='UTF-8'><title>Cuestionario PHP</title></head>";
echo "<body>";
echo "<h2>Cuestionario de Matemáticas</h2>";
if (!empty($mensaje)) echo "<p>$mensaje</p>";

echo "<form method='POST' action=''>";
echo "<label>Tu nombre:</label><br>";
echo "<input type='text' name='name' required><br><br>";

// Pregunta 1
echo "<label>1. ¿Cuál es el área de un triángulo con base 5 cm y altura 6 cm?</label><br>";
echo "<input type='radio' name='q1' value='15' required> 15 cm²<br>";
echo "<input type='radio' name='q1' value='30' required> 30 cm²<br><br>";

// Pregunta 2
echo "<label>2. ¿Qué figura tiene 4 lados de igual longitud y 4 ángulos rectos?</label><br>";
echo "<input type='radio' name='q2' value='cuadrado' required> Cuadrado<br>";
echo "<input type='radio' name='q2' value='rectángulo' required> Rectángulo<br><br>";

// Pregunta 3
echo "<label>3. ¿Qué valor tiene el seno de 90°?</label><br>";
echo "<input type='radio' name='q3' value='1' required> 1<br>";
echo "<input type='radio' name='q3' value='0' required> 0<br><br>";

// Pregunta 4
echo "<label>4. ¿Cuál es la tangente de 45°?</label><br>";
echo "<input type='radio' name='q4' value='1' required> 1<br>";
echo "<input type='radio' name='q4' value='0' required> 0<br><br>";

// Pregunta 5
echo "<label>5. ¿Cuántos grados tiene un ángulo recto?</label><br>";
echo "<input type='radio' name='q5' value='90' required> 90°<br>";
echo "<input type='radio' name='q5' value='60' required> 60°<br><br>";

// Pregunta 6
echo "<label>6. ¿Cuántos grados tiene la suma de los ángulos internos de un triángulo?</label><br>";
echo "<input type='radio' name='q6' value='360' required> 360°<br>";
echo "<input type='radio' name='q6' value='180' required> 180°<br><br>";

// Pregunta 7
echo "<label>7. ¿Cuál es el perímetro de un cuadrado con lado de 5 cm?</label><br>";
echo "<input type='radio' name='q7' value='5' required> 5 cm<br>";
echo "<input type='radio' name='q7' value='20' required> 20 cm<br><br>";

// Pregunta 8
echo "<label>8. ¿Qué figura tiene 3 lados?</label><br>";
echo "<input type='radio' name='q8' value='2' required> Triángulo<br>";
echo "<input type='radio' name='q8' value='4' required> Cuadrado<br><br>";

echo "<button type='submit'>Enviar</button>";
echo "</form>";
echo "</body></html>";
?>
