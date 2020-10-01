import UploadModal from './components/upload-modal'
import AlertModal from './components/alert-modal'
import AlertToast from './components/alert-toast'
import ConfirmDelete from './components/confirm-delete'
import ExportModal from './components/export-modal'
import './components/loading-button'
import './components/password-toggler'
import './components/admin'

window.customElements.define('upload-modal', UploadModal)
window.customElements.define('alert-modal', AlertModal)
window.customElements.define('alert-toast', AlertToast)
window.customElements.define('confirm-delete', ConfirmDelete)
window.customElements.define('export-modal', ExportModal)
