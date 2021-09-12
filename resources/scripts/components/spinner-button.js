document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelectorAll('.spinner')) {
        document.querySelectorAll('.spinner').forEach(element => {
            element.addEventListener('click', event => {
                event.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>'
            })
        })
    }
})
