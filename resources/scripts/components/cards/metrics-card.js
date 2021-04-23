/**
 * metrics card layout
 * 
 * @class MetricsCard
 * @constructor
 */
class MetricsCard extends HTMLElement {
    constructor() {
        super()
    }

    connectedCallback() {
        this.classList.add('row', 'mb-md-4', 'mb-0')
    }
}

export default MetricsCard