class DonutChart extends HTMLElement {
    constructor() {
        super()
    }

    connectedCallback() {
        new Morris.Donut({
            element: this.getAttribute('el'),
            resize: true,
            data: JSON.parse(this.getAttribute('data'))
        })
    }
}

export default DonutChart