(() => {
    const buttonForShowPass = document.querySelector('.btn-view-pass');
    const inputPass = document.getElementsByName('password')[0];

    buttonForShowPass.addEventListener('click', handleViewPassButton);


    function handleViewPassButton() {
        console.log(this.firstElementChild.firstElementChild)
        if (this.firstElementChild.firstElementChild.matches('.fa-eye')) {
            this.firstElementChild.firstElementChild.classList.remove('fa-eye');
            this.firstElementChild.firstElementChild.classList.add('fa-eye-slash');
            handleFormInput(this);
        } else if (this.firstElementChild.firstElementChild.matches('.fa-eye-slash')) {
            this.firstElementChild.firstElementChild.classList.remove('fa-eye-slash');
            this.firstElementChild.firstElementChild.classList.add('fa-eye');
            handleFormInput(this)
        }

    }

    function handleFormInput(button) {
        if (inputPass.type == 'text') {
            inputPass.type = 'password'
        } else if (inputPass.type == 'password') {
            inputPass.type = 'text'
        }
    }
})();