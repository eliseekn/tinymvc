<?php $this->layout('admin/layout', [
    'page_title' => 'Users | Administration',
    'page_description' => 'Users administration page'
]) ?>

<?php $this->start('page_content') ?>

<?php if (session_has_flash_messages()) : $this->insert('partials/flash', [
        'messages' => get_flash_messages()
    ]);
endif ?>

<div class="card">
    <div class="card-header bg-dark d-flex align-items-center justify-content-between">
        <p class="mb-0 text-white lead">Users</p>
        <a href="<?= absolute_url('/admin/users/add') ?>" class="btn btn-primary">Add</a>
    </div>

    <div class="card-body">
        <div class="input-group mb-5">
            <div class="input-group-prepend">
                <div class="input-group-text bg-white">
                    <li class="fa fa-search"></li>
                </div>
            </div>

            <input type="search" class="form-control border-left-0" id="filter" placeholder="Filter results">
        </div>

        <div class="d-flex align-items-center justify-content-end mb-3">
            <button class="btn btn-danger ml-3" id="bulk-delete" data-url="<?= absolute_url('/admin/users/delete/') ?>">
                Bulk delete
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
                                <?php if ($user->role !== 'admin' || $user->id === get_user_session()->id) : ?>

                                    <a class="btn text-primary" href="<?= absolute_url('/admin/users/edit/' . $user->id) ?>" title="Edit item">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <?php if ($user->id !== get_user_session()->id) : ?>

                                    <button class="btn text-danger delete-item" onclick="confirmDelete(this)" data-message="Are you sure you want to delete this user?" data-redirect="<?= absolute_url('/admin/users/delete/' . $user->id) ?>" title="Delete item">
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
        <p class="mb-0">
            Total result(s): <span class="font-weight-bold"><?= $users->getTotalItems() ?></span>
        </p>

        <?php $this->insert('partials/pagination', [
            'pagination' => $users
        ]) ?>
    </div>
</div>

<?php $this->stop() ?>