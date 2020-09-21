<nav class="d-flex justify-content-end">
    <ol class="breadcrumb bg-white">
        <li class="breadcrumb-item">
            <a href="<?= absolute_url('/admin') ?>">
                <i class="fa fa-home"></i> Dashboard
            </a>
        </li>

        <?php
        foreach ($items as $key => $value) :
            if (!empty($value)) :
        ?>

        <li class="breadcrumb-item">
            <a href="<?= $value ?>"><?= $key ?></a>
        </li>

        <?php else : ?>

        <li class="breadcrumb-item active" aria-current="page"><?= $key ?></li>

        <?php
            endif;
        endforeach;
        ?>
    </ol>
</nav>
