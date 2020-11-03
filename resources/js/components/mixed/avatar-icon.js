class AvatarIcon extends HTMLElement {
    constructor() {
        super()

        this.getFirstLetter = this.getFirstLetter.bind(this)
    }

    connectedCallback() {
        this.innerHTML = `
            <div class="d-flex align-items-center justify-content-center rounded-circle bg-dark text-light" id="avatar-icon" style="width: 30px; height: 30px">
                <span class="small">${this.getFirstLetter()}</span>
            </div>
        `
    }

    getFirstLetter() {
        const name = this.getAttribute('name')
        const firstLetter = name.split(' ')

        if (firstLetter.length === 0) {
            return name[0].toUpperCase()
        } else {
            return firstLetter[0][0].toUpperCase()
        }
    }
}

export default AvatarIcon