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
    if (document.querySelectorAll('.dropdown-btn')) {
        document.querySelectorAll('.dropdown-btn').forEach(element => {
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

    //dropdown submenu
    /** */
    if (document.querySelectorAll('.list-group-item-submenu-toggle')) {
        document.querySelectorAll('.list-group-item-submenu-toggle').forEach(element => {
            element.addEventListener('click', event => {
                event.preventDefault()
                let submenu = document.querySelector('#' + element.dataset.target)

                if (submenu.classList.contains('d-none')) {
                    submenu.classList.remove('d-none')
                } else {
                    submenu.classList.add('d-none')
                }
            })
        })
    }

    if (document.querySelectorAll('.list-group-item-submenu-item')) {
        document.querySelectorAll('.list-group-item-submenu-item').forEach(element => {
            element.addEventListener('click', event => {
                event.preventDefault()
                window.location = event.target.dataset.url
            })
        })
    }
    /** */
})