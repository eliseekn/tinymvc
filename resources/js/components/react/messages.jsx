import React from 'react'
import '../mixed/avatar-icon'

//new message alert icon
const Icon = (props) => {
    return props.count > 0 ? <span className="bg-danger notifications-icon"></span> : ''
}

//single message item
const Message = (props) => {
    return (
        <div className="dropdown-item py-2" style={{ width: '350px' }}>
            <p className="text-wrap">
                <avatar-icon name={props.sender_name + '(' + props.sender_email + ')'}></avatar-icon>
                <span>{props.message}</span>
            </p>
            <span className="small text-muted">{props.createdAt}</span>
        </div>
    )
}

//display messages dynamically
class Messages extends React.Component {
    constructor() {
        super()

        this.state = {
            messages: [],
            title: '',
            markAsRead: '',
            viewAll: ''
        }

        this.getMessages = this.getMessages.bind(this)
    }

    getMessages() {
        fetch('/tinymvc/api/messages')
            .then(response => response.json())
            .then(data => this.setState({ 
                messages: data.messages,
                title: data.title,
                viewAll: data.view_all
            }))
    }

    componentDidMount() {
        this.getMessages()
        this.intervalID = window.setInterval(() => this.getMessages(), 10 * 1000) //every 10 seconds
    }

    componentWillUnmount() {
        clearInterval(this.intervalID);
    }

    render() {
        return (
            <div className="dropdown">
                <button className="btn btn-sm" type="button" id="dropdown-messages" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title={this.state.title}>
                    <i className="fa fa-envelope fa-lg"></i>
                    
                    <Icon count={this.state.messages.length} />
                </button>

                <div className="dropdown-menu dropdown-menu-right py-0" aria-labelledby="dropdown-messages" style={{ zIndex: 1111 }}>
                    <p className="font-weight-bold px-4 py-2 text-center">
                        {this.state.title} ({this.state.messages.length})
                    </p>

                    <div className="dropdown-divider my-0"></div>

                    {
                        this.state.messages.map(message => {
                            return (
                                <Message
                                    key={message.id}
                                    id={message.id}
                                    sender_email={message.sender_email}
                                    sender_name={message.sender_name}
                                    message={message.message}
                                    markAsRead={this.state.markAsRead}
                                    createdAt={message.created_at}
                                    handleSubmit={this.handleSubmit} />
                            )
                        })
                    }

                    <div className="dropdown-divider my-0"></div>

                    <div className="text-center px-4 py-2 bg-light">
                        <a className="text-primary" href="/tinymvc/admin/account/messages">
                            {this.state.viewAll}
                        </a>
                    </div>
                </div>
            </div>
        )
    }
}

export default Messages