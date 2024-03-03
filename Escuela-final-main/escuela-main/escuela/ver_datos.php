<?php
include("db.php"); // Incluye el archivo de conexión a la base de datos
require_once 'config.php'; // Incluye el archivo de configuración que contiene la clave de cifrado

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

// Consulta todos los datos almacenados en la tabla "usuarios"
$sql = "SELECT * FROM usuarios";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    // Mostrar los datos descifrados
    echo "<h2>Datos almacenados en la base de datos:</h2>";
    while ($fila = $resultado->fetch_assoc()) {
        // Descifra los datos antes de mostrarlos
        $nombre = $fila['nombre'];
        $email = $fila['correo'];
        $contraseña_cifrada = $fila['contraseña'];
        $contraseña_descifrada = decrypt_data($contraseña_cifrada, CLAVE_CIFRADO_ESTATICA);
        $telefono = $fila['telefono'];
        
        // Muestra los datos descifrados
        echo "<p><strong>Nombre:</strong> $nombre</p>";
        echo "<p><strong>Email:</strong> $email</p>";
        echo "<p><strong>Contraseña Descifrada:</strong> $contraseña_descifrada</p>";
        echo "<p><strong>Teléfono:</strong> $telefono</p>";
        echo "<hr>";
    }
} else {
    echo "No se encontraron datos en la base de datos.";
}

// Cierra la conexión a la base de datos
$conexion->close();
?>
