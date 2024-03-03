<?php
// Incluye el archivo de configuración que contiene la clave de cifrado
require_once 'config.php'; // Asegúrate de que la clave de cifrado esté definida en este archivo

// Función para cifrar datos
function encrypt_data($data)
{
    // Utiliza la clave de cifrado estática definida en config.php
    $key = CLAVE_CIFRADO_ESTATICA;
    
    // Genera un IV de 16 bytes
    $iv = openssl_random_pseudo_bytes(16);
    // Cifra los datos con el IV y la clave proporcionada
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    // Combina el IV y los datos cifrados
    $combined = $iv . $encrypted;
    // Codifica los datos combinados en hexadecimal
    $hex = bin2hex($combined);
    return $hex;
}

// Verifica si se han enviado datos desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $telefono = $_POST['teléfono'];

    // Guardar los datos en la base de datos
    $servername = "localhost";
    $username = "root";
    $pass = "";
    $dbname = "escuela";
    // Crea una conexión a la base de datos
    $conn = new mysqli($servername, $username, $pass, $dbname);

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Cifrar la contraseña utilizando la clave de cifrado definida en config.php
    $password_cifrado = encrypt_data($password, CLAVE_CIFRADO_ESTATICA);

    // Prepara la consulta SQL para insertar los datos cifrados en la tabla correspondiente
    $sql = "INSERT INTO usuarios (nombre, correo, contraseña, telefono) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Vincula los parámetros de la consulta con los datos recibidos del formulario y la contraseña cifrada
    $stmt->bind_param("ssss", $nombre, $email, $password_cifrado, $telefono);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        // Redirige o muestra un mensaje de éxito después de guardar los datos
        // header("Location: registro_exitoso.php");
        // exit;
        echo "Los datos se han guardado correctamente en la base de datos.";
    } else {
        echo "Error al guardar los datos en la base de datos: " . $conn->error;
    }

    // Cierra la conexión
    $conn->close();
}
?>
