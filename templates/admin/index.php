<?php 
$this->layout('admin/layout', [
    'page_title' => 'TinyMVC | Administration dashboard',
    'page_description' => 'TinyMVC administration dashboard'
]) 
?>

<?php $this->start('page_content') ?>

<div class="card-columns">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark lead">
            <span class="text-white">Users</span>

            <a href="<?= absolute_url('/admin/users') ?>">
                <i class="fa fa-dot-circle text-white"></i>
            </a>
        </div>

        <div class="card-body">
            <p class="card-text">Total: <span class="font-weight-bold"><?= count($users) ?></span></p>
            <p class="card-text">Online: <span class="font-weight-bold"><?= count($online_users) ?></span></p>
            <p class="card-text">Latest registered: <span class="font-weight-bold font-italic"><?= $users[0]->name ?></span></p>
        </div>
    </div>
</div>

<?php $this->stop() ?>
