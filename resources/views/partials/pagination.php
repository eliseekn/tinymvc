<nav>
    <ul class="pagination justify-content-center mb-0">
        <?php if ($pagination->hasLess()) : ?>

        <li class="page-item">
            <a class="page-link" href="<?= $pagination->previousPageUrl() ?>"><?= __('previous') ?></a>
        </li>

        <?php 
        endif;
        
        if ($pagination->totalPages() > 1) :
            for ($i = 1; $i <= $pagination->totalPages(); $i++) :
        ?>

        <li class="page-item <?php if ($pagination->currentPage() === $i) : echo 'active'; endif ?>">
            <a class="page-link" href="<?= $pagination->pageUrl($i) ?>"><?= $i ?></a>
        </li>

        <?php
            endfor;
        endif;
        
        if ($pagination->hasMore()) : 
        ?>

        <li class="page-item">
            <a class="page-link" href="<?= $pagination->nextPageUrl() ?>"><?= __('next') ?></a>
        </li>

        <?php endif ?>
    </ul>
</nav>