import './components/admin'
import './components/loading-button'
import './components/password-toggler'
import UploadModal from './components/modal/upload-modal'
import ExportModal from './components/modal/export-modal'
import AlertPopup from './components/alert/alert-popup'
import AlertToast from './components/alert/alert-toast'
import ConfirmDelete from './components/mixed/confirm-delete'
import TextEditor from './components/mixed/text-editor'
import TimezonePicker from './components/mixed/timezone-picker'
import CurrencyPicker from './components/mixed/currency-picker'
import DonutChart from './components/charts/donut-chart'
import BarsChart from './components/charts/bars-chart'
import LinesChart from './components/charts/lines-chart'
import ThemeSwitch from './components/mixed/theme-switch'

window.customElements.define('upload-modal', UploadModal)
window.customElements.define('export-modal', ExportModal)
window.customElements.define('alert-popup', AlertPopup)
window.customElements.define('alert-toast', AlertToast)
window.customElements.define('confirm-delete', ConfirmDelete)
window.customElements.define('text-editor', TextEditor)
window.customElements.define('donut-chart', DonutChart)
window.customElements.define('bars-chart', BarsChart)
window.customElements.define('lines-chart', LinesChart)
window.customElements.define('timezone-picker', TimezonePicker)
window.customElements.define('currency-picker', CurrencyPicker)
window.customElements.define('theme-switch', ThemeSwitch)
