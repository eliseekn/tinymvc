import './components/admin'
import './components/sidebar'
import './components/loading-button'
import './components/password-toggler'
import './components/trends'

import UploadModal from './components/modal/upload-modal'
import ExportModal from './components/modal/export-modal'
import SendMessage from './components/modal/send-message'
import TicketMessage from './components/modal/ticket-message'
import AlertPopup from './components/alert/alert-popup'
import AlertToast from './components/alert/alert-toast'
import ConfirmPopup from './components/alert/confirm-popup'
import TextEditor from './components/mixed/text-editor'
import TimezonePicker from './components/mixed/timezone-picker'
import CurrencyPicker from './components/mixed/currency-picker'
import ThemeSwitch from './components/mixed/theme-switch'
import AvatarIcon from './components/mixed/avatar-icon'
import UpdateItem from './components/mixed/update-item'
import DeleteItem from './components/mixed/delete-item'
import NotificationsIcon from './components/mixed/notifications-icon'
import MessagesIcon from './components/mixed/messages-icon'
import DonutChart from './components/charts/donut-chart'
import BarsChart from './components/charts/bars-chart'
import MetricsCard from './components/cards/metrics-card'
import MetricsCardItem from './components/cards/metrics-card-item'

window.customElements.define('upload-modal', UploadModal)
window.customElements.define('export-modal', ExportModal)
window.customElements.define('alert-popup', AlertPopup)
window.customElements.define('alert-toast', AlertToast)
window.customElements.define('confirm-popup', ConfirmPopup)
window.customElements.define('text-editor', TextEditor)
window.customElements.define('donut-chart', DonutChart)
window.customElements.define('bars-chart', BarsChart)
window.customElements.define('timezone-picker', TimezonePicker)
window.customElements.define('currency-picker', CurrencyPicker)
window.customElements.define('theme-switch', ThemeSwitch)
window.customElements.define('avatar-icon', AvatarIcon)
window.customElements.define('send-message', SendMessage)
window.customElements.define('ticket-message', TicketMessage)
window.customElements.define('update-item', UpdateItem)
window.customElements.define('delete-item', DeleteItem)
window.customElements.define('metrics-card', MetricsCard)
window.customElements.define('metrics-card-item', MetricsCardItem)
window.customElements.define('notifications-icon', NotificationsIcon)
window.customElements.define('messages-icon', MessagesIcon)

window.addEventListener('beforeunload', () => {
    document.body.className = 'page-loading'
})
