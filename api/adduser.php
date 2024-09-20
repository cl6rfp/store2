<?php
include 'config.php';

// Check connection
if ($mysql->connect_error) {
    echo "Error de conexión: " . $mysql->connect_error;
} else {
    echo "Conexión establecida\n";
}

$rol = "";
$name = "";
$email = "";
$phone = "";
$pwd = "";
$photo = "";

// Verificar que los campos email y contraseña no estén vacíos
if (empty($_POST["email"]) || empty($_POST["pwd"])) {
    echo "Tu email o contraseña no pueden estar vacíos, por favor rellena esos datos.\n";
} else {
    $rol = $_POST["rol"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $pwd = $_POST["pwd"];  // Contraseña en texto plano
    $photo = $_POST["photo"];

    // Verificar si el email ya está registrado
    $sql_check = "SELECT * FROM users WHERE email='$email'";
    $result = $mysql->query($sql_check);

    if ($result->num_rows > 0) {
        echo "El usuario ya está registrado.\n";
    } else {
        // Generar un código de verificación en el servidor
        $codigoVerificacion = generarCodigoVerificacion();

        // Insertar todos los datos tal cual (sin codificación) en la base de datos
        $sql = "INSERT INTO users (rol, name, email, phone, pwd, photo, verification_code)
                VALUES ('$rol', '$name', '$email', '$phone', '$pwd', '$photo', '$codigoVerificacion')";

        if ($mysql->query($sql) === TRUE) {
            echo "Usuario registrado con éxito\n";
        } else {
            echo "Error: " . $sql . "<br>" . $mysql->error;
        }
    }
}

// Función para generar un código de verificación
function generarCodigoVerificacion($longitud = 6) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $codigo;
}

?>
