class ConfirmDelete extends HTMLElement {
    constructor() {
        super()

        this.translations = {}
        this.getTranslations = this.getTranslations.bind(this)
        this.showDialog = this.showDialog.bind(this)
        this.addEventListener('click', this.showDialog)
    }

    getTranslations() {
        fetch('/tinymvc/api/translations')
            .then(response => response.json())
            .then(data => this.translations = data.translations)
    }

    connectedCallback() {
        if (this.getAttribute('type') === 'icon') {
            this.innerHTML = `<a class="btn text-danger p-1" title="${this.getAttribute('title')}">${this.getAttribute('content')}</a>`
        } else {
            this.innerHTML = `<a class="btn btn-danger ml-2" href="#">${this.getAttribute('content')}</a>`
        }

        this.getTranslations()
    }

    showDialog() {
        const innerHTML = this.childNodes[0].innerHTML
        this.childNodes[0].innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>'

        if (window.confirm(this.translations.delete_item)) {
            window.location.href = this.getAttribute('action')
        }
        
        this.childNodes[0].innerHTML = innerHTML
    }
}

export default ConfirmDelete