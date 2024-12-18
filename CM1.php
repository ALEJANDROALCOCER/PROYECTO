<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = ""; // Mensaje inicial vacío
$puntuacion = 0; // Inicializar puntuación en 0

// Respuestas correctas
$respuestas_correctas = array(
    "q1" => "3",                   // Pregunta 1
    "q2" => "(x + 2)(x + 3)",      // Pregunta 2
    "q3" => "x = 2, x = 3",        // Pregunta 3
    "q4" => "22/15"                // Pregunta 4
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

    // Definir el ID del cuestionario (puedes cambiarlo según tus necesidades)
    $id_cuestionario = 1; // ID fijo del cuestionario

    // Preparar la consulta (sin el campo 'id', ya que es autoincremental)
    $stmt = $conexion->prepare("INSERT INTO resultados (nombre_usuario,puntuacion,id_cuestionario) VALUES (?, ?, ?)");
    
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
echo "<head><meta charset='UTF-8'><title>Cuestionario PHP</title></head>";
echo "<body>";
echo "<h2>Cuestionario de Matemáticas</h2>";
if (!empty($mensaje)) echo "<p>$mensaje</p>";

echo "<form method='POST' action=''>";
echo "<label>Tu nombre:</label><br>";
echo "<input type='text' name='name' required><br><br>";

// Pregunta 1
echo "<label>1. ¿Cuál es el valor de x en 3x + 5 = 14?</label><br>";
echo "<input type='radio' name='q1' value='3' required> 3<br>";
echo "<input type='radio' name='q1' value='4' required> 4<br><br>";

// Pregunta 2
echo "<label>2. Factorización de x&sup2; + 5x + 6</label><br>";
echo "<input type='radio' name='q2' value='(x + 2)(x + 3)' required> (x + 2)(x + 3)<br>";
echo "<input type='radio' name='q2' value='(x + 1)(x + 6)' required> (x + 1)(x + 6)<br><br>";

// Pregunta 3
echo "<label>3. Solución de la ecuación x&sup2; - 5x + 6 = 0</label><br>";
echo "<input type='radio' name='q3' value='x = 2, x = 3' required> x = 2, x = 3<br>";
echo "<input type='radio' name='q3' value='x = 1, x = 6' required> x = 1, x = 6<br><br>";

// Pregunta 4
echo "<label>4. Resultado de 11/15 + 11/15</label><br>";
echo "<input type='radio' name='q4' value='22/15' required> 22/15<br>";
echo "<input type='radio' name='q4' value='11/30' required> 11/30<br><br>";

echo "<button type='submit'>Enviar</button>";
echo "</form>";
echo "</body></html>";
?>