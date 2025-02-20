import './components/spinner-button'
import './components/password-toggle'

window.addEventListener('beforeunload', () => {
    document.body.className = 'page-loading'
})
