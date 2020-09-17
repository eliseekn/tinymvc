class AlertModal extends HTMLElement {
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
        element.id = 'alert-modal'
        element.setAttribute('tabindex', '-1')
        element.setAttribute('role', 'dialog')
        element.classList.add('modal', 'fade')
        element.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        ${this.modalIcon()}
                        <p class="modal-title my-3">${this.getAttribute('message')}</p>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        `

        document.body.appendChild(element)

        $('#alert-modal').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        })
    }
}

window.customElements.define('alert-modal', AlertModal)