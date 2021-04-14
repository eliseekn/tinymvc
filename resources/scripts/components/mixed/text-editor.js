class TextEditor extends HTMLElement {
    constructor() {
        super()
        this.submit = this.submit.bind(this)

        let form = document.querySelector(this.getAttribute('form'))
        form.addEventListener('submit', event => this.submit(event))
    }

    submit(event) {
        event.preventDefault()
        
        let formData = new FormData(form)
        formData.append('editor', this.editor.root.innerHTML)

        fetch(form.dataset.url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => window.location.href = data.redirect)
    }

    connectedCallback() {
        this.innerHTML = `<div id="editor">${this.getAttribute('content')}</div>`
        
        //initialize editor
        this.editor = new Quill('#editor', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    ['clean']
                ]
            },
    
            theme: 'snow'
        })

        //customize editor
        document.querySelector('.ql-editor').setAttribute('style', 'font-size: 1rem')
        document.querySelector('.ql-toolbar').classList.add('rounded-top')
        document.querySelector('#editor').classList.add('rounded-bottom')
    }
}

export default TextEditor