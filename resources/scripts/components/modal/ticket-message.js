/**
 * display message popup
 *
 * @class TicketMessage
 * @constructor
 */
class TicketMessage extends HTMLElement {
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
        this.getTranslations()
    }

    showDialog() {
        let element = document.createElement('div')
        element.id = 'ticket-message'
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
                        <input type="hidden" name="csrf_token" value="${document.querySelector('meta[name="csrf_token"]').content}">
                        <input type="hidden" name="ticket_id" value="${this.getAttribute('ticket_id')}">
                        
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="message">${this.translations.message}</label>
                                <textarea id="message" name="message" rows="3" class="form-control" required></textarea>
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

        $('#ticket-message').modal({ show: true })
        $('#ticket-message').on('hidden.bs.modal', function (e) { document.body.removeChild(element) })
    }

    disconnectedCallback() {
        this.removeEventListener('click')
    }
}

export default TicketMessage
