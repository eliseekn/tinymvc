document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('#select-all')) {
        document.querySelector('#select-all').addEventListener('change', event => {
            if (event.target.checked) {
                document.querySelectorAll('table input[type=checkbox]').forEach(element => {
                    element.checked = true
                })
            } else {
                document.querySelectorAll('table input[type=checkbox]').forEach(element => {
                    element.checked = false
                })
            }
        })
    }

    if (document.querySelector('#bulk-delete')) {
        document.querySelector('#bulk-delete').addEventListener('click', event => {
            items = []
            title = event.target.textContent
            event.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> ' + title
            
            document.querySelectorAll('table input[type=checkbox]').forEach(element => {
                if (element.id !== 'select-all' && element.checked) {
                    items.push(element.dataset.userId)
                }
            })

            fetch(event.target.dataset.url, {
                method: 'post',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({items: items})
            }).then(() => window.location.reload())
        })
    }
})
