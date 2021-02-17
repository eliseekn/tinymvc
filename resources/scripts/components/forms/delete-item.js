class DeleteItem extends HTMLElement {
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
        this.innerHTML = `
            <form method="post" action="${this.getAttribute('action') + '?csrf_token=' + document.querySelector('#csrf_token').value}" class="d-inline-block">
                <input type="hidden" name="request_method" value="delete">

                ${this.innerHTML}
            </form>
        `

        this.getTranslations()
    }

    showDialog() {
        let innerHTML = this.innerHTML
        this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>'

        if (window.confirm(this.translations.delete_item)) {
            this.firstElementChild.submit()
        }

        this.innerHTML = innerHTML
    }
}

export default DeleteItem