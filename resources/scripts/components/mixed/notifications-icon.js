/**
 * display notifications icon and messages
 * 
 * @class NotificationsIcon
 * @constructor
*/
class NotificationsIcon extends HTMLElement {
    constructor() {
        super()
        this.notifications = []
        this.translations = {}
        this.getTranslations = this.getTranslations.bind(this)
        this.getNotifications = this.getNotifications.bind(this)
        this.displayAlert = this.displayAlert.bind(this)
        this.render = this.render.bind(this)

        this.event = document.createEvent('Event')
        this.event.initEvent('render', true, true)

        this.addEventListener('render', () => {
            this.render()

            if (document.querySelector('.wrapper__content nav').classList.contains('bg-light')) {
                document.querySelector('#dropdown-notifications').classList.toggle('text-dark')
            } else if (document.querySelector('.wrapper__content nav').classList.contains('bg-dark')) {
                document.querySelector('#dropdown-notifications').classList.toggle('text-light')
            } 
        })
    }

    displayAlert() {
        return this.notifications.length > 0 ? '<span class="bg-danger notifications-icon"></span>' : ''
    }

    getNotifications() {
        fetch(process.env.APP_URL + 'api/notifications')
            .then(response => response.json())
            .then(data => {
                this.notifications = data.notifications,
                this.dispatchEvent(this.event)
            })
    }

    getTranslations() {
        fetch(process.env.APP_URL + 'api/translations')
            .then(response => response.json())
            .then(data => {
                this.translations = data.translations
                this.dispatchEvent(this.event)
            })
    }

    connectedCallback() {
        this.getTranslations()
        this.getNotifications()
        this.intervalId = window.setInterval(() => this.getNotifications(), 60 * 1000) //every 60 seconds
    }

    disconnectedCallback() {
        clearInterval(this.intervalId)
        this.removeEventListener('render')
    }

    render() {
        this.innerHTML = `
            <div class="dropdown">
                <button class="btn btn-sm" type="button" id="dropdown-notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title=${this.translations.notifications}>
                    <i class="fa fa-bell fa-lg"></i>
                    
                    ${this.displayAlert()}
                </button>

                <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="dropdown-notifications" style="z-index: 1111">
                    <p class="font-weight-bold px-4 py-2 text-center">
                        ${this.translations.notifications} (${this.notifications.length})
                    </p>

                    <div class="dropdown-divider my-0"></div>

                    ${
                        this.notifications.map(notification => {
                            return `<div class="dropdown-item py-2" style="width: 250px">
                                <p class="text-wrap">${notification.message}</p>
                                <span class="small text-muted">${notification.created_at}</span>
                            </div>
                            `
                        })
                    }

                    <div class="dropdown-divider my-0"></div>

                    <div class="px-4 py-2 bg-light text-center">
                        <a class="text-primary" href="${process.env.APP_URL}admin/account/notifications">
                            ${this.translations.view_all}
                        </a>
                    </div>
                </div>
            </div>
        `
    }
}

export default NotificationsIcon
