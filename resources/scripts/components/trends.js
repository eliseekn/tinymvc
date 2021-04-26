document.addEventListener('DOMContentLoaded', () => {
    //users metrics trends
    if (document.querySelector('#users-trends')) {
        document.querySelector('#users-trends').addEventListener('change', event => {
            if (event.target.value === 'last-years') {
                fetch(process.env.APP_URL + process.env.APP_FOLDER + '/years/5')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('#users-bars-chart').setAttribute('data', data.metrics)
                        document.querySelector('#users-bars-chart').setAttribute('xkey', 'year')
                    })
            }
            
            else if (event.target.value === 'last-weeks') {
                fetch(process.env.APP_URL + process.env.APP_FOLDER + '/weeks/4')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('#users-bars-chart').setAttribute('data', data.metrics)
                        document.querySelector('#users-bars-chart').setAttribute('xkey', 'day')
                    })
            }

            else {
                fetch(process.env.APP_URL + process.env.APP_FOLDER + '/' + event.target.value)
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