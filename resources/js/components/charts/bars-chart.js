class BarsChart extends HTMLElement {
    constructor() {
        super()

        this.displayChart = this.displayChart.bind(this)
    }

    displayChart(data, xkey) {
        this.innerHTML = '<div id="users-bars" style="height: 200px"></div>'
        
        new Morris.Bar({
            element: 'users-bars',
            resize: true,
            data: data,
            xkey: xkey,
            ykeys: JSON.parse(this.getAttribute('ykeys')),
            labels: JSON.parse(this.getAttribute('labels'))
        })
    }
    
    connectedCallback() {
        this.displayChart(JSON.parse(this.getAttribute('data')), this.getAttribute('xkey'))
    }

    static get observedAttributes() {
        return ['data', 'xkey']
    }

    attributeChangedCallback(name, oldValue, newValue) {
        this.displayChart(JSON.parse(this.getAttribute('data')), this.getAttribute('xkey'))
    }
}

export default BarsChart