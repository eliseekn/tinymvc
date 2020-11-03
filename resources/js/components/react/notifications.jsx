import React from 'react'

//new notifications alert icon
const Icon = (props) => {
    return props.count > 0 ? <span className="bg-danger notifications-icon"></span> : ''
}

//single notification item
const Item = (props) => {
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
            items: [],
            title: '',
            markAsRead: '',
            viewAll: ''
        }

        this.getNotifications = this.getNotifications.bind(this)
    }

    getNotifications() {
        fetch('/tinymvc/api/notifications')
            .then(response => response.json())
            .then(data => this.setState({ 
                items: data.items,
                title: data.title,
                viewAll: data.view_all
            }))
    }

    componentDidMount() {
        this.getNotifications()
        this.intervalID = window.setInterval(() => this.getNotifications(), 30 * 1000) //every 30 seconds
    }

    componentWillUnmount() {
        clearInterval(this.intervalID);
    }

    render() {
        return (
            <div className="dropdown">
                <button className="btn" type="button" id="dropdown-notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title={this.state.title}>
                    <i className="fa fa-bell fa-lg"></i>
                    
                    <Icon count={this.state.items.length} />
                </button>

                <div className="dropdown-menu dropdown-menu-right py-0" aria-labelledby="dropdown-notifications" style={{ zIndex: 1111 }}>
                    <p className="font-weight-bold px-4 py-2">
                        {this.state.title} ({this.state.items.length})
                    </p>

                    <div className="dropdown-divider my-0"></div>

                    {
                        this.state.items.map(item => {
                            return (
                                <Item
                                    key={item.id}
                                    id={item.id}
                                    message={item.message}
                                    markAsRead={this.state.markAsRead}
                                    createdAt={item.created_at}
                                    handleSubmit={this.handleSubmit} />
                            )
                        })
                    }

                    <div className="dropdown-divider my-0"></div>

                    <div className="px-4 py-2 bg-light">
                        <a className="text-primary" href="/tinymvc/admin/notifications">
                            {this.state.viewAll}
                        </a>
                    </div>
                </div>
            </div>
        )
    }
}

export default Notifications