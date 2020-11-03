document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.card-header').forEach(element => {
        element.classList.toggle('bg-dark')
        element.classList.toggle('text-white')
    })

    document.querySelectorAll('.card-header .fa-dot-circle').forEach(element => {
        element.classList.toggle('text-white')
        element.classList.toggle('text-dark')
    })

    document.querySelectorAll('.card-metrics').forEach(element => {
        element.classList.toggle('bg-dark')
        element.classList.toggle('bg-light')
        element.classList.toggle('text-white')
    })

    document.querySelector('.navbar').classList.toggle('navbar-light')
    document.querySelector('.navbar').classList.toggle('bg-light')
    document.querySelector('.navbar').classList.toggle('navbar-dark')
    document.querySelector('.navbar').classList.toggle('bg-dark')

    document.querySelector('#sidebar-toggler').classList.toggle('border-dark')
    document.querySelector('#sidebar-toggler').classList.toggle('border-light')
    document.querySelector('#sidebar-toggler .fa-bars').classList.toggle('text-light')

    document.querySelector('#dropdown-notifications').classList.toggle('text-light')
    document.querySelector('.fa-cog').classList.toggle('text-light')

    document.querySelector('#sidebar-wrapper').classList.toggle('bg-light')  
    document.querySelector('#sidebar-wrapper .sidebar-title').classList.toggle('bg-light')            
    document.querySelector('#sidebar-wrapper .sidebar-title').classList.toggle('bg-dark')            
    document.querySelector('#sidebar-wrapper .sidebar-title').classList.toggle('text-light')  

    document.querySelectorAll('#sidebar-wrapper .list-group-item').forEach(element => {
        element.classList.toggle('bg-light')
    })
})
