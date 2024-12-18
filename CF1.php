<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = ""; // Mensaje inicial vacío
$puntuacion = 0; // Inicializar puntuación en 0

// Respuestas correctas
$respuestas_correctas = array(
    "q1" => "9.8 m/s²",                      // Pregunta 1
    "q2" => "F = ma",                        // Pregunta 2
    "q3" => "Energía cinética",              // Pregunta 3
    "q4" => "Newton",                        // Pregunta 4
    "q5" => "Trabajo = Fuerza x Distancia"   // Pregunta 5
);

// Procesar respuestas si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir el nombre del usuario, con valor por defecto "Anónimo"
    $nombre = isset($_POST['name']) ? $_POST['name'] : "Anónimo";

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
    $id_cuestionario = 10; // ID del cuestionario de Física

    // Preparar la consulta (sin el campo 'id', ya que es autoincremental)
    $stmt = $conexion->prepare("INSERT INTO resultados (nombre_usuario, puntuacion, id_cuestionario) VALUES (?, ?, ?)");
    
    if ($stmt === false) {
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
echo "<head><meta charset='UTF-8'><title>Cuestionario de Física</title></head>";
echo "<body>";
echo "<h2>Cuestionario de Física</h2>";
if (!empty($mensaje)) echo "<p>$mensaje</p>";

echo "<form method='POST' action=''>";

// Nombre del usuario
echo "<label>Tu nombre:</label><br>";
echo "<input type='text' name='name' required><br><br>";

// Pregunta 1
echo "<label>1. ¿Cuál es el valor de la aceleración de la gravedad en la Tierra?</label><br>";
echo "<input type='radio' name='q1' value='9.8 m/s²' required> 9.8 m/s²<br>";
echo "<input type='radio' name='q1' value='3.2 m/s²' required> 3.2 m/s²<br><br>";

// Pregunta 2
echo "<label>2. ¿Cuál es la fórmula de la Segunda Ley de Newton?</label><br>";
echo "<input type='radio' name='q2' value='F = ma' required> F = ma<br>";
echo "<input type='radio' name='q2' value='E = mc²' required> E = mc²<br><br>";

// Pregunta 3
echo "<label>3. ¿Cómo se llama la energía asociada al movimiento de un objeto?</label><br>";
echo "<input type='radio' name='q3' value='Energía cinética' required> Energía cinética<br>";
echo "<input type='radio' name='q3' value='Energía potencial' required> Energía potencial<br><br>";

// Pregunta 4
echo "<label>4. ¿Cuál es la unidad de fuerza en el Sistema Internacional?</label><br>";
echo "<input type='radio' name='q4' value='Newton' required> Newton<br>";
echo "<input type='radio' name='q4' value='Joule' required> Joule<br><br>";

// Pregunta 5
echo "<label>5. ¿Qué fórmula describe el trabajo realizado por una fuerza constante?</label><br>";
echo "<input type='radio' name='q5' value='Trabajo = Fuerza x Distancia' required> Trabajo = Fuerza x Distancia<br>";
echo "<input type='radio' name='q5' value='Trabajo = Masa x Aceleración' required> Trabajo = Masa x Aceleración<br><br>";

echo "<button type='submit'>Enviar</button>";
echo "</form>";
echo "</body></html>";
?>
