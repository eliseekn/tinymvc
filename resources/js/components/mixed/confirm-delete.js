class ConfirmDelete extends HTMLElement {
    constructor() {
        super()

        this.showDialog = this.showDialog.bind(this)
        this.addEventListener('click', this.showDialog)
    }

    connectedCallback() {
        if (this.getAttribute('type') === 'icon') {
            this.innerHTML = `<a class="btn text-danger p-1" title="Delete item">${this.getAttribute('content')}</a>`
        } else {
            this.innerHTML = `<a class="btn btn-danger ml-2" href="#">${this.getAttribute('content')}</a>`
        }
    }

    showDialog() {
        const innerHTML = this.childNodes[0].innerHTML
        this.childNodes[0].innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>'

        if (window.confirm('Are you sure you want to delete this item?')) {
            window.location.href = this.getAttribute('action')
        }
        
        this.childNodes[0].innerHTML = innerHTML
    }
}

export default ConfirmDelete