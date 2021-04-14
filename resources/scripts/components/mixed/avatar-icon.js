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
        const fullName = this.getAttribute('name')
        const firstName = fullName.split(' ')

        if (firstName.length === 0) {
            return fullName
        } else {
            return firstName[0]
        }
    }

    getFirstLetter() {
        return this.getName()[0].toUpperCase()
    }
}

export default AvatarIcon
