class DonutChart extends HTMLElement {
    constructor() {
        super()
    }

    connectedCallback() {
        this.innerHTML = `<div id="${this.getAttribute('el')}" style="height: 200px"></div>`
        
        new Morris.Donut({
            element: this.getAttribute('el'),
            resize: true,
            data: JSON.parse(this.getAttribute('data'))
        })
    }
}

export default DonutChart