<div class="text-center">
    <button 
        class="btn btn-lg btn-dark" 
        data-toggle="modal" 
        data-target="#add-post-modal">Add post</button>
</div>

<table class="table my-5">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Feautured image</th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>

    <tbody>

    <?php foreach ($posts as $post) { ?>

        <tr>
            <th scope="row"><?= $post['id'] ?></th>
            <td><?= $post['title'] ?></td>
            <td>
                <img 
                    src="<?= absolute_url('public/assets/img/posts/'. $post['image']) ?>" 
                    alt="Feautured image"
                    class="img-fluid">
            </td>
            <td>
                <a 
                    id="edit-post" 
                    data-post-id="<?= $post['id'] ?>" 
                    data-post-title="<?= $post['title'] ?>" 
                    data-post-content="<?= $post['content'] ?>" 
                    href="#">Edit post</a>
            </td>
            <td>
                <a id="edit-post-image" data-post-id="<?= $post['id'] ?>" href="#">
                    Replace image
                </a>
            </td>
            <td>
                <a id="remove-post" data-post-id="<?= $post['id'] ?>" href="#">
                    Remove post
                </a>
            </td>
        </tr>
    
    <?php } ?>

    </tbody>
</table>

<nav class="my-5">
    <ul class="pagination pagination-lg justify-content-center">

        <?php if ($pagination['page'] > 1) { ?>
        
        <li class="page-item">
            <a class="page-link text-dark" href="<?= absolute_url('dashboard/posts/'. $pagination['previous_page']) ?>">
                <i class="fas fa-angle-double-left"></i>
            </a>
        </li>

        <?php } ?>

        <?php if ($pagination['total_pages'] > 1) { ?>
        
        <li class="page-item page-link text-dark">
            Page <?= $pagination['page'] ?>/<?= $pagination['total_pages'] ?>
        </li>
        
        <?php } ?>

        <?php if ($pagination['page'] < $pagination['total_pages']) { ?>

        <li class="page-item">
            <a class="page-link text-dark" href="<?= absolute_url('dashboard/posts/'. $pagination['next_page']) ?>">
                <i class="fas fa-angle-double-right"></i>
            </a>
        </li>

        <?php } ?>
        
    </ul>
</nav>