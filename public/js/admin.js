//confirm delete single table item
function confirmDelete(target) {
    const innerHTML = target.innerHTML
    target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>'

    if (window.confirm(target.dataset.message)) {
        window.location.href = target.dataset.redirect
    }
    
    target.innerHTML = innerHTML
}

document.addEventListener('DOMContentLoaded', () => {
    //toggle sidebar
    document.querySelector('#sidebar-toggler').addEventListener('click', event => {
        document.querySelector('#wrapper').classList.toggle('toggled')
    })

    //select all table items
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

    //delete selected table items
    document.querySelector('#bulk-delete').addEventListener('click', event => {
        const innerHTML = event.target.innerHTML
        event.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>'

        if (window.confirm('Are you sure want to delete all selected items?')) {
            items = []

            document.querySelectorAll('.table input[type=checkbox]').forEach(element => {
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
        
        event.target.innerHTML = innerHTML
    })

    //filter tables
    //https://stackoverflow.com/questions/51187477/how-to-filter-a-html-table-using-simple-javascript    
    document.querySelector('#filter').addEventListener('keyup', event => {
        const filterText = event.target.value.toUpperCase()

        document.querySelectorAll('.table tbody tr:not(.header)').forEach(tr => {
            const match = [...tr.children].some(td => td.textContent.toUpperCase().includes(filterText))
            tr.style.display = match ? '' : 'none'
        })
    })

    //sort tables
    //https://stackoverflow.com/questions/14267781/sorting-html-table-with-javascript
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
})
