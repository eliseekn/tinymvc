<nav class="d-flex justify-content-end">
    <ol class="breadcrumb bg-dark">
        <?php
        foreach ($items as $key => $value) :
            if (!empty($value)) :
        ?>

        <li class="breadcrumb-item">
            <a href="<?= $value ?>" class="text-white"><?= $key ?></a>
        </li>

        <?php else : ?>

        <li class="breadcrumb-item active text-muted" aria-current="page"><?= $key ?></li>

        <?php
            endif;
        endforeach;
        ?>
    </ol>
</nav>
