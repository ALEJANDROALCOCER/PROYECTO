<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = ""; // Mensaje inicial vacío
$puntuacion = 0; // Inicializar puntuación en 0

// Respuestas correctas
$respuestas_correctas = array(
    "q1" => "1/2",  // Pregunta 1
    "q2" => "6",     // Pregunta 2
    "q3" => "1/2",   // Pregunta 3
    "q4" => "1/3",   // Pregunta 4
    "q5" => "10",    // Pregunta 5
    "q6" => "2/6",   // Pregunta 6
    "q7" => "3",     // Pregunta 7
    "q8" => "5",     // Pregunta 8
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
    $id_cuestionario = 5; // ID fijo del cuestionario

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
        } elseif ($puntuacion >= 6) {
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
echo "<head><meta charset='UTF-8'><title>Cuestionario de Probabilidad y Estadística</title></head>";
echo "<body>";
echo "<div class='container'>";
echo "<h1 class='text-center'>Cuestionario de Probabilidad y Estadística</h1>";
if (!empty($mensaje)) echo "<p>$mensaje</p>";

echo "<form method='POST' action=''>";

// Nombre del usuario
echo "<label>Tu nombre:</label><br>";
echo "<input type='text' name='name' required><br><br>";

// Pregunta 1
echo "<label>1. Si lanzamos una moneda al aire, ¿cuál es la probabilidad de que salga cara?</label><br>";
echo "<input type='radio' name='q1' value='1/2' required> a) 1/2<br>";
echo "<input type='radio' name='q1' value='1/3' required> b) 1/3<br>";
echo "<input type='radio' name='q1' value='1/4' required> c) 1/4<br>";
echo "<input type='radio' name='q1' value='2/3' required> d) 2/3<br><br>";

// Pregunta 2
echo "<label>2. ¿Cuál es la media de los números 2, 4, 6, 8, 10?</label><br>";
echo "<input type='radio' name='q2' value='6' required> a) 6<br>";
echo "<input type='radio' name='q2' value='5' required> b) 5<br>";
echo "<input type='radio' name='q2' value='4' required> c) 4<br>";
echo "<input type='radio' name='q2' value='7' required> d) 7<br><br>";

// Pregunta 3
echo "<label>3. Si tenemos un dado regular, ¿cuál es la probabilidad de sacar un número mayor que 4?</label><br>";
echo "<input type='radio' name='q3' value='1/2' required> a) 1/2<br>";
echo "<input type='radio' name='q3' value='1/3' required> b) 1/3<br>";
echo "<input type='radio' name='q3' value='2/3' required> c) 2/3<br>";
echo "<input type='radio' name='q3' value='1/6' required> d) 1/6<br><br>";

// Pregunta 4
echo "<label>4. Si lanzamos un dado regular, ¿cuál es la probabilidad de sacar un número par?</label><br>";
echo "<input type='radio' name='q4' value='1/2' required> a) 1/2<br>";
echo "<input type='radio' name='q4' value='1/3' required> b) 1/3<br>";
echo "<input type='radio' name='q4' value='2/3' required> c) 2/3<br>";
echo "<input type='radio' name='q4' value='1/6' required> d) 1/6<br><br>";

// Pregunta 5
echo "<label>5. ¿Cuál es la varianza de los números 1, 2, 3, 4, 5?</label><br>";
echo "<input type='radio' name='q5' value='2' required> a) 2<br>";
echo "<input type='radio' name='q5' value='1' required> b) 1<br>";
echo "<input type='radio' name='q5' value='3' required> c) 3<br>";
echo "<input type='radio' name='q5' value='4' required> d) 4<br><br>";

// Pregunta 6
echo "<label>6. Si lanzamos un dado regular, ¿cuál es la probabilidad de sacar un número menor que 3?</label><br>";
echo "<input type='radio' name='q6' value='2/6' required> a) 2/6<br>";
echo "<input type='radio' name='q6' value='3/6' required> b) 3/6<br>";
echo "<input type='radio' name='q6' value='4/6' required> c) 4/6<br>";
echo "<input type='radio' name='q6' value='1/6' required> d) 1/6<br><br>";

// Pregunta 7
echo "<label>7. ¿Qué es una distribución normal?</label><br>";
echo "<input type='radio' name='q7' value='Curva en forma de campana' required> a) Curva en forma de campana<br>";
echo "<input type='radio' name='q7' value='Distribución uniforme' required> b) Distribución uniforme<br>";
echo "<input type='radio' name='q7' value='Distribución sesgada' required> c) Distribución sesgada<br>";
echo "<input type='radio' name='q7' value='Distribución binomial' required> d) Distribución binomial<br><br>";

// Pregunta 8
echo "<label>8. ¿Cuál es la desviación estándar de los números 1, 2, 3, 4, 5?</label><br>";
echo "<input type='radio' name='q8' value='1.41' required> a) 1.41<br>";
echo "<input type='radio' name='q8' value='2.24' required> b) 2.24<br>";
echo "<input type='radio' name='q8' value='2.00' required> c) 2.00<br>";
echo "<input type='radio' name='q8' value='1.73' required> d) 1.73<br><br>";

echo "<button type='submit'>Enviar</button>";
echo "</form>";
echo "</div>";
echo "</body></html>";
?>
