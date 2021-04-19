document.addEventListener('DOMContentLoaded', () => {
    //users metrics trends
    if (document.querySelector('#users-trends')) {
        document.querySelector('#users-trends').addEventListener('change', event => {
            if (event.target.value === 'last-years') {
                fetch(event.target.dataset.url + '/years/5')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('#users-bars-chart').setAttribute('data', data.metrics)
                        document.querySelector('#users-bars-chart').setAttribute('xkey', 'year')
                    })
            }
            
            else if (event.target.value === 'last-weeks') {
                fetch(event.target.dataset.url + '/weeks/4')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('#users-bars-chart').setAttribute('data', data.metrics)
                        document.querySelector('#users-bars-chart').setAttribute('xkey', 'day')
                    })
            }

            else {
                fetch(event.target.dataset.url + '/' + event.target.value)
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
})