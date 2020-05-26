<?php $this->layout('layouts/admin', [
    'page_title' => $page_title,
    'page_description' => $page_description
]) ?>

<?php $this->start('page_content') ?>

    <table class="table my-5">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Post id</th>
                <th scope="col">Author</th>
                <th scope="col">Comment</th>
                <th scope="col"></th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($comments as $comment) : ?>

            <tr>
                <th scope="row"><?= $comment->id ?></th>
                <td><?= $comment->post_id ?></td>
                <td><?= $comment->author ?></td>
                <td><?= $comment->content ?></td>
                <td>
                    <a id="delete-comment" data-comment-id="<?= $comment->id ?>" href="#">
                        Delete comment
                    </a>
                </td>
            </tr>
        
        <?php endforeach ?>

        </tbody>
    </table>

    <nav class="my-5">
		<ul class="pagination pagination-lg justify-content-center">

			<?php if ($comments->currentPage() > 1) : ?>
			
			<li class="page-item">
				<a class="page-link text-dark" href="<?= $comments->previousPageUrl() ?>">
					<i class="fas fa-angle-double-left"></i>
				</a>
			</li>

			<?php endif ?>

			<?php if ($comments->totalPages() > 1) : ?>
			
			<li class="page-item page-link text-dark">
				Page <?= $comments->currentPage() ?>/<?= $comments->totalPages() ?>
			</li>
			
			<?php endif ?>

			<?php if ($comments->currentPage() < $comments->totalPages()) : ?>

			<li class="page-item">
				<a class="page-link text-dark" href="<?= $comments->nextPageUrl() ?>">
					<i class="fas fa-angle-double-right"></i>
				</a>
			</li>

			<?php endif ?>

		</ul>
	</nav>

<?php $this->stop() ?>