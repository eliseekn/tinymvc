class TextEditor extends HTMLElement {
    constructor() {
        super()

        document.querySelector(this.getAttribute('form')).addEventListener('submit', e => {
            e.preventDefault()
            
            let formData = new FormData(document.querySelector(this.getAttribute('form')))
            formData.append('editor', this.editor.root.innerHTML)

            fetch(document.querySelector(this.getAttribute('form')).dataset.url, {
                method: 'post',
                body: formData
            })
            .then(response => response.json())
            .then(data => window.location.href = data.redirect)
        })
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