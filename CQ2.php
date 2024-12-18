<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = ""; // Mensaje inicial vacío
$puntuacion = 0; // Inicializar puntuación en 0

// Respuestas correctas
$respuestas_correctas = array(
    "q1" => "a",  // Pregunta 1
    "q2" => "b",  // Pregunta 2
    "q3" => "c",  // Pregunta 3
    "q4" => "a",  // Pregunta 4
    "q5" => "b"   // Pregunta 5
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
    $id_cuestionario = 7; // ID fijo del cuestionario

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
        $mensaje = "$nombre, tu puntuación es: $puntuacion de 5. ";
        if ($puntuacion == 5) {
            $mensaje .= "¡Excelente!";
        } elseif ($puntuacion >= 3) {
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
echo "<head><meta charset='UTF-8'><title>Cuestionario de Química 2</title><style>body{font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9;} .container{max-width: 700px; margin: 0 auto; padding: 20px; background: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);} h1{text-align: center;} .question{margin: 10px 0;} label{display: block;}</style></head>";
echo "<body>";
echo "<div class='container'>";
echo "<h1>Cuestionario de Química 2</h1>";
if (!empty($mensaje)) echo "<p>$mensaje</p>";

echo "<form method='POST' action=''>";

// Nombre del usuario
echo "<label>Tu nombre:</label><br>";
echo "<input type='text' name='name' required><br><br>";

// Pregunta 1
echo "<div class='question'><label>1. ¿Qué describe el número atómico de un elemento?</label><br>";
echo "<input type='radio' name='q1' value='a' required> a) El número de protones<br>";
echo "<input type='radio' name='q1' value='b' required> b) El número de neutrones<br>";
echo "<input type='radio' name='q1' value='c' required> c) El número de electrones<br>";
echo "<input type='radio' name='q1' value='d' required> d) El número de átomos<br></div>";

// Pregunta 2
echo "<div class='question'><label>2. ¿Cómo se organiza la tabla periódica?</label><br>";
echo "<input type='radio' name='q2' value='a' required> a) Por masa atómica<br>";
echo "<input type='radio' name='q2' value='b' required> b) Por número atómico<br>";
echo "<input type='radio' name='q2' value='c' required> c) Por su grupo de trabajo<br>";
echo "<input type='radio' name='q2' value='d' required> d) Por su temperatura de ebullición<br></div>";

// Pregunta 3
echo "<div class='question'><label>3. ¿Qué es un isótopo?</label><br>";
echo "<input type='radio' name='q3' value='a' required> a) Átomos del mismo elemento con el mismo número de neutrones<br>";
echo "<input type='radio' name='q3' value='b' required> b) Átomos con el mismo número de electrones pero diferente número de protones<br>";
echo "<input type='radio' name='q3' value='c' required> c) Átomos con el mismo número de protones pero diferente número de neutrones<br>";
echo "<input type='radio' name='q3' value='d' required> d) Átomos de diferentes elementos con la misma masa atómica<br></div>";

// Pregunta 4
echo "<div class='question'><label>4. ¿Qué son los niveles de energía en un átomo?</label><br>";
echo "<input type='radio' name='q4' value='a' required> a) Las capas de electrones donde se encuentran los electrones<br>";
echo "<input type='radio' name='q4' value='b' required> b) Las órbitas de los protones<br>";
echo "<input type='radio' name='q4' value='c' required> c) Los centros de carga del átomo<br>";
echo "<input type='radio' name='q4' value='d' required> d) Las zonas de vacíos alrededor del núcleo<br></div>";

// Pregunta 5
echo "<div class='question'><label>5. ¿Qué es un enlace iónico?</label><br>";
echo "<input type='radio' name='q5' value='a' required> a) Un enlace donde se comparten electrones<br>";
echo "<input type='radio' name='q5' value='b' required> b) Un enlace entre un metal y un no metal por transferencia de electrones<br>";
echo "<input type='radio' name='q5' value='c' required> c) Un enlace entre dos no metales<br>";
echo "<input type='radio' name='q5' value='d' required> d) Un enlace entre dos metales con intercambio de protones<br></div>";

echo "<button type='submit'>Enviar Respuestas</button>";
echo "</form>";
echo "</div>";
echo "</body></html>";
?>
