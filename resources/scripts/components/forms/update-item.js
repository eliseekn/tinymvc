class UpdateItem extends HTMLElement {
    constructor() {
        super()

        this.isDisabled = this.isDisabled.bind(this)
    }

    connectedCallback() {
        this.innerHTML = `
            <form method="post" action="${this.getAttribute('action')}" class="d-inline-block">
                <input type="hidden" name="csrf_token" value="${document.querySelector('meta[name="csrf_token"]').content}">
                <input type="hidden" name="request_method" value="patch">

                ${this.innerHTML}
            </form>
        `

        this.isDisabled()
    }

    isDisabled() {
        if (this.getAttribute('disabled')) {
            this.firstElementChild.querySelector('button[type=submit]').classList.add('disabled')
            this.firstElementChild.querySelector('button[type=submit]').setAttribute('type', 'button')
        }
    }
}

export default UpdateItem
