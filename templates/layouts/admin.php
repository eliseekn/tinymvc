<!DOCTYPE html>
<html lang="en-US">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= $page_description ?>">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" href="<?= absolute_url('/public/assets/css/main.css') ?>">
	<title><?= $page_title ?></title>
</head>

<body>
    <div class="container my-5">
        <h1 class="mb-3 text-center">Administration</h1>
        
        <div class="text-center py-3">
            <a href="<?= absolute_url('/admin/posts') ?>" class="btn btn-lg btn-dark">Posts</a>
            <a href="<?= absolute_url('/admin/comments') ?>" class="btn btn-lg btn-dark mx-2">Comments</a>
            <a href="<?= absolute_url('/') ?>" class="btn btn-lg btn-dark mr-2">Go back home</a>
            <a href="<?= absolute_url('/user/logout') ?>" class="btn btn-lg btn-dark">Logout</a>
        </div>

        <div class="card shadow mt-4 p-5">

            <?= $this->section('page_content') ?>

        </div>
    </div>

    <div id="add-post" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add post</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="post" action="<?= absolute_url('/post/add') ?>" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="title" 
                                placeholder="Post title" 
                                class="form-control" 
                                required>
                        </div>

                        <div class="form-group">
                            <textarea 
                                name="content" 
                                rows="5" 
                                placeholder="Post content" 
                                class="form-control" 
                                required></textarea>
                        </div>

                        <input type="file" name="image" class="form-control-file" required>
                    </div>
                
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-dark" value="Add">
                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="edit-post-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit post</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="edit-post-form" data-post-id="">
                    <div class="modal-body">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="title" 
                                id="title" 
                                placeholder="Post title" 
                                class="form-control" 
                                required>
                        </div>

                        <div class="form-group">
                            <textarea 
                                name="content" 
                                id="content" 
                                rows="5" 
                                placeholder="Post content" 
                                class="form-control" 
                                required></textarea>
                        </div>
                    </div>
                
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-dark" value="Edit">
                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="replace-image-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Replace featured image</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="replace-image-form" data-post-id="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input 
                            type="file" 
                            id="image" 
                            name="image" 
                            class="form-control-file" 
                            required>
                    </div>
                
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-dark" value="Replace">
                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script defer src="https://use.fontawesome.com/releases/v5.13.0/js/all.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="<?= absolute_url('/public/assets/js/app.js') ?>"></script>
</body>

</html>