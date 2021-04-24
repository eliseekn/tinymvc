/**
 * display upload file popup
 *
 * @class UploadModal
 * @constructor
 */
class UploadModal extends HTMLElement {
    constructor() {
        super()
        this.translations = {}
        this.inputHTML = this.inputHTML.bind(this)
        this.getTranslations = this.getTranslations.bind(this)
        this.showDialog = this.showDialog.bind(this)
        this.addEventListener('click', this.showDialog)
    }

    getTranslations() {
        fetch(process.env.APP_URL + '/api/translations')
            .then(response => response.json())
            .then(data => this.translations = data.translations)
    }

    inputHTML() {
        if (this.getAttribute('multiple') === "") {
            return `<input type="file" name="file" id="file" class="form-group-file" required>`
        } else {
            return `<input type="file" name="files[]" id="files" class="form-group-file" required multiple>`
        }
    }

    connectedCallback() {
        this.getTranslations()
    }

    showDialog() {
        let element = document.createElement('div')
        element.id = 'upload-modal'
        element.setAttribute('tabindex', '-1')
        element.setAttribute('role', 'dialog')
        element.classList.add('modal', 'fade')
        element.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light text-dark align-items-center py-2">
                        <h5 class="modal-title">${this.translations.upload}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" action="${this.getAttribute('action')}" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="${document.querySelector('meta[name="csrf_token"]').content}">

                        <div class="modal-body">${this.inputHTML()}</div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark">${this.translations.submit}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">${this.translations.cancel}</button>
                        </div>
                    </form>
                </div>
            </div>
        `

        document.body.appendChild(element)

        $('#upload-modal').modal({ show: true })
        $('#upload-modal').on('hidden.bs.modal', function (e) { document.body.removeChild(element) })
    }

    disconnectedCallback() {
        this.removeEventListener('click')
    }
}

export default UploadModal
