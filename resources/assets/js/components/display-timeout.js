document.addEventListener('DOMContentLoaded', () => {
    document
        .querySelectorAll('[data-timeout]')
        ?.forEach(el => {
            const timeout = parseInt(el.dataset.timeout)

            setTimeout(function () {
                el.classList.add('d-none')
            }, timeout);
        })
})
