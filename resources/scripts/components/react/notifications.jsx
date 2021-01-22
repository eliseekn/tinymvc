import React from 'react'

//new notifications alert icon
const Icon = (props) => {
    return props.count > 0 ? <span className="bg-danger notifications-icon"></span> : ''
}

//single notification item
const Notification = (props) => {
    return (
        <div className="dropdown-item py-2" style={{ width: '350px' }}>
            <p className="text-wrap">{props.message}</p>
            <span className="small text-muted">{props.createdAt}</span>
        </div>
    )
}

//display notifications dynamically
class Notifications extends React.Component {
    constructor() {
        super()

        this.state = {
            notifications: [],
            translations: {}
        }

        this.getTranslations = this.getTranslations.bind(this)
        this.getNotifications = this.getNotifications.bind(this)
    }

    getNotifications() {
        fetch('/tinymvc/api/notifications')
            .then(response => response.json())
            .then(data => this.setState({ 
                notifications: data.notifications,
            }))
    }

    getTranslations() {
        fetch('/tinymvc/api/translations')
            .then(response => response.json())
            .then(data => this.setState({
                translations: data.translations
            }))
    }

    componentDidMount() {
        this.getTranslations()
        this.getNotifications()
        this.intervalID = window.setInterval(() => this.getNotifications(), 10 * 1000) //every 10 seconds
    }

    componentWillUnmount() {
        clearInterval(this.intervalID);
    }

    render() {
        return (
            <div className="dropdown">
                <button className="btn btn-sm" type="button" id="dropdown-notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title={this.state.translations.notifications}>
                    <i className="fa fa-bell fa-lg"></i>
                    
                    <Icon count={this.state.notifications.length} />
                </button>

                <div className="dropdown-menu dropdown-menu-right py-0" aria-labelledby="dropdown-notifications" style={{ zIndex: 1111 }}>
                    <p className="font-weight-bold px-4 py-2 text-center">
                        {this.state.translations.notifications} ({this.state.notifications.length})
                    </p>

                    <div className="dropdown-divider my-0"></div>

                    {
                        this.state.notifications.map(notification => {
                            return (
                                <Notification
                                    key={notification.id}
                                    id={notification.id}
                                    message={notification.message}
                                    createdAt={notification.created_at} />
                            )
                        })
                    }

                    <div className="dropdown-divider my-0"></div>

                    <div className="px-4 py-2 bg-light text-center">
                        <a className="text-primary" href="/tinymvc/admin/account/notifications">
                            {this.state.translations.view_all}
                        </a>
                    </div>
                </div>
            </div>
        )
    }
}

export default Notifications