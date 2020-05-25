//dashboard url
var dashboard = "http://localhost/tinymvc/demos/blog/dashboard/"

//post data with fetch api
async function postData(url, data = null) {
	const request = await fetch(url, {
		method: 'post',
		body: data
	})

	try {
		const response = await request.json()
		return response
	} catch(error) {
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
					postData(dashboard + 'delete_post/' + event.target.dataset.postId)
						.then(() => {
							window.location.href = dashboard
						})
				}
			})
		})
	}	

	if (document.querySelectorAll('#edit-post')) {
		document.querySelectorAll('#edit-post').forEach(element => {
			element.addEventListener('click', event => {
				event.preventDefault()
				
				document.querySelector('#edit-post-form').dataset.postId = event.target.dataset.postId
				document.querySelector('#edit-post-form #post-title').value = event.target.dataset.postTitle
				document.querySelector('#edit-post-form #post-content').value = event.target.dataset.postContent
	
				$('#edit-post-modal').modal('show')
			})
		})
	}

	if (document.querySelector('#edit-post-form')) {
		document.querySelector('#edit-post-form').addEventListener('submit', event => {
			event.preventDefault()
	
			let formData = new FormData()
			formData.append('post-title', document.querySelector('#edit-post-form #post-title').value)
			formData.append('post-content', document.querySelector('#edit-post-form #post-content').value)
	
			postData(dashboard + 'edit_post/' + event.target.dataset.postId, formData).then(() => {
				window.location.href = dashboard
			})
		})
	}

	if (document.querySelectorAll('#edit-post-image')) {
		document.querySelectorAll('#edit-post-image').forEach(element => {
			element.addEventListener('click', event => {
				event.preventDefault()
				document.querySelector('#edit-post-image-form').dataset.postId = event.target.dataset.postId
				$('#edit-post-image-modal').modal('show')
			})
		})
	}

	if (document.querySelector('#edit-post-image-form')) {
		document.querySelector('#edit-post-image-form').addEventListener('submit', event => {
			event.preventDefault()
	
			let formData = new FormData()
			formData.append('post-image', document.querySelector('#edit-post-image-form #post-image').files[0])
	
			postData(dashboard + 'edit_post_image/' + event.target.dataset.postId, formData).then(() => {
				window.location.href = dashboard
			})
		})
	}

	//manage comments
	if (document.querySelector('#add-comment-form')) {
		document.querySelector('#add-comment-form').addEventListener('submit', event => {
			event.preventDefault()
	
			const postUrl = event.target.getAttribute('action')
	
			let formData = new FormData()
			formData.append('author', document.querySelector('#add-comment-form #comment-author').value)
			formData.append('content', document.querySelector('#add-comment-form #comment-content').value)
			
			postData(dashboard + 'add_comment/' + event.target.dataset.postId, formData).then(() => {
				window.location.href = postUrl
			})
		})
	}

	if (document.querySelectorAll('#remove-comment')) {
		document.querySelectorAll('#remove-comment').forEach(element => {
			element.addEventListener('click', event => {
				event.preventDefault()
		
				if (window.confirm('Are you sure you want to delete this comment?')) {
					postData(dashboard + 'delete_comment/' + event.target.dataset.commentId)
						.then(() => {
							window.location.href = dashboard + 'comments'
						})
				}
			})
		})
	}
})