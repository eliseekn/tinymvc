/**
 * display popup alert
 *
 * @class AlertPopup
 * @constructor
 */
class AlertPopup extends HTMLElement {
    constructor() {
        super()
        this.icon = this.icon.bind(this)
    }

    icon() {
        switch(this.getAttribute('type')) {
            case 'primary':
                return '<i class="fa fa-info-circle text-primary fa-4x"></i>'
            case 'danger':
                return '<i class="fa fa-times-circle text-danger fa-4x"></i>'
            case 'warning':
                return '<i class="fa fa-exclamation-triangle text-warning fa-4x"></i>'
            default:
                return '<i class="fa fa-check-circle text-success fa-4x"></i>'
        }
    }

    connectedCallback() {
        let element = document.createElement('div')
        element.id = 'alert-popup'
        element.setAttribute('tabindex', '-1')
        element.setAttribute('role', 'dialog')
        element.classList.add('modal', 'fade')
        element.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        ${this.icon()}
                        <p class="modal-title my-2">${this.getAttribute('message')}</p>
                        <button type="button" class="btn btn-${this.getAttribute('type')}" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        `

        document.body.appendChild(element)

        $('#alert-popup').modal({ show: true })
        $('#alert-popup').on('hidden.bs.modal', function () { document.body.removeChild(element) })
    }
}

export default AlertPopup
