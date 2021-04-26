/**
 * display morris donut chart
 *
 * @class DonutChart
 * @constructor
 */
class DonutChart extends HTMLElement {
    constructor() {
        super()
        this.translations = {}
        this.getTranslations = this.getTranslations.bind(this)
        this.displayData = this.displayData.bind(this)
    }

    getTranslations() {
        fetch(process.env.APP_URL + process.env.APP_FOLDER + '/api/translations')
            .then(response => response.json())
            .then(data => {
                this.translations = data.translations
                this.displayData()
            })
    }

    displayData() {
        if (JSON.parse(this.getAttribute('data')).length == 0) {
            this.innerHTML = `
                <div class="d-flex justify-content-center align-items-center" style="height: 200px">
                    ${this.translations.no_data_found}
                </div>
            ` 
        }
        
        else {
            this.innerHTML = `<div id="${this.getAttribute('el')}" style="height: 200px"></div>`
        
            new Morris.Donut({
                element: this.getAttribute('el'),
                resize: true,
                data: JSON.parse(this.getAttribute('data'))
            })
        }
    }

    connectedCallback() {
        this.getTranslations()
        this.displayData()
    }
}

export default DonutChart