document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('#password-toggler')) {
        document.querySelector('#password-toggler').addEventListener('click', event => {
            if (document.querySelector('#password').type === 'password') {
                document.querySelector('#password').type = 'text';
                event.target.innerHTML = '<i class="fa fa-eye"></i>'
            } else {
                document.querySelector('#password').type = 'password';
                event.target.innerHTML = '<i class="fa fa-eye-slash"></i>'
            }
        })
    }
})
