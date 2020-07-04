//display confirm delete message and redirect
function confirmDelete(target, message, redirect) {
    title = target.innerHTML
    target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> ' + title

    if (window.confirm(message)) {
        window.location.href = redirect
    } else {
        target.innerHTML = title
    }
}
