<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = ""; // Mensaje inicial vacío
$puntuacion = 0; // Inicializar puntuación en 0

// Respuestas correctas
$respuestas_correctas = array(
    "q1" => "compuesto de carbono e hidrógeno", // Pregunta 1
    "q2" => "compuesto sin enlaces dobles o triples", // Pregunta 2
    "q3" => "eteno", // Pregunta 3
    "q4" => "grupo de átomos que determina las propiedades químicas", // Pregunta 4
    "q5" => "CH4", // Pregunta 5
    "q6" => "moléculas con la misma fórmula molecular pero diferente estructura", // Pregunta 6
    "q7" => "etanol", // Pregunta 7
    "q8" => "hidrocarburo con estructura en anillo", // Pregunta 8
    "q9" => "fabricación de plásticos", // Pregunta 9
    "q10" => "una reacción en la que se añade una molécula a un enlace doble", // Pregunta 10
    "q11" => "enlace simple", // Pregunta 11
    "q12" => "una reacción en la que un átomo o grupo de átomos es reemplazado por otro", // Pregunta 12
    "q13" => "propano", // Pregunta 13
    "q14" => "un compuesto formado por la reacción entre un ácido y un alcohol", // Pregunta 14
    "q15" => "un ácido es un aceptor de pares de electrones y una base es un donador de pares de electrones" // Pregunta 15
);

// Procesar respuestas si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir el nombre del usuario, con valor por defecto "Anonimo"
    $nombre = isset($_POST['name']) ? $_POST['name'] : "Anonimo";

    // Comprobar respuestas correctas
    foreach ($respuestas_correctas as $key => $value) {
        if (isset($_POST[$key]) && strtolower($_POST[$key]) == strtolower($value)) {
            $puntuacion++;
        }
    }

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "puntajes");
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Definir el ID del cuestionario
    $id_cuestionario = 9; // ID fijo del cuestionario

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
        $mensaje = "$nombre, tu puntuación es: $puntuacion de 15. ";
        if ($puntuacion == 15) {
            $mensaje .= "¡Excelente!";
        } elseif ($puntuacion >= 10) {
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
echo "<head><meta charset='UTF-8'><title>Cuestionario de Química</title><style>body{font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9;} .container{max-width: 700px; margin: 0 auto; padding: 20px; background: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);} h1{text-align: center;} .question{margin: 10px 0;} label{display: block;}</style></head>";
echo "<body>";
echo "<div class='container'>";
echo "<h1>Cuestionario de Química</h1>";
if (!empty($mensaje)) echo "<p>$mensaje</p>";

echo "<form method='POST' action=''>";

// Nombre del usuario
echo "<label>Tu nombre:</label><br>";
echo "<input type='text' name='name' required><br><br>";

// Pregunta 1
echo "<div class='question'><label>1. Define qué es un hidrocarburo.</label><br>";
echo "<input type='radio' name='q1' value='compuesto de carbono e hidrógeno' required> Compuesto de carbono e hidrógeno<br>";
echo "<input type='radio' name='q1' value='compuesto formado por oxígeno e hidrógeno' required> Compuesto formado por oxígeno e hidrógeno<br>";
echo "<input type='radio' name='q1' value='compuesto de carbono y nitrógeno' required> Compuesto de carbono y nitrógeno<br></div>";

// Pregunta 2
echo "<div class='question'><label>2. ¿Qué es un compuesto saturado?</label><br>";
echo "<input type='radio' name='q2' value='compuesto sin enlaces dobles o triples' required> Compuesto sin enlaces dobles o triples<br>";
echo "<input type='radio' name='q2' value='compuesto con enlaces dobles' required> Compuesto con enlaces dobles<br>";
echo "<input type='radio' name='q2' value='compuesto con enlaces triples' required> Compuesto con enlaces triples<br></div>";

// Pregunta 3
echo "<div class='question'><label>3. Da un ejemplo de un alqueno.</label><br>";
echo "<input type='radio' name='q3' value='eteno' required> Eteno<br>";
echo "<input type='radio' name='q3' value='etano' required> Etano<br>";
echo "<input type='radio' name='q3' value='metano' required> Metano<br></div>";

// Pregunta 4
echo "<div class='question'><label>4. ¿Qué es un grupo funcional?</label><br>";
echo "<input type='radio' name='q4' value='grupo de átomos que determina las propiedades químicas' required> Grupo de átomos que determina las propiedades químicas<br>";
echo "<input type='radio' name='q4' value='grupo de átomos que determina la cantidad de átomos en la molécula' required> Grupo de átomos que determina la cantidad de átomos en la molécula<br>";
echo "<input type='radio' name='q4' value='grupo de átomos que altera las reacciones químicas' required> Grupo de átomos que altera las reacciones químicas<br></div>";

// Pregunta 5
echo "<div class='question'><label>5. ¿Cuál es la fórmula del metano?</label><br>";
echo "<input type='radio' name='q5' value='CH4' required> CH4<br>";
echo "<input type='radio' name='q5' value='C2H6' required> C2H6<br>";
echo "<input type='radio' name='q5' value='C3H8' required> C3H8<br></div>";

echo "<button type='submit'>Enviar Respuestas</button>";
echo "</form>";
echo "</div>";
echo "</body></html>";
?>
