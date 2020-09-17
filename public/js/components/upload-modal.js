class UploadModal extends HTMLElement {
    constructor() {
        super()

        this.showDialog = this.showDialog.bind(this)
        this.addEventListener('click', this.showDialog)
    }

    connectedCallback() {
        this.innerHTML = '<button class="btn btn-primary ml-2">Import</button>'
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
                    <div class="modal-header">
                        <h5 class="modal-title">Import file</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" action="${this.getAttribute('action')}" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="file" name="file" id="file" class="form-control-file" required>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Import</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
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
    }
}

window.customElements.define('upload-modal', UploadModal)