<section class="container my-5">
	<div class="row row-cols-2">

	<?php foreach ($posts as $key => $post) { ?>

		<article class="col mb-5">
			<div class="card">
				<img 
					src="<?= absolute_url('public/assets/img/posts/'. $post['image']) ?>" 
					class="card-img-top" 
					alt="Post image">
				
				<div class="card-body">
					<h2 class="card-title post-title"><?= $post['title'] ?></h2>
					<p class="card-text mt-3 text-justify"><?= generate_exerpt($post['content']) ?></p>
					<a 
						href="<?= absolute_url('posts/slug/'. $post['slug']) ?>" 
						data-post-id="<?= $post['id'] ?>" 
						class="btn btn-lg btn-dark read-more">Read more</a>
				</div>
			</div>
		</article>

	<?php } ?>

	</div>

	<nav class="my-5">
		<ul class="pagination pagination-lg justify-content-center">

			<?php if ($pagination['page'] > 1) { ?>
			
			<li class="page-item">
				<a class="page-link text-dark" href="<?= absolute_url('home/page/'. $pagination['previous_page']) ?>">
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
				<a class="page-link text-dark" href="<?= absolute_url('home/page/'. $pagination['next_page']) ?>">
					<i class="fas fa-angle-double-right"></i>
				</a>
			</li>

			<?php } ?>
		</ul>
	</nav>
</section>