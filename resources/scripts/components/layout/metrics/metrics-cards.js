class MetricsCards extends HTMLElement {
    constructor() {
        super()
    }

    connectedCallback() {
        this.classList.add('row', 'mb-md-4', 'mb-0')
    }
}

export default MetricsCards