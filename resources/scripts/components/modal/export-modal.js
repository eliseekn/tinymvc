/**
 * display export data popup
 *
 * @class ExportModal
 * @constructor
 */
class ExportModal extends HTMLElement {
    constructor() {
        super()
        this.translations = {}
        this.getTranslations = this.getTranslations.bind(this)
        this.showDialog = this.showDialog.bind(this)
        this.addEventListener('click', this.showDialog)
    }

    getTranslations() {
        fetch(process.env.APP_URL + 'api/translations')
            .then(response => response.json())
            .then(data => this.translations = data.translations)
    }

    connectedCallback() {
        this.getTranslations()
    }

    showDialog() {
        let element = document.createElement('div')
        element.id = 'export-modal'
        element.setAttribute('tabindex', '-1')
        element.setAttribute('role', 'dialog')
        element.classList.add('modal', 'fade')
        element.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light text-dark align-items-center py-2">
                        <h5 class="modal-title">${this.translations.export}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" action="${this.getAttribute('action')}">
                        <input type="hidden" name="csrf_token" value="${document.querySelector('meta[name="csrf_token"]').content}">

                        <div class="modal-body">
                            <fieldset class="form-group">
                                <div class="row">
                                    <legend class="col-form-label col-sm-4 pt-0">${this.translations.file_type}</legend>

                                    <div class="col-sm-8">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" type="radio" name="file_type" id="csv" value="csv" checked>
                                            <label class="custom-control-label" for="csv">CSV</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" type="radio" name="file_type" id="pdf" value="pdf">
                                            <label class="custom-control-label" for="pdf">PDF</label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="form-group row">
                                <p class="col-sm-4 col-form-label">${this.translations.period} <small>(${this.translations.optional})</small></p>

                                <div class="col-sm-8">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>

                                        <input type="date" class="form-control" name="date_start" id="date_start">
                                    </div>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>

                                        <input type="date" class="form-control" name="date_end" id="date_end">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark">${this.translations.submit}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">${this.translations.cancel}</button>
                        </div>
                    </form>
                </div>
            </div>
        `

        document.body.appendChild(element)

        $('#export-modal').modal({ show: true })
        $('#export-modal').on('hidden.bs.modal', function (e) { document.body.removeChild(element) })
    }

    disconnectedCallback() {
        this.removeEventListener('click')
    }
}

export default ExportModal