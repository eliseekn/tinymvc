import './components/spinner-button'
import './components/password-toggle'
import './components/display-timeout'

window.addEventListener('beforeunload', () => {
    document.body.className = 'page-loading'
})
