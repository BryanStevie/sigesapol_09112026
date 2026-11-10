(() => {
    // Listado de botones
    /**
     * 1. Todos los botones para mostrar contraseña para cada uno de los campos
     * 2. Input de la contraseña anterior
     * 3. Input de la contraseña nueva
     * 4. Input que permite confirmar la contraseña
     */
    const buttonsForShowPass = document.querySelectorAll('.btn-view-pass');
    const inputOldPass = document.getElementById('old_password');
    const inputNewPass = document.getElementById('password');
    const inputConfirmPass = document.getElementById('password_confirmation');

    // Generamos un evento para cada uno de lso botones, que responda al evento "click"
    buttonsForShowPass.forEach((button) => {
        button.addEventListener('click', handleViewPassButton);
    });

    // Dicho evento, llama a esta funcion cada que se ejecute:
    function handleViewPassButton() {
        // "this" hace referencia al boton que llama al evento
        // Comprobamos que el estado del icono que muestra el ojito, y quitamos y agregamos las clases CSS correspondientes:
        if (this.firstElementChild.firstElementChild.matches('.fa-eye')) {
            this.firstElementChild.firstElementChild.classList.remove('fa-eye');
            this.firstElementChild.firstElementChild.classList.add('fa-eye-slash');
            // Esta funcion valida el estado del input:
            handleFormInput(this);
        } else if (this.firstElementChild.firstElementChild.matches('.fa-eye-slash')) {
            this.firstElementChild.firstElementChild.classList.remove('fa-eye-slash');
            this.firstElementChild.firstElementChild.classList.add('fa-eye');
            handleFormInput(this)
        }

    }

    function handleFormInput(button) {
        // Se verifica el estado del input, si es de tipo text se cambia a password, y viceversa
        if (button.id == 'btnShowNewPass') {
            if (inputNewPass.type == 'text') {
                inputNewPass.type = 'password'
            } else if (inputNewPass.type == 'password') {
                inputNewPass.type = 'text'
            }
        }
        if (button.id == 'btnShowOldPass') {
            if (inputOldPass.type == 'text') {
                inputOldPass.type = 'password'
            } else if (inputOldPass.type == 'password') {
                inputOldPass.type = 'text'
            }
        }
        if (button.id == 'btnShowConfirmPass') {
            if (inputConfirmPass.type == 'text') {
                inputConfirmPass.type = 'password'
            } else if (inputConfirmPass.type == 'password') {
                inputConfirmPass.type = 'text'
            }
        }
    }
})();