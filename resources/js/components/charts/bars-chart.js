class BarsChart extends HTMLElement {
    constructor() {
        super()

        this.drawChart = this.drawChart.bind(this)
        this.displayData = this.displayData.bind(this)
    }

    drawChart(data, xkey) {
        this.innerHTML = `<div id="${this.getAttribute('el')}" style="height: 200px"></div>`
        
        new Morris.Bar({
            element: this.getAttribute('el'),
            resize: true,
            data: data,
            xkey: xkey,
            ykeys: JSON.parse(this.getAttribute('ykeys')),
            labels: JSON.parse(this.getAttribute('labels'))
        })
    }

    displayData() {
        if (JSON.parse(this.getAttribute('data')).length == 0) {
            this.innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height: 200px">No data found</div>'
        } else {
            this.drawChart(JSON.parse(this.getAttribute('data')), this.getAttribute('xkey'))
        }
    }
    
    connectedCallback() {
        this.displayData()
    }

    static get observedAttributes() {
        return ['data', 'xkey']
    }

    attributeChangedCallback(name, oldValue, newValue) {
        this.displayData()
    }
}

export default BarsChart