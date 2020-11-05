class AvatarIcon extends HTMLElement {
    constructor() {
        super()

        this.getFirstLetter = this.getFirstLetter.bind(this)
        this.getName = this.getName.bind(this)
    }

    connectedCallback() {
        this.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-dark text-light" id="avatar-icon" style="width: 30px; height: 30px">
                    <span class="small font-weight-bold">${this.getFirstLetter()}</span>
                </div>

                <span class="ml-2">${this.getName()}</span>
            </div>
        `
    }

    getName() {
        const name = this.getAttribute('name')
        const firstLetter = name.split(' ')

        if (firstLetter.length === 0) {
            return name
        } else {
            return firstLetter[0]
        }
    }

    getFirstLetter() {
        console.log(this.getName(), this.getName()[0], this.getName()[0].toUpperCase())
        return this.getName()[0].toUpperCase()
    }
}

export default AvatarIcon
