class AlertPopup extends HTMLElement {
    constructor() {
        super()

        this.modalIcon = this.modalIcon.bind(this)
    }

    modalIcon() {
        switch(this.getAttribute('type')) {
            case 'primary':
                return '<i class="fa fa-info-circle text-primary fa-4x"></i>'
            case 'danger':
                return '<i class="fa fa-exclamation-circle text-danger fa-4x"></i>'
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
                    <div class="position-relative bg-${this.getAttribute('type')} rounded-top" style="padding: .13em 0"></div>

                    <div class="modal-body text-center">
                        ${this.modalIcon()}
                        <h5 class="modal-title mt-2 text-${this.getAttribute('type')}">${this.getAttribute('title')}</h5>
                        <p class="modal-title my-2">${this.getAttribute('message')}</p>
                        <button type="button" class="btn btn-${this.getAttribute('type')}" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        `

        document.body.appendChild(element)

        $('#alert-popup').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        })
    }
}

export default AlertPopup