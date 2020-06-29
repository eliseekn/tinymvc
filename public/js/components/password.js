document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('#password-toggler')) {
        document.querySelector('#password-toggler').addEventListener('click', () => {
            if (document.querySelector('#password').type === 'password') {
                document.querySelector('#password').type = 'text';
                document.querySelector('#password-toggler').innerHTML = '<i class="fa fa-eye"></i>'
            } else {
                document.querySelector('#password').type = 'password';
                document.querySelector('#password-toggler').innerHTML = '<i class="fa fa-eye-slash"></i>'
            }
        })
    }
})