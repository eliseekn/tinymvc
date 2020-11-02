class AlertToast extends HTMLElement {
    constructor() {
        super()

        this.toastIcon = this.toastIcon.bind(this)
    }

    toastIcon() {
        switch(this.getAttribute('type')) {
            case 'primary':
                return '<i class="fa fa-info-circle text-primary fa-lg"></i>'
            case 'danger':
                return '<i class="fa fa-exclamation-circle text-danger fa-lg"></i>'
            case 'warning':
                return '<i class="fa fa-exclamation-triangle text-warning fa-lg"></i>'
            default:
                return '<i class="fa fa-check-circle text-success fa-lg"></i>'
        }
    }

    connectedCallback() {
        let element = document.createElement('div')
        element.id = 'alert-toast'
        element.classList.add('fade')
        element.innerHTML = `
            <div class="modal-dialog position-absolute shadow-sm rounded" style="top: -0.3em; right: .8em">
                <div class="modal-content">
                    <div class="position-relative bg-${this.getAttribute('type')} rounded-top" style="padding: .13em 0"></div>

                    <div class="modal-body d-flex justify-content-around align-items-start">
                        <div style="font-size: 1.5rem">${this.toastIcon()}</div>

                        <div class="d-flex flex-column px-3">
                            <div class="toast-header border-0 pl-0 text-${this.getAttribute('type')}">${this.getAttribute('title')}</div>
                            <p class="modal-title">${this.getAttribute('message')}</p>
                        </div>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        `

        document.body.appendChild(element)

        $('#alert-toast').modal({
            backdrop: false,
            keyboard: false,
            show: true
        })

        $('#alert-toast').on('shown.bs.modal', function (e) {
            window.setTimeout(function () {
                $('#alert-toast').modal('hide');
            }, 2500)
        })
    }
}

export default AlertToast