class UploadModal extends HTMLElement {
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
        this.innerHTML = `<button class="btn btn-outline-dark ml-2">${this.getAttribute('title')}</button>`
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
                        <h5 class="modal-title">${this.getAttribute('modal_title')}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" action="${this.getAttribute('action')}" enctype="multipart/form-data">
                        ${this.getAttribute('csrf_token')}

                        <div class="modal-body">
                            <input type="file" name="file" id="file" class="form-group-file" required>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark">${this.translations.submit}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">${this.translations.cancel}</button>
                        </div>
                    </form>
                </div>
            </div>
        `

        document.body.appendChild(element)

        $('#upload-modal').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        })

        $('#upload-modal').on('hidden.bs.modal', function (e) {
            document.body.removeChild(element)
        })
    }
}

export default UploadModal
