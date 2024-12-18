<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = ""; // Mensaje inicial vacío
$puntuacion = 0; // Inicializar puntuación en 0

// Respuestas correctas
$respuestas_correctas = array(
    "q1" => "1",  // Pregunta 1
    "q2" => "1",  // Pregunta 2
    "q3" => "1",  // Pregunta 3
    "q4" => "1",  // Pregunta 4
    "q5" => "1"   // Pregunta 5
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
    $id_cuestionario = 11; // ID fijo del cuestionario

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
echo "<head><meta charset='UTF-8'><title>Cuestionario de Física - Parte 2</title><style>body{font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9;} .container{max-width: 700px; margin: 0 auto; padding: 20px; background: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);} h1{text-align: center;} .question{margin: 10px 0;} label{display: block;}</style></head>";
echo "<body>";
echo "<div class='container'>";
echo "<h1>Cuestionario de Física - Parte 2</h1>";
if (!empty($mensaje)) echo "<p>$mensaje</p>";

echo "<form method='POST' action=''>";

// Nombre del usuario
echo "<label>Tu nombre:</label><br>";
echo "<input type='text' name='name' required><br><br>";

// Pregunta 1
echo "<div class='question'><label>1. ¿Qué es la temperatura?</label><br>";
echo "<input type='radio' name='q1' value='1' required> La medida de la energía cinética promedio de las partículas<br>";
echo "<input type='radio' name='q1' value='2' required> La cantidad de calor en un cuerpo<br>";
echo "<input type='radio' name='q1' value='3' required> La cantidad de materia en un objeto<br></div>";

// Pregunta 2
echo "<div class='question'><label>2. ¿Qué dice la primera ley de la termodinámica?</label><br>";
echo "<input type='radio' name='q2' value='1' required> La energía interna de un sistema cambia por la transferencia de calor o trabajo.<br>";
echo "<input type='radio' name='q2' value='2' required> La energía de un sistema se conserva sin importar las condiciones.<br>";
echo "<input type='radio' name='q2' value='3' required> La temperatura de un cuerpo no cambia cuando se le transfiere calor.<br></div>";

// Pregunta 3
echo "<div class='question'><label>3. ¿Qué es la refracción?</label><br>";
echo "<input type='radio' name='q3' value='1' required> Cambio de dirección de la luz al pasar de un medio a otro.<br>";
echo "<input type='radio' name='q3' value='2' required> La reflexión de la luz al chocar con una superficie.<br>";
echo "<input type='radio' name='q3' value='3' required> El paso de luz a través de un objeto transparente.<br></div>";

// Pregunta 4
echo "<div class='question'><label>4. ¿Qué establece la Ley de Coulomb?</label><br>";
echo "<input type='radio' name='q4' value='1' required> La fuerza entre dos cargas eléctricas es directamente proporcional al producto de las cargas e inversamente proporcional al cuadrado de la distancia entre ellas.<br>";
echo "<input type='radio' name='q4' value='2' required> La fuerza entre dos cargas es inversamente proporcional al producto de las cargas.<br>";
echo "<input type='radio' name='q4' value='3' required> La fuerza entre dos cargas no depende de la distancia entre ellas.<br></div>";

// Pregunta 5
echo "<div class='question'><label>5. ¿Qué es un circuito eléctrico?</label><br>";
echo "<input type='radio' name='q5' value='1' required> Un circuito eléctrico es una ruta cerrada por la que circula la corriente eléctrica.<br>";
echo "<input type='radio' name='q5' value='2' required> Un conjunto de dispositivos que almacenan energía.<br>";
echo "<input type='radio' name='q5' value='3' required> Un sistema que produce electricidad de manera independiente.<br></div>";

echo "<button type='submit'>Enviar Respuestas</button>";
echo "</form>";
echo "</div>";
echo "</body></html>";
?>
