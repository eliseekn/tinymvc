/**
 * display messages icon and messages
 * 
 * @class MessagesIcon
 * @constructor
*/
class MessagesIcon extends HTMLElement {
    constructor() {
        super()
        this.messages = []
        this.translations = {}
        this.getTranslations = this.getTranslations.bind(this)
        this.getMessages = this.getMessages.bind(this)
        this.displayAlert = this.displayAlert.bind(this)
        this.render = this.render.bind(this)

        this.event = document.createEvent('Event')
        this.event.initEvent('render', true, true)

        this.addEventListener('render', () => {
            this.render()
            document.querySelector('#dropdown-messages').classList.toggle('text-light')
        })
    }

    displayAlert() {
        return this.messages.length > 0 ? '<span class="bg-danger notifications-icon"></span>' : ''
    }

    getMessages() {
        fetch('/tinymvc/api/messages')
            .then(response => response.json())
            .then(data => {
                this.messages = data.messages,
                this.dispatchEvent(this.event)
            })
    }

    getTranslations() {
        fetch('/tinymvc/api/translations')
            .then(response => response.json())
            .then(data => {
                this.translations = data.translations
                this.dispatchEvent(this.event)
            })
    }

    connectedCallback() {
        this.getTranslations()
        this.getMessages()
        this.intervalId = window.setInterval(() => this.getMessages(), 10 * 1000) //every 10 seconds
    }

    disconnectedCallback() {
        clearInterval(this.intervalId)
        this.removeEventListener('render')
    }

    render() {
        this.innerHTML = `
            <div class="dropdown">
                <button class="btn btn-sm" type="button" id="dropdown-messages" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title=${this.translations.messages}>
                    <i class="fa fa-envelope fa-lg"></i>
                    
                    ${this.displayAlert()}
                </button>

                <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="dropdown-messages" style="z-index: 1111">
                    <p class="font-weight-bold px-4 py-2 text-center">
                        ${this.translations.messages} (${this.messages.length})
                    </p>

                    <div class="dropdown-divider my-0"></div>

                    ${
                        this.messages.map(message => {
                            return `<div class="dropdown-item py-2" style="width: 350px">
                                <p class="text-wrap">
                                    <avatar-icon name=${message.sender_name} (${message.sender_email})></avatar-icon>
                                    ${message.message}
                                </p>
                                <span class="small text-muted">${message.created_at}</span>
                            </div>
                            `
                        })
                    }

                    <div class="dropdown-divider my-0"></div>

                    <div class="px-4 py-2 bg-light text-center">
                        <a class="text-primary" href="/tinymvc/admin/account/messages">
                            ${this.translations.view_all}
                        </a>
                    </div>
                </div>
            </div>
        `
    }
}

export default MessagesIcon