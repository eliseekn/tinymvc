/**
 * display morris line chart
 *
 * @class LineChart
 * @constructor
 */
class LineChart extends HTMLElement {
    constructor() {
        super()
        this.translations = {}
        this.getTranslations = this.getTranslations.bind(this)
        this.setDefaultInnerHTML = this.setDefaultInnerHTML.bind(this)
        this.drawChart = this.drawChart.bind(this)
        this.displayData = this.displayData.bind(this)
    }

    getTranslations() {
        fetch(process.env.APP_URL + 'api/translations')
            .then(response => response.json())
            .then(data => {
                this.translations = data.translations
                this.displayData()
            })
    }

    setDefaultInnerHTML() {
        this.innerHTML = `
            <div class="d-flex justify-content-center align-items-center" style="height: 230px">
                ${this.translations.no_data_found}
            </div>
        ` 
    }

    drawChart(data, xkey) {
        this.innerHTML = `<div id="${this.getAttribute('el')}" style="height: 230px"></div>`

        new Morris.Line({
            element: this.getAttribute('el'),
            resize: true,
            data: data,
            xkey: xkey,
            ykeys: JSON.parse(this.getAttribute('ykeys')),
            labels: JSON.parse(this.getAttribute('labels')),
        })
    }

    displayData() {
        if (JSON.parse(this.getAttribute('data')).length == 0) {
            this.setDefaultInnerHTML()
        } else {
            this.drawChart(JSON.parse(this.getAttribute('data')), this.getAttribute('xkey'))
        }
    }
    
    connectedCallback() {
        this.getTranslations()
        this.displayData()
    }

    static get observedAttributes() {
        return ['data', 'xkey']
    }

    attributeChangedCallback(name, oldValue, newValue) {
        this.displayData()
    }
}

export default LineChart