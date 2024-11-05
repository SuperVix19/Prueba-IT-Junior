// validaciones.js

// Ejemplo de validación usando Bootstrap y jQuery
(function () {
    'use strict'

    // Obtener todos los formularios a los que queremos aplicar estilos de validación Bootstrap personalizados
    var forms = document.querySelectorAll('.needs-validation')

    // Bucle sobre ellos y evitar el envío
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                } else {
                    // Validaciones personalizadas adicionales
                    let email = $('#email').val().trim();
                    let password = $('#password').val().trim();
                    if (!validateEmail(email)) {
                        event.preventDefault()
                        event.stopPropagation()
                        alert('Por favor, ingresa un correo electrónico válido.');
                    }
                    if (!validatePassword(password)) {
                        event.preventDefault()
                        event.stopPropagation()
                        alert('Por favor, ingresa una contraseña válida. Debe tener al menos 8 caracteres, una letra mayúscula, una letra minúscula, un número y un carácter especial.');
                    }
                }
                form.classList.add('was-validated')
            }, false)
        })
})()

function validateEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePassword(password) {
    var re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    return re.test(password);
}
