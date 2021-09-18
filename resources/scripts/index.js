import '../vendor/bootstrap-5.1.1-dist/bootstrap.bundle.min.js'
import './components/spinner-button'
import './components/password-toggler'
import AlertToast from './components/alert-toast'

window.customElements.define('alert-toast', AlertToast)

window.addEventListener('beforeunload', () => {
    document.body.className = 'page-loading'
})
