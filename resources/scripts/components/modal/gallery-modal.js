class GalleryModal extends HTMLElement {
    constructor() {
        super()

        this.translations = {}
        this.medias = [];
        this.content = ''

        this.getMedias = this.getMedias.bind(this)
        this.getTranslations = this.getTranslations.bind(this)
        this.showDialog = this.showDialog.bind(this)
        this.addEventListener('click', this.showDialog)
    }

    getTranslations() {
        fetch('/tinymvc/api/translations')
            .then(response => response.json())
            .then(data => this.translations = data.translations)
    }

    getMedias() {
        fetch('/tinymvc/api/medias')
            .then(response => response.json())
            .then(data => {
                console.log(data)
                this.medias = data.medias,
                this.content = data.content
            })
    }

    connectedCallback() {
        this.innerHTML = `<a href="#" class="btn btn-outline-dark mr-2">${this.translations.add_medias}</a>`;
        this.getTranslations()
        this.getMedias()
    }

    showDialog() {
        let element = document.createElement('div')
        element.id = 'upload-modal'
        element.setAttribute('tabindex', '-1')
        element.setAttribute('role', 'dialog')
        element.classList.add('modal', 'fade')
        element.innerHTML = `
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light text-dark align-items-center py-2">
                        <h5 class="modal-title">${this.translations.medias}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    ${this.content}
                </div>
            </div>
        `

        document.body.appendChild(element)

        $('#upload-modal').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        })

        $('#upload-modal').on('hidden.bs.modal', function (e) {
            document.body.removeChild(element)
        })
    }
}

export default GalleryModal
