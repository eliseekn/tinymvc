import './components/spinner-button'
import './components/password-toggler'
import AlertToast from './components/alert-toast'

window.customElements.define('alert-toast', AlertToast)

window.addEventListener('beforeunload', () => {
    document.body.className = 'page-loading'
})
