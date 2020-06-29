//display confirm delete message and redirect
function confirmDelete(message, redirect) {
    if (window.confirm(message)) {
        window.location.href = redirect
    }
}