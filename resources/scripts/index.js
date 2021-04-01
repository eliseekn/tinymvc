import './components/admin'
import './components/sidebar'
import './components/loading-button'
import './components/password-toggler'
import './components/trends'

import UploadModal from './components/modal/upload-modal'
import ExportModal from './components/modal/export-modal'
import AlertPopup from './components/alert/alert-popup'
import AlertToast from './components/alert/alert-toast'
import TextEditor from './components/mixed/text-editor'
import TimezonePicker from './components/mixed/timezone-picker'
import CurrencyPicker from './components/mixed/currency-picker'
import DonutChart from './components/charts/donut-chart'
import BarsChart from './components/charts/bars-chart'
import ThemeSwitch from './components/mixed/theme-switch'
import AvatarIcon from './components/mixed/avatar-icon'
import CreateNotification from './components/modal/create-notification'
import SendMessage from './components/modal/send-message'
import UpdateItem from './components/forms/update-item'
import DeleteItem from './components/forms/delete-item'

import MetricsCards from './components/layout/metrics/metrics-cards'
import MetricCardItem from './components/layout/metrics/metric-card-item'

import React from 'react'
import ReactDOM from 'react-dom'
import Notifications from './components/react/notifications'
import Messages from './components/react/messages'

import hjs from '../vendor/hjs/highlight.pack'

window.customElements.define('upload-modal', UploadModal)
window.customElements.define('export-modal', ExportModal)
window.customElements.define('alert-popup', AlertPopup)
window.customElements.define('alert-toast', AlertToast)
window.customElements.define('text-editor', TextEditor)
window.customElements.define('donut-chart', DonutChart)
window.customElements.define('bars-chart', BarsChart)
window.customElements.define('timezone-picker', TimezonePicker)
window.customElements.define('currency-picker', CurrencyPicker)
window.customElements.define('theme-switch', ThemeSwitch)
window.customElements.define('avatar-icon', AvatarIcon)
window.customElements.define('create-notification', CreateNotification)
window.customElements.define('send-message', SendMessage)
window.customElements.define('update-item', UpdateItem)
window.customElements.define('delete-item', DeleteItem)

window.customElements.define('metrics-cards', MetricsCards)
window.customElements.define('metric-card-item', MetricCardItem)

if (document.querySelector('#notifications-bell')) {
    ReactDOM.render(<Notifications />, document.querySelector('#notifications-bell'))
}

if (document.querySelector('#messages-icon')) {
    ReactDOM.render(<Messages />, document.querySelector('#messages-icon'))
}

hjs.initHighlightingOnLoad()

window.addEventListener('beforeunload', () => {
    document.body.className = 'page-loading'
})