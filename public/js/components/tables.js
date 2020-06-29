document.addEventListener('DOMContentLoaded', () => {
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

        const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
            v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
            ) (getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx))

        document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
            const table = th.closest('table')
            const tbody = table.querySelector('tbody')
            
            Array.from(tbody.querySelectorAll('tr'))
                .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
                .forEach(tr => tbody.appendChild(tr))
        })))
    }
})