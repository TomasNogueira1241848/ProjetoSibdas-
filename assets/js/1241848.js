const formContacto = document.getElementById ('formContacto');
if (formContacto) {
    formContacto.addEventListener('submit', function (e){
        e.preventDefault();
        if(!formContacto.checkValidity()){
            e.stopPropagation();
            formContacto.classList.add('was-validated');
            return;
        }
        formContacto.classList.remove('was-validated');
        formContacto.reset();
        document.getElementById('mensagemSucesso').classList.remove('d-none');
        setTimeout(() => {
            document.getElementById('mensagemSucesso').classList.add('d-none');
            }, 4000);
    });
}

const formLogin = document.getElementById('formLogin');

if (formLogin) {
    formLogin.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!formLogin.checkValidity()) {
            formLogin.classList.add('was-validated');
            return;
        }

        window.location.href = '';
    });
}
