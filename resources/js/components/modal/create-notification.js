class CreateNotification extends HTMLElement {
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
        element.id = 'create-notification'
        element.setAttribute('tabindex', '-1')
        element.setAttribute('role', 'dialog')
        element.classList.add('modal', 'fade')
        element.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light text-dark align-items-center py-2">
                        <h5 class="modal-title">${this.getAttribute('modal_title')}</h5>
                        <button type="button" class="btn" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                    <form method="post" action="${this.getAttribute('action')}">
                        ${this.getAttribute('csrf_token')}

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="message">Message</label>
                                <input type="text" name="message" id="message" class="form-control" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark loading">${this.getAttribute('modal_button_title')}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">${this.getAttribute('modal_button_cancel')}</button>
                        </div>
                    </form>
                </div>
            </div>
        `

        document.body.appendChild(element)

        $('#create-notification').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        })

        $('#create-notification').on('hidden.bs.modal', function (e) {
            document.body.removeChild(element)
        })
    }
}

export default CreateNotification
