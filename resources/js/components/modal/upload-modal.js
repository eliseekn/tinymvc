class UploadModal extends HTMLElement {
    constructor() {
        super()

        this.showDialog = this.showDialog.bind(this)
        this.addEventListener('click', this.showDialog)
        
    }

    connectedCallback() {
        this.innerHTML = `<button class="btn btn-outline-dark ml-2">${this.getAttribute('title')}</button>`
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
                        <h5 class="modal-title">${this.getAttribute('modal_title')}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" action="${this.getAttribute('action')}" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="file" name="file" id="file" class="form-group-file" required>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">${this.getAttribute('modal_button_title')}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">${this.getAttribute('modal_button_cancel')}</button>
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

export default UploadModal
