//dashboard url
var dashboard = "http://127.0.0.1:8000/"

//post data with fetch api
async function postData(url, data = null) {
	const request = await fetch(url, {
		method: 'post',
		body: data
	})

	try {
		const response = await request.json()
		return response
	} catch (error) {
		//console.log(error)
	}
}

document.addEventListener('DOMContentLoaded', () => {
	//manage posts
	if (document.querySelectorAll('#remove-post')) {
		document.querySelectorAll('#remove-post').forEach(element => {
			element.addEventListener('click', event => {
				event.preventDefault()

				if (window.confirm('Are you sure you want to delete this post?')) {
					window.location.href = dashboard + 'post/delete/' + event.target.dataset.postId
				}
			})
		})
	}

	if (document.querySelectorAll('#edit-post')) {
		document.querySelectorAll('#edit-post').forEach(element => {
			element.addEventListener('click', event => {
				event.preventDefault()

				document.querySelector('#edit-post-form').dataset.postId = event.target.dataset.postId
				document.querySelector('#edit-post-form #title').value = event.target.dataset.postTitle
				document.querySelector('#edit-post-form #content').value = event.target.dataset.postContent

				$('#edit-post-modal').modal('show')
			})
		})
	}

	if (document.querySelector('#edit-post-form')) {
		document.querySelector('#edit-post-form').addEventListener('submit', event => {
			event.preventDefault()

			postData(dashboard + 'post/edit/' + event.target.dataset.postId, new FormData(event.target))
				.then(() => {
					window.location.reload()
				})
		})
	}

	if (document.querySelectorAll('#replace-image')) {
		document.querySelectorAll('#replace-image').forEach(element => {
			element.addEventListener('click', event => {
				event.preventDefault()
				document.querySelector('#replace-image-form').dataset.postId = event.target.dataset.postId
				$('#replace-image-modal').modal('show')
			})
		})
	}

	if (document.querySelector('#replace-image-form')) {
		document.querySelector('#replace-image-form').addEventListener('submit', event => {
			event.preventDefault()

			postData(dashboard + 'post/replaceImage/' + event.target.dataset.postId, new FormData(event.target))
				.then(() => {
					window.location.reload()
				})
		})
	}

	//manage comments
	if (document.querySelectorAll('#remove-comment')) {
		document.querySelectorAll('#remove-comment').forEach(element => {
			element.addEventListener('click', event => {
				event.preventDefault()

				if (window.confirm('Are you sure you want to delete this comment?')) {
					window.location.href = dashboard + 'comment/delete/' + event.target.dataset.commentId
				}
			})
		})
	}
})