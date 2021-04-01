class MetricCardItem extends HTMLElement {
    constructor() {
        super()
    }

    connectedCallback() {
        this.innerHTML = `
            <div class="card card-metrics bg-light shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <p><i class="${this.getAttribute('icon')}"></i> ${this.getAttribute('title')}</p>
                    <p class="font-weight-bold">${this.getAttribute('data')}</p>
                </div>
            </div>
        `

        this.classList.add('col-md-' + this.getAttribute('columns'), 'mb-md-0', 'mb-4')
    }
}

export default MetricCardItem