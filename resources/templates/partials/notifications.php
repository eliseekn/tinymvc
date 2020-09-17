<?php if ($display === 'alert') : ?>

    <div class="alert alert-<?= key($messages) ?> alert-dismissible show" role="alert">
        <?php
        foreach ($messages as $message) :
            if (is_array($message)) :
                foreach ($message as $m) :
                    echo $m . '<br>';
                endforeach;
            else :
                echo $message . '<br>';
            endif;
        endforeach
        ?>

        <?php if ($dismiss === true) : ?>
            <button class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        <?php endif ?>
    </div>

<?php elseif ($display === 'modal') : ?>

    <alert-modal type="<?= key($messages) ?>" message="<?= $messages[key($messages)] ?>"></alert-modal>

<?php elseif ($display === 'toast') : ?>

    <alert-toast type="<?= key($messages) ?>" message="<?= $messages[key($messages)] ?>"></alert-toast>

<?php endif ?>
