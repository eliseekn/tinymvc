document.addEventListener('DOMContentLoaded', () => {
    //users metrics data
    if (document.querySelector('#users-chart-period')) {
        document.querySelector('#users-chart-period').addEventListener('change', event => {
            fetch(process.env.APP_URL + 'api/metrics/users/' + event.target.value)
                .then(response => response.json())
                .then(data => {
                    document.querySelector('#users-chart').setAttribute('data', data.metrics)

                    if (event.target.value === 'today' || event.target.value === 'week') {
                        document.querySelector('#users-chart').setAttribute('xkey', 'day')
                    } else {
                        document.querySelector('#users-chart').setAttribute('xkey', event.target.value)
                    }
                })
        })
    }

    //incomes metrics trends
    if (document.querySelector('#incomes-chart-period')) {
        document.querySelector('#incomes-chart-period').addEventListener('change', event => {
            fetch(process.env.APP_URL + 'api/metrics/invoices/' + event.target.value)
                .then(response => response.json())
                .then(data => {
                    document.querySelector('#incomes-chart').setAttribute('data', data.metrics)

                    if (event.target.value === 'today' || event.target.value === 'week') {
                        document.querySelector('#incomes-chart').setAttribute('xkey', 'day')
                    } else {
                        document.querySelector('#incomes-chart').setAttribute('xkey', event.target.value)
                    }
                })
        })
    }
})
