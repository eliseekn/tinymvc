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

    <button class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
