/**
 * send delete request
 *
 * @class DeleteItem
 * @constructor
 */
class DeleteItem extends HTMLElement {
    constructor() {
        super()
        this.translations = {}
        this.getTranslations = this.getTranslations.bind(this)
        this.showDialog = this.showDialog.bind(this)
        this.addEventListener('click', this.showDialog)
    }

    getTranslations() {
        fetch(process.env.APP_URL + 'api/translations')
            .then(response => response.json())
            .then(data => this.translations = data.translations)
    }

    connectedCallback() {
        this.innerHTML = `
            <form method="post" action="${this.getAttribute('action') + '?csrf_token=' + document.querySelector('meta[name="csrf_token"]').content}" class="d-inline-block">
                <input type="hidden" name="request_method" value="delete">

                ${this.innerHTML}
            </form>
        `

        this.getTranslations()
    }

    showDialog() {
        let submitLink = this.firstElementChild.querySelector('a')
        let innerHTML = submitLink.innerHTML
        submitLink.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>'

        let confirm = document.createElement('confirm-popup')
        confirm.setAttribute('message', this.translations.delete_item)
        document.body.appendChild(confirm)

        document.querySelector('#yes-button').addEventListener('click', () => {
            this.firstElementChild.submit()
        })
        
        document.body.removeChild(confirm)
        submitLink.innerHTML = innerHTML
    }

    disconnectedCallback() {
        this.removeEventListener('click')
    }
}

export default DeleteItem