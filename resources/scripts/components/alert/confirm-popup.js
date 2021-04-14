/**
 * affiche une alerte de type popup
 *
 * @class ConfirmPopup
 * @constructor
 */
 class ConfirmPopup extends HTMLElement {
    constructor() {
        super()
    }

    connectedCallback() {
        let element = document.createElement('div')
        element.id = 'confirm-popup'
        element.setAttribute('tabindex', '-1')
        element.setAttribute('role', 'dialog')
        element.classList.add('modal', 'fade')
        element.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <i class="fa fa-question-circle text-warning fa-4x"></i>
                        <p class="modal-title my-2">${this.getAttribute('message')}</p>
                        <button type="button" id="yes-button" class="btn btn-warning mr-2" data-dismiss="modal">Yes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        `

        document.body.appendChild(element)

        $('#confirm-popup').modal({
            show: true
        })

        $('#confirm-popup').on('hidden.bs.modal', function (e) {
            document.body.removeChild(element)
        })
    }
}

export default ConfirmPopup