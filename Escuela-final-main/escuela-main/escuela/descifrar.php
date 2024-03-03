<?php
// Definir la función para descifrar datos
function decrypt_data($data, $key, $iv)
{
    // Convierte los datos cifrados de hexadecimal a binario
    $encrypted_data = hex2bin($data);
    // Descifra los datos utilizando openssl_decrypt
    $decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    // Retorna los datos descifrados
    return $decrypted;
}

// Definir los valores proporcionados
$cifrado_hex = '19457599c042e29c77b777318c567bd49fbff01a71578014a9bf8c547e7903a3';
$iv_hex = '19457599c042e29c77b777318c567bd4';
$key_hex = '9cac7c88a84346552df55ac7b2e4f322aa6ee41404fc1aa638a7bc3befdaf2c4';

// Convertir el IV y la clave de hexadecimal a binario
$iv = hex2bin($iv_hex);
$key = hex2bin($key_hex);

// Descifrar los datos
$contraseña_descifrada = decrypt_data($cifrado_hex, $key, $iv);

// Imprimir la contraseña descifrada
echo "Contraseña descifrada: " . $contraseña_descifrada;

?>
