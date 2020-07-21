<?php $this->layout('admin/layout', [
    'page_title' => 'Users | Administration',
    'page_description' => 'Users administration page'
]) ?>

<?php $this->start('page_content') ?>

<?php
if (session_has('flash_messages')) :
    $flash_messages = get_flash_messages('flash_messages');

    if (isset($flash_messages['success'])) :
?>
    <div class="alert alert-success alert-dismissible show" role="alert">

        <?php foreach ($flash_messages as $flash_message) : echo $flash_message . '<br>'; endforeach; ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

<?php else : ?>

    <div class="alert alert-danger alert-dismissible show" role="alert">

        <?php
        foreach ($flash_messages as $flash_message) :
            if (is_array($flash_message)) :
                foreach ($flash_message as $error_message) :
                    echo $error_message . '<br>';
                endforeach;
            else :
                echo $flash_message . '<br>';
            endif;
        endforeach
        ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

<?php
    endif;
endif
?>

<div class="card">
    <div class="card-header bg-dark d-flex align-items-center justify-content-between">
        <h3 class="mb-0 text-white">Users</h3>
        
        <a href="<?= absolute_url('/admin/users/add') ?>" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add new user
        </a>
    </div>

    <div class="card-body">
        <div class="input-group mb-5">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <li class="fa fa-search"></li>
                </div>
            </div>

            <input type="search" class="form-control" id="filter" placeholder="Filter results">
        </div>

        <div class="d-flex align-items-center justify-content-end mb-3">
            <button class="btn btn-danger ml-3" id="bulk-delete" data-url="<?= absolute_url('/admin/users/delete/') ?>">
                <i class="fa fa-trash"></i> Bulk delete
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="select-all">
                                <label class="custom-control-label" for="select-all"></label>
                            </div>
                        </th>

                        <th scope="col"><i class="fa fa-sort"></i> ID</th>
                        <th scope="col"><i class="fa fa-sort"></i> Name</th>
                        <th scope="col"><i class="fa fa-sort"></i> Email</th>
                        <th scope="col"><i class="fa fa-sort"></i> Role</th>
                        <th scope="col"><i class="fa fa-sort"></i> Created at</th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($users as $user) : ?>

                    <tr>
                        <td>

                            <?php if ($user->role !== 'admin') : ?>

                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="<?= $user->id ?>" data-id="<?= $user->id ?>">
                                <label class="custom-control-label" for="<?= $user->id ?>"></label>
                            </div>

                            <?php endif ?>

                        </td>

                        <td><?= $user->id ?></td>
                        <td><?= $user->name ?></td>
                        <td><?= $user->email ?></td>
                        <td><?= $user->role ?></td>
                        <td><?= $user->created_at ?></td>

                        <td>

                            <?php if ($user->role !== 'admin' || $user->id === get_session(APP_NAME . '_user')->id) : ?>

                            <a class="btn text-primary" href="<?= absolute_url('/admin/users/edit/' . $user->id) ?>">
                                <i class="fa fa-edit"></i>
                            </a>

                            <?php if ($user->id !== get_session(APP_NAME . '_user')->id) : ?>

                            <button class="btn text-danger" onclick="confirmDelete(this, 'Are you sure you want to delete this user?', '<?= absolute_url('/admin/users/delete/' . $user->id) ?>')">
                                <i class="fa fa-trash-alt"></i>
                            </button>

                            <?php 
                                endif;
                            endif 
                            ?>

                        </td>
                    </tr>

                    <?php endforeach ?>
                
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex align-items-center justify-content-between">
        <p class="lead mb-0">
            Total result(s): <span class="font-weight-bold"><?= $users->getTotalItems() ?></span>
        </p>

        <nav>
            <ul class="pagination justify-content-center mb-0">

                <?php if ($users->hasLess()) : ?>

                <li class="page-item">
                    <a class="page-link" href="<?= $users->previousPageUrl() ?>">
                        Previous
                    </a>
                </li>

                <?php 
                endif;
                
                if ($users->totalPages() > 1) :
                    for ($i = 1; $i <= $users->totalPages(); $i++) :
                ?>

                <li class="page-item <?php if ($users->currentPage() === $i) : echo 'active'; endif ?>">
                    <a class="page-link" href="<?= $users->pageUrl($i) ?>"><?= $i ?></a>
                </li>

                <?php
                    endfor;
                endif;
                
                if ($users->hasMore()) : 
                ?>

                <li class="page-item">
                    <a class="page-link" href="<?= $users->nextPageUrl() ?>">
                        Next
                    </a>
                </li>

                <?php endif ?>

            </ul>
        </nav>
    </div>
</div>

<?php $this->stop() ?>
