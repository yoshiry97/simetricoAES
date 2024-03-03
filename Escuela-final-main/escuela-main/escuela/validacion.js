function validarFormulario() {
    var nombre = document.getElementById("nombre").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var telefono = document.getElementById("telefono").value;

    // Validación de campos
    if (nombre === "" || email === "" || password === "" || telefono === "") {
        alert("Por favor, completa todos los campos.");
        return false;
    }

    // Validación de contraseña
    if (password.length < 10) {
        alert("La contraseña debe tener al menos 10 caracteres.");
        return false;
    }

    if (!/[0-9]/.test(password)) {
        alert("La contraseña debe contener al menos un número.");
        return false;
    }

    if (!/[a-zA-Z]/.test(password)) {
        alert("La contraseña debe contener al menos una letra.");
        return false;
    }

    if (!/[^a-zA-Z0-9]/.test(password)) {
        alert("La contraseña debe contener al menos un carácter especial.");
        return false;
    }

    return true;
}
