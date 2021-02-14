class UpdateItem extends HTMLElement {
    constructor() {
        super()
    }

    connectedCallback() {
        this.innerHTML = `
            <form method="post" action="${this.getAttribute('action')}" class="d-inline-block">
                <input type="hidden" name="csrf_token" value="${document.querySelector('#csrf_token').value}">
                <input type="hidden" name="request_method" value="patch">

                ${this.innerHTML}
            </form>
        `
    }
}

export default UpdateItem