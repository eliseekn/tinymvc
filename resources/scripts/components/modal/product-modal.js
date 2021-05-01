/**
 * display add product popup
 *
 * @class ProductModal
 * @constructor
 */
class ProductModal extends HTMLElement {
    constructor() {
        super()
        this.translations = {}
        this.getTranslations = this.getTranslations.bind(this)
        this.showDialog = this.showDialog.bind(this)
        this.addEventListener('click', this.showDialog)
    }

    getTranslations() {
        fetch('/api/translations')
            .then(response => response.json())
            .then(data => this.translations = data.translations)
    }

    addProduct() {
        if (
            document.querySelector('#product-name').value === '' ||
            document.querySelector('#product-quantity').value === '' ||
            document.querySelector('#product-price').value === ''
        ) {
            return
        }

        let sep = document.querySelector('#products').value === '' ? '' : ','

        document.querySelector('#products').value += sep + JSON.stringify({
            name: document.querySelector('#product-name').value,
            quantity: document.querySelector('#product-quantity').value,
            price: document.querySelector('#product-price').value
        })
    }

    connectedCallback() {
        this.getTranslations()
    }

    showDialog() {
        let element = document.createElement('div')
        element.id = 'product-modal'
        element.setAttribute('tabindex', '-1')
        element.setAttribute('role', 'dialog')
        element.classList.add('modal', 'fade')
        element.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light text-dark align-items-center py-2">
                        <h5 class="modal-title">${this.translations.add_product}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="product-name">${this.translations.name}</label>
                            <input type="text" id="product-name" name="product-name" class="form-control" autofocus>
                        </div>

                        <div class="form-group">
                            <label for="product-quantity">${this.translations.quantity}</label>
                            <input type="number" id="product-quantity" name="product-quantity" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="product-price">${this.translations.price}</label>
                            <input type="number" id="product-price" name="product-price" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" id="add-product" data-dismiss="modal">${this.translations.add}</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">${this.translations.cancel}</button>
                    </div>
                </div>
            </div>
        `

        document.body.appendChild(element)
        element.querySelector('#add-product').addEventListener('click', this.addProduct)

        $('#product-modal').modal({ show: true })

        $('#product-modal').on('hidden.bs.modal', function (e) {
            element.removeEventListener('click', element)
            document.body.removeChild(element)
        })
    }

    disconnectedCallback() {
        this.removeEventListener('click')
    }
}

export default ProductModal