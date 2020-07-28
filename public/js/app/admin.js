document.addEventListener('DOMContentLoaded', () => {
    //toggle sidebar
    document.querySelector('#sidebar-toggler').addEventListener('click', event => {
        document.querySelector('#wrapper').classList.toggle('toggled')

        if (document.querySelector('#wrapper').classList.contains('toggled')) {
            document.querySelector('#sidebar-toggler').innerHTML = '<i class="fa fa-angle-right"></i>'
        } else {
            document.querySelector('#sidebar-toggler').innerHTML = '<i class="fa fa-angle-left"></i>'
        }
    })

    //select all tables entries
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

    //delete selected tables entries
    if (document.querySelector('#bulk-delete')) {
        document.querySelector('#bulk-delete').addEventListener('click', event => {
            title = event.target.innerHTML
            event.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>'
            
            if (window.confirm('Are you sure want to delete all selected entries?')) {
                items = []

                document.querySelectorAll('table input[type=checkbox]').forEach(element => {
                    if (element.id !== 'select-all' && element.checked) {
                        items.push(element.dataset.id)
                    }
                })
    
                if (items.length > 0) {
                    fetch(event.target.dataset.url, {
                        method: 'post',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({items: items})
                    }).then(() => window.location.reload())
                }
            } 
              
            event.target.innerHTML = title
        })
    }

    //filter tables
    //https://stackoverflow.com/questions/51187477/how-to-filter-a-html-table-using-simple-javascript    
    if (document.querySelector('#filter')) {
        document.querySelector('#filter').addEventListener('keyup', event => {
            const filter = event.target.value.toUpperCase()
    
            document.querySelectorAll('.table tbody tr:not(.header)').forEach(tr => {
                const match = [...tr.children].some(td => td.textContent.toUpperCase().includes(filter))
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
})
