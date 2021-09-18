/**
 * display toast alert
 *
 * @class AlertToast
 * @constructor
 */
class AlertToast extends HTMLElement {
    constructor() {
        super()
        this.icon = this.icon.bind(this)
    }

    icon() {
        switch(this.getAttribute('type')) {
            case 'primary': return '<i class="fa fs-4 fa-info-circle text-primary"></i>'
            case 'danger': return '<i class="fa fs-4 fa-times-circle text-danger"></i>'
            case 'warning': return '<i class="fa fs-4 fa-exclamation-triangle text-warning"></i>'
            default: return '<i class="fa fs-4 fa-check-circle text-success"></i>'
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
                        ${this.icon()}

                        <div class="d-flex flex-column px-2">
                            <p class="modal-title mb-0">${this.getAttribute('message')}</p>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        `

        document.body.appendChild(element)

        let alert = new bootstrap.Modal(element, {
            backdrop: false,
            keyboard: false,
        })

        element.addEventListener('shown.bs.modal', () => {
            window.setTimeout(() => {
                alert.hide()
            }, 3500)
        })

        element.addEventListener('hidden.bs.modal', () => {
            document.body.removeChild(element)
        })
          
        alert.show()
    }
}

export default AlertToast