document.addEventListener('DOMContentLoaded', () => {
    //users metrics trends
    if (document.querySelector('#users-trends')) {
        document.querySelector('#users-trends').addEventListener('change', event => {
            if (event.target.value === 'last-years') {
                fetch('/api/metrics/users/id/count/years/5')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('#users-bars-chart').setAttribute('data', data.metrics)
                        document.querySelector('#users-bars-chart').setAttribute('xkey', 'year')
                    })
            }
            
            else if (event.target.value === 'last-weeks') {
                fetch('/api/metrics/users/id/count/weeks/4')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('#users-bars-chart').setAttribute('data', data.metrics)
                        document.querySelector('#users-bars-chart').setAttribute('xkey', 'day')
                    })
            }

            else {
                fetch('/api/metrics/users/id/count/' + event.target.value)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('#users-bars-chart').setAttribute('data', data.metrics)
                        event.target.value === 'weeks'
                            ? document.querySelector('#users-bars-chart').setAttribute('xkey', 'day')
                            : document.querySelector('#users-bars-chart').setAttribute('xkey', 'month')
                    })
            }
        })
    }

    //incomes metrics trends
    if (document.querySelector('#incomes-trends')) {
        document.querySelector('#incomes-trends').addEventListener('change', event => {
            if (event.target.value === 'last-years') {
                fetch('/api/metrics/invoices/total_price/sum/years/5')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('#incomes-bars-chart').setAttribute('data', data.metrics)
                        document.querySelector('#incomes-bars-chart').setAttribute('xkey', 'year')
                    })
            }
            
            else if (event.target.value === 'last-weeks') {
                fetch('/api/metrics/invoices/total_price/sum/weeks/4')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('#incomes-bars-chart').setAttribute('data', data.metrics)
                        document.querySelector('#incomes-bars-chart').setAttribute('xkey', 'day')
                    })
            }

            else {
                fetch('/api/metrics/invoices/total_price/sum/' + event.target.value)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('#incomes-bars-chart').setAttribute('data', data.metrics)
                        event.target.value === 'weeks'
                            ? document.querySelector('#incomes-bars-chart').setAttribute('xkey', 'day')
                            : document.querySelector('#incomes-bars-chart').setAttribute('xkey', 'month')
                    })
            }
        })
    }
})