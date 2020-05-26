<?php $this->layout('layouts/main', [
    'page_title' => $page_title,
    'page_description' => $page_description,
    'header_title' => $header_title,
    'footer_title' => $header_title
]) ?>

<?php $this->start('page_content') ?>

    <section class="container w-75 my-5">
        <article class="card mb-5">
            <img 
                src="<?= absolute_url('/public/assets/img/posts/' . $post->image) ?>" 
                class="card-img-top" 
                alt="Post image">
            
            <div class="card-body">
                <h2 class="card-title post-title"><?= $post->title ?></h2>
                <p class="card-text mt-3 text-justify"><?= $post->content ?></p>
                <a href="<?= absolute_url('/') ?>" class="btn btn-lg btn-dark">Go back home</a>
            </div>
        </article>

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a 
                    class="nav-item nav-link active lead" 
                    id="nav-home-tab" 
                    data-toggle="tab" 
                    href="#nav-home" 
                    role="tab" 
                    aria-controls="nav-home" 
                    aria-selected="true">Comments (<?= count((array) $comments) ?>)</a>

                <a 
                    class="nav-item nav-link lead" 
                    id="nav-profile-tab" 
                    data-toggle="tab" 
                    href="#nav-profile" 
                    role="tab" 
                    aria-controls="nav-profile" 
                    aria-selected="false">Leave a comment</a>
            </div>
        </nav>
        
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="mt-4">
                
                    <?php foreach ($comments as $comment) : ?>

                    <p class="font-weight-bold"><?= $comment->author ?></p>
                    <p><?= $comment->content ?></p>

                    <?php endforeach ?>

                </div>
            </div>

            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="mt-4">

                    <?php if (session_has('flash_messages')) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php
                                $flash_messages = get_flash_messages('flash_messages');

                                foreach ($flash_messages as $flash_message) {
                                    foreach ($flash_message as $error_message) {
                                        echo $error_message . '<br>';
                                    }
                                }
                            ?>
                        </div>
                    <?php } ?>

                    <form method="post" action="<?= absolute_url('/comment/add/' . $post->id) ?>">
                        <div class="form-group">
                            <input 
                                type="email" 
                                name="author" 
                                placeholder="Your email" 
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <textarea 
                                name="content" 
                                rows="5" 
                                placeholder="Your comment" 
                                class="form-control"></textarea>
                        </div>

                        <input type="submit" class="btn btn-lg btn-dark" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php $this->stop() ?>