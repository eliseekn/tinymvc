document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('button[type=submit]')) {
        document.querySelector('button[type=submit]').addEventListener('click', event => {
            title = event.target.innerHTML
            event.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> ' + title
        })
    }
})