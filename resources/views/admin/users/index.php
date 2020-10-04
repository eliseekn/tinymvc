<?php
$this->layout('admin/layout', [
    'page_title' => 'Users | Administration'
])
?>

<?php $this->start('page_content') ?>

<?php
$this->insert('partials/breadcrumb', [
    'items' => [
        'Users' => ''
    ]
]);
?>

<?php
if (session_has_flash_messages()) :
    $this->insert('partials/notifications', [
        'messages' => get_flash_messages(),
        'display' => 'toast'
    ]);
endif
?>

<div class="card">
    <div class="card-header bg-dark">
        <div class="d-flex flex-lg-row flex-sm-column align-items-lg-center justify-content-lg-between">
            <span class="text-white lead">Users</span>

            <span>
                <a href="<?= absolute_url('/admin/users/new') ?>" class="btn btn-primary">New</a>
                
                <upload-modal action="<?= absolute_url('/admin/users/import') ?>"></upload-modal>
                <export-modal action="<?= absolute_url('/admin/users/export') ?>"></export-modal>

                <button class="btn btn-danger" id="bulk-delete" data-url="<?= absolute_url('/admin/users/delete') ?>">
                    Bulk delete
                </button>
            </span>
        </div>
    </div>

    <div class="card-body">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <div class="input-group-text bg-white">
                    <i class="fa fa-search"></i>
                </div>
            </div>

            <input type="search" class="form-control border-left-0" id="filter" placeholder="Filter results">
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
                        <th scope="col"><i class="fa fa-sort"></i> Account</th>
                        <th scope="col"><i class="fa fa-sort"></i> Status</th>
                        <th scope="col"><i class="fa fa-sort"></i> Created at</th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($users as $key => $user) : ?>

                        <tr>
                            <td>
                                <?php if ($user->role !== 'admin') : ?>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="<?= $user->id ?>" data-id="<?= $user->id ?>">
                                        <label class="custom-control-label" for="<?= $user->id ?>"></label>
                                    </div>

                                <?php endif ?>
                            </td>

                            <td><?= $key + 1 ?></td>
                            <td><?= $user->name ?></td>
                            <td><?= $user->email ?></td>
                            <td><?= $user->role ?></td>

                            <td>
                                <?php if ($user->active) : ?>
                                    <span class="badge badge-pill badge-success">Active</span>
                                <?php else : ?>
                                    <span class="badge badge-pill badge-danger">Inactive</span>
                                <?php endif ?>
                            </td>

                            <td>
                                <?php if ($user->online) : ?>
                                    <span class="badge badge-pill badge-success">Online</span>
                                <?php else : ?>
                                    <span class="badge badge-pill badge-danger">Offline</span>
                                <?php endif ?>
                            </td>

                            <td><?= date('Y-m-d', strtotime($user->created_at)) ?></td>

                            <td>
                                <a class="btn text-primary" href="<?= absolute_url('/admin/users/view/' . $user->id) ?>" title="View item">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <?php if ($user->role !== 'admin' || $user->id === get_user_session()->id) : ?>

                                <a class="btn text-primary" href="<?= absolute_url('/admin/users/edit/' . $user->id) ?>" title="Edit item">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <?php if ($user->id !== get_user_session()->id) : ?>

                                <confirm-delete type="icon" content='<i class="fa fa-trash-alt"></i>' action="<?= absolute_url('/admin/users/delete/' . $user->id) ?>" redirect="<?= current_url()?>"></confirm-delete>

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
        <span>Total result(s): <span class="font-weight-bold"><?= $users->getTotalItems() ?></span></span>

        <?php
        $this->insert('partials/pagination', [
            'pagination' => $users
        ])
        ?>
    </div>
</div>

<?php $this->stop() ?>