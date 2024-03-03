<?php
include("db.php");
require_once 'config.php';

// Función para descifrar datos
function decrypt_data($data)
{
    // Utiliza la clave de cifrado estática definida en config.php
    $key = CLAVE_CIFRADO_ESTATICA;
    
    // Convierte la cadena hexadecimal de vuelta a datos binarios
    $combined = hex2bin($data);
    // Obtiene el IV de los primeros 16 bytes de los datos combinados
    $iv = substr($combined, 0, 16);
    // Obtiene los datos cifrados después del IV
    $encrypted = substr($combined, 16);
    // Descifra los datos con el IV y la clave proporcionada
    $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    // Retorna la contraseña descifrada
    return $decrypted;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $EMAIL = $_POST['email'];
    $PASSWORD = $_POST['password'];

    // Consulta el usuario utilizando el correo electrónico
    $consulta = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("s", $EMAIL);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Obtiene el primer resultado
        $fila = $resultado->fetch_assoc();
        // Obtiene la contraseña cifrada almacenada en la base de datos
        $contraseña_cifrada = $fila['contraseña'];
        // Descifra la contraseña almacenada en la base de datos
        $contraseña_descifrada = decrypt_data($contraseña_cifrada, CLAVE_CIFRADO_ESTATICA);
        // Compara la contraseña proporcionada con la contraseña descifrada
        // Comparación de contraseñas utilizando hash_equals
        if (hash_equals($contraseña_descifrada, $PASSWORD)) {
            // Contraseña válida, redirige al usuario a la página de inicio
            header("location:indexAdmin.php");
            exit();
        }
    }

    // Si las credenciales no son válidas, muestra un mensaje de error y vuelve al formulario de inicio de sesión
    include("login.html");
    echo "<h1>ERROR DE AUTENTICACIÓN</h1>";

    $stmt->close();
    $conexion->close();
} else {
    // Si el método de solicitud no es POST, redirige al usuario al formulario de inicio de sesión
    header("Location: login.html");
    exit();
}
