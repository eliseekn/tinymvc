class MetricCardItem extends HTMLElement {
    constructor() {
        super()
    }

    connectedCallback() {
        this.innerHTML = `
            <div class="card card-metrics bg-light shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <i class="${this.getAttribute('icon')}"></i>

                        <div class="text-right">
                            <p class="font-weight-bold" style="font-size: 1.2rem">${this.getAttribute('data')}</p>
                            <p class="font-weight-bold"> ${this.getAttribute('title')}</p>
                        </div>
                    </div>
                </div>
            </div>
        `

        this.classList.add('col-md-' + this.getAttribute('columns'), 'mb-md-0', 'mb-4')
    }
}

export default MetricCardItem