/**
 * affiche une alerte de type toast
 *
 * @class AlertToast
 * @constructor
 */
 class AlertToast extends HTMLElement {
    constructor() {
        super()

        this.toastIcon = this.toastIcon.bind(this)
    }

    /**
     * génère l'icône pour la fenêtre
     *
     * @return
     */
    toastIcon() {
        switch(this.getAttribute('type')) {
            case 'primary':
                return '<i class="fa fa-info-circle text-primary fa-lg"></i>'
            case 'danger':
                return '<i class="fa fa-times-circle text-danger fa-lg"></i>'
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
            <div class="modal-dialog position-absolute shadow-sm rounded" style="top: -0.3em; right: .8em; z-index: 11111">
                <div class="modal-content">
                    <div class="modal-body d-flex justify-content-around align-items-center">
                        <div style="font-size: 1.5rem">${this.toastIcon()}</div>

                        <div class="d-flex flex-column px-3">
                            <p class="modal-title mb-0">${this.getAttribute('message')}</p>
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
            }, 4000)
        })

        $('#alert-toast').on('hidden.bs.modal', function (e) {
            document.body.removeChild(element)
        })
    }
}

export default AlertToast