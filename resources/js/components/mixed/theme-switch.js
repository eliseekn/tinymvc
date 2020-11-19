class ThemeSwitch extends HTMLElement {
    constructor() {
        super()
    }

    connectedCallback() {
        this.innerHTML = `
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" name="theme" id="theme" ${this.getAttribute('checked')}>
                <label class="custom-control-label" for="theme"></label>
            </div>
        `

        document.querySelector('#theme').addEventListener('change', () => {
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
        
            document.querySelector('.sidebar-toggler').classList.toggle('border-dark')
            document.querySelector('.sidebar-toggler').classList.toggle('border-light')
            document.querySelector('.sidebar-toggler .fa-bars').classList.toggle('text-light')
        
            if (document.querySelector('#dropdown-notifications')) {
                document.querySelector('#dropdown-notifications').classList.toggle('text-light')
            }
        
            if (document.querySelector('#dropdown-messages')) {
                document.querySelector('#dropdown-messages').classList.toggle('text-light')
            }

            if (document.querySelector('.btn-sm .fa-cog')) {
                document.querySelector('.btn-sm .fa-cog').classList.toggle('text-light')
            }
        
            document.querySelector('.wrapper__sidebar').classList.toggle('bg-light')  
            document.querySelector('.wrapper__sidebar').classList.toggle('bg-white')
            document.querySelector('.wrapper__sidebar .sidebar-title').classList.toggle('bg-light')            
            document.querySelector('.wrapper__sidebar .sidebar-title').classList.toggle('bg-dark')            
            document.querySelector('.wrapper__sidebar .sidebar-title').classList.toggle('text-light')  
            document.querySelector('.wrapper__sidebar .sidebar-title .fa-times').classList.toggle('text-dark')
            document.querySelector('.wrapper__sidebar .sidebar-title .fa-times').classList.toggle('text-light')
        
            if (document.querySelector('#avatar-icon')) {
                document.querySelector('#avatar-icon').classList.toggle('text-light')
                document.querySelector('#avatar-icon').classList.toggle('bg-dark')
                document.querySelector('#avatar-icon').classList.toggle('text-dark')
                document.querySelector('#avatar-icon').classList.toggle('bg-light')
            }
        
            document.querySelectorAll('.card-header .btn-outline-dark').forEach(element => {
                element.classList.toggle('btn-outline-dark')
                element.classList.toggle('btn-light')
            })
        
            document.querySelectorAll('.wrapper__sidebar .list-group-item').forEach(element => {
                element.classList.toggle('bg-light')
            })
        })
    }
}

export default ThemeSwitch
