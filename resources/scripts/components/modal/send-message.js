/**
 * display message popup
 *
 * @class SendMessage
 * @constructor
 */
class SendMessage extends HTMLElement {
    constructor() {
        super()
        this.users = []
        this.translations = {}
        this.getTranslations = this.getTranslations.bind(this)
        this.getUsers = this.getUsers.bind(this)
        this.showDialog = this.showDialog.bind(this)
        this.addEventListener('click', this.showDialog)
    }

    getUsers() {
        fetch('/api/users')
            .then(response => response.json())
            .then(data => this.users = data.users)
    }

    getTranslations() {
        fetch('/api/translations')
            .then(response => response.json())
            .then(data => this.translations = data.translations)
    }

    connectedCallback() {
        this.getTranslations()
        this.getUsers()
    }

    showDialog() {
        let element = document.createElement('div')
        element.id = 'send-message'
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
                        
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient">${this.translations.user}</label>
                                <select id="recipient" name="recipient" class="custom-select" required>
                                    <option selected disabled>${this.translations.select_user}</option>

                                    ${
                                        this.users.map(user => {
                                            return `<option value="${user.id}" ${user.id == this.getAttribute('recipient') ? 'selected' : ''}>${user.email}</option>`
                                        })
                                    }
                                </select>
                            </div>

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

        $('#send-message').modal({ show: true })
        $('#send-message').on('hidden.bs.modal', function (e) { document.body.removeChild(element) })
    }

    disconnectedCallback() {
        this.removeEventListener('click')
    }
}

export default SendMessage
