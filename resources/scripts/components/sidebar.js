document.addEventListener('DOMContentLoaded', () => {
    //toggle sidebar
    if (document.querySelector('.sidebar-toggler')) {
        document.querySelector('.sidebar-toggler').addEventListener('click', event => {
            document.querySelector('.wrapper').classList.toggle('toggled')
            document.querySelector('.sidebar-close').classList.toggle('d-none')
        })
    }

    //close sidebar
    if (document.querySelector('.sidebar-close')) {
        document.querySelector('.sidebar-close').addEventListener('click', event => {
            document.querySelector('.wrapper').classList.toggle('toggled')
            document.querySelector('.sidebar-close').classList.toggle('d-none')
        })
    }

    //dropdown menu
    if (document.querySelectorAll('#dropdown-btn')) {
        document.querySelectorAll('#dropdown-btn').forEach(element => {
            element.addEventListener('click', event => {
                if (document.getElementById(event.target.dataset.target).classList.contains('d-none')) {
                    element.childNodes[3].childNodes[0].innerHTML = '<i class="fa fa-caret-up"></i>'
                    document.getElementById(event.target.dataset.target).classList.remove('d-none')
                } else {
                    element.childNodes[3].childNodes[0].innerHTML = '<i class="fa fa-caret-down"></i>'
                    document.getElementById(event.target.dataset.target).classList.add('d-none')
                }
            })
        })
    }
})