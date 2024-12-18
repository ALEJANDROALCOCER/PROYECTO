<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = ""; // Mensaje inicial vacío
$puntuacion = 0; // Inicializar puntuación en 0

// Respuestas correctas
$respuestas_correctas = array(
    "q1" => "x²/2",                   // Pregunta 1
    "q2" => "sin(x)",                 // Pregunta 2
    "q3" => "ln|x|",                  // Pregunta 3
    "q4" => "x³/3",                   // Pregunta 4
    "q5" => "e^x",                    // Pregunta 5
    "q6" => "cos(x)",                 // Pregunta 6
    "q7" => "x³/3 + C"                // Pregunta 7
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

    // Definir el ID del cuestionario
    $id_cuestionario = 4; // ID fijo del cuestionario

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
        $mensaje = "$nombre, tu puntuación es: $puntuacion de 7. ";
        if ($puntuacion == 7) {
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
echo "<head><meta charset='UTF-8'><title>Cuestionario de Cálculo Integral</title></head>";
echo "<body>";
echo "<h2>Cuestionario de Cálculo Integral</h2>";
if (!empty($mensaje)) echo "<p>$mensaje</p>";

echo "<form method='POST' action=''>";

// Nombre del usuario
echo "<label>Tu nombre:</label><br>";
echo "<input type='text' name='name' required><br><br>";

// Pregunta 1
echo "<label>1. ¿Cuál es la integral de f(x) = x?</label><br>";
echo "<input type='radio' name='q1' value='x²/2' required> a) x²/2<br>";
echo "<input type='radio' name='q1' value='x²' required> b) x²<br>";
echo "<input type='radio' name='q1' value='2x' required> c) 2x<br>";
echo "<input type='radio' name='q1' value='x³' required> d) x³<br><br>";

// Pregunta 2
echo "<label>2. ¿Cuál es la integral de f(x) = cos(x)?</label><br>";
echo "<input type='radio' name='q2' value='sin(x)' required> a) sin(x)<br>";
echo "<input type='radio' name='q2' value='cos(x)' required> b) cos(x)<br>";
echo "<input type='radio' name='q2' value='-sin(x)' required> c) -sin(x)<br>";
echo "<input type='radio' name='q2' value='tan(x)' required> d) tan(x)<br><br>";

// Pregunta 3
echo "<label>3. ¿Cuál es la integral de f(x) = 1/x?</label><br>";
echo "<input type='radio' name='q3' value='ln|x|' required> a) ln|x|<br>";
echo "<input type='radio' name='q3' value='e^x' required> b) e^x<br>";
echo "<input type='radio' name='q3' value='x²' required> c) x²<br>";
echo "<input type='radio' name='q3' value='x' required> d) x<br><br>";

// Pregunta 4
echo "<label>4. ¿Cuál es la integral de f(x) = x²?</label><br>";
echo "<input type='radio' name='q4' value='x³/3' required> a) x³/3<br>";
echo "<input type='radio' name='q4' value='x²' required> b) x²<br>";
echo "<input type='radio' name='q4' value='2x' required> c) 2x<br>";
echo "<input type='radio' name='q4' value='x³' required> d) x³<br><br>";

// Pregunta 5
echo "<label>5. ¿Cuál es la integral de f(x) = e^x?</label><br>";
echo "<input type='radio' name='q5' value='e^x' required> a) e^x<br>";
echo "<input type='radio' name='q5' value='e^2x' required> b) e^2x<br>";
echo "<input type='radio' name='q5' value='ln(x)' required> c) ln(x)<br>";
echo "<input type='radio' name='q5' value='x²' required> d) x²<br><br>";

// Pregunta 6
echo "<label>6. ¿Cuál es la integral de f(x) = cos(x)?</label><br>";
echo "<input type='radio' name='q6' value='sin(x)' required> a) sin(x)<br>";
echo "<input type='radio' name='q6' value='-sin(x)' required> b) -sin(x)<br>";
echo "<input type='radio' name='q6' value='cos(x)' required> c) cos(x)<br>";
echo "<input type='radio' name='q6' value='tan(x)' required> d) tan(x)<br><br>";

// Pregunta 7
echo "<label>7. ¿Cuál es la integral de f(x) = x³?</label><br>";
echo "<input type='radio' name='q7' value='x³/3 + C' required> a) x³/3 + C<br>";
echo "<input type='radio' name='q7' value='x² + C' required> b) x² + C<br>";
echo "<input type='radio' name='q7' value='x⁴/4 + C' required> c) x⁴/4 + C<br>";
echo "<input type='radio' name='q7' value='x²/2 + C' required> d) x²/2 + C<br><br>";

echo "<button type='submit'>Enviar</button>";
echo "</form>";
echo "</body></html>";
?>
