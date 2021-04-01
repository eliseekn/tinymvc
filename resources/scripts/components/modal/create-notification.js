/**
 * affiche une fenêtre de création d'une notification
 *
 * @class CreateNotification
 * @constructor
 */
class CreateNotification extends HTMLElement {
    constructor() {
        super()

        this.translations = {}
        this.getTranslations = this.getTranslations.bind(this)
        this.showDialog = this.showDialog.bind(this)
        this.addEventListener('click', this.showDialog)
    }

    /**
     * recupère les traductions
     *
     * @return
     */
    getTranslations() {
        fetch('/tinymvc/api/translations')
            .then(response => response.json())
            .then(data => this.translations = data.translations)
    }

    connectedCallback() {
        this.getTranslations()
    }

    /**
     * affiche la fenêtre modale
     *
     * @return
     */
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
                        <h5 class="modal-title">${this.translations.create_notification}</h5>
                        <button type="button" class="btn" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                    <form method="post" action="${this.getAttribute('action')}">
                        <input type="hidden" name="csrf_token" value="${document.querySelector('meta[name="csrf_token"]').content}">

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="message">${this.translations.message}</label>
                                <input type="text" name="message" id="message" class="form-control" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark loading">${this.translations.submit}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">${this.translations.cancel}</button>
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
