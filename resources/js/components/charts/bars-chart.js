class BarsChart extends HTMLElement {
    constructor() {
        super()
    }

    connectedCallback() {
        new Morris.Bar({
            element: this.getAttribute('el'),
            resize: true,
            data: JSON.parse(this.getAttribute('data')),
            xkey: this.getAttribute('xkey'),
            ykeys: JSON.parse(this.getAttribute('ykeys')),
            labels: JSON.parse(this.getAttribute('labels'))
        })
    }
}

export default BarsChart