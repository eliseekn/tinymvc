document.addEventListener('DOMContentLoaded', () => {
    //select all table items
    if (document.querySelector('#select-all')) {
        document.querySelector('#select-all').addEventListener('change', event => {
            if (event.target.checked) {
                document.querySelectorAll('.table input[type=checkbox]').forEach(element => {
                    element.checked = true
                })
            } else {
                document.querySelectorAll('.table input[type=checkbox]').forEach(element => {
                    element.checked = false
                })
            }
        })
    }

    //delete selected table items
    if (document.querySelector('#bulk-delete')) {
        document.querySelector('#bulk-delete').addEventListener('click', event => {
            var items = []

            document.querySelectorAll('.table input[type=checkbox]').forEach(element => {
                if (element.id !== 'select-all' && element.checked) {
                    items.push(element.dataset.id)
                }
            })

            fetch('/tinymvc/api/translations')
                .then(response => response.json())
                .then(data => {
                    const innerHTML = event.target.innerHTML
                    event.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>'
                    
                    if (items.length === 0) {
                        alert(data.translations.items_not_checked)
                        event.target.innerHTML = innerHTML
                        return
                    }
                    
                    if (window.confirm(data.translations.delete_items)) {
                        let formData = new FormData()
                        formData.append('csrf_token', document.querySelector('#csrf_token').value)
                        formData.append('items', items)
        
                        fetch(event.target.dataset.url, {
                            method: 'post',
                            body: formData
                        })
                            .then(() => window.location.reload())
                    } 

                    event.target.innerHTML = innerHTML
                })
        })
    }

    //mark notifications as read
    if (document.querySelector('#bulk-read')) {
        document.querySelector('#bulk-read').addEventListener('click', event => {
            var items = []

            document.querySelectorAll('.table input[type=checkbox]').forEach(element => {
                if (element.id !== 'select-all' && element.checked) {
                    items.push(element.dataset.id)
                }
            })

            fetch('/tinymvc/api/translations')
                .then(response => response.json())
                .then(data => {
                    const innerHTML = event.target.innerHTML
                    event.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>'
                    
                    if (items.length === 0) {
                        alert(data.translations.items_not_checked)
                        event.target.innerHTML = innerHTML
                        return
                    }

                    if (window.confirm(data.translations.mark_items_as_read)) {
                        let formData = new FormData()
                        formData.append('csrf_token', document.querySelector('#csrf_token').value)
                        formData.append('items', items)

                        fetch(event.target.dataset.url, {
                            method: 'post',
                            body: formData
                        })
                            .then(() => window.location.reload())
                    } 
                    
                    event.target.innerHTML = innerHTML
                })
        })
    }

    //filter tables
    //https://stackoverflow.com/questions/51187477/how-to-filter-a-html-table-using-simple-javascript
    if (document.querySelector('#filter')) {
        document.querySelector('#filter').addEventListener('keyup', event => {
            const filterText = event.target.value.toUpperCase()

            document.querySelectorAll('.table tbody tr:not(.header)').forEach(tr => {
                const match = [...tr.children].some(td => td.textContent.toUpperCase().includes(filterText))
                tr.style.display = match ? '' : 'none'
            })
        })
    }

    //sort tables
    //https://stackoverflow.com/questions/14267781/sorting-html-table-with-javascript
    if (document.querySelector('.table')) {
        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent

        const comparer = (idx, asc) => (a, b) => (
            (v1, v2) => v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
        ) (
            getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx)
        )

        document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
            const table = th.closest('table')
            const tbody = table.querySelector('tbody')
            
            Array.from(tbody.querySelectorAll('tr'))
                .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
                .forEach(tr => tbody.appendChild(tr))
        })))
    }

    //metrics trends
    if (document.querySelector('#users-trends')) {
        document.querySelector('#users-trends').addEventListener('change', event => {
            if (event.target.value === 'last-years') {
                fetch(event.target.dataset.url + '/years/5')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('bars-chart').setAttribute('data', data.metrics)
                        document.querySelector('bars-chart').setAttribute('xkey', 'year')
                    })
            }
            
            else if (event.target.value === 'last-weeks') {
                fetch(event.target.dataset.url + '/weeks/4')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('bars-chart').setAttribute('data', data.metrics)
                        document.querySelector('bars-chart').setAttribute('xkey', 'day')
                    })
            }

            else {
                fetch(event.target.dataset.url + '/' + event.target.value)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('bars-chart').setAttribute('data', data.metrics)
                        event.target.value === 'weeks' ? document.querySelector('bars-chart').setAttribute('xkey', 'day') : document.querySelector('bars-chart').setAttribute('xkey', 'month')
                    })
            }
        })
    }

    //media search
    if (document.querySelector('#media-search')) {
        document.querySelector('#media-search').addEventListener('keypress', event => {
            if (event.keyCode == 13) {
                window.location.href = event.target.dataset.url + event.target.value
            }
        })
    }
})
