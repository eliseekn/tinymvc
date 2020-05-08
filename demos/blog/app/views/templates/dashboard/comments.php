<table class="table my-5">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Post id</th>
            <th scope="col">Comment author</th>
            <th scope="col">Comment</th>
            <th scope="col"></th>
        </tr>
    </thead>

    <tbody>

    <?php foreach ($comments as $comment) { ?>

        <tr>
            <th scope="row"><?= $comment['id'] ?></th>
            <td><?= $comment['post_id'] ?></td>
            <td><?= $comment['author'] ?></td>
            <td><?= $comment['content'] ?></td></td>
            <td>
                <a id="remove-comment" data-comment-id="<?= $comment['id'] ?>" href="#">
                    Remove comment
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
            <a class="page-link text-dark" href="<?= absolute_url('dashboard/comments/'. $pagination['previous_page']) ?>">
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
            <a class="page-link text-dark" href="<?= absolute_url('dashboard/comments/'. $pagination['next_page']) ?>">
                <i class="fas fa-angle-double-right"></i>
            </a>
        </li>

        <?php } ?>
    </ul>
</nav>