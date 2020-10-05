<?php
$this->layout('admin/layout', [
    'page_title' => 'Roles | Administration'
])
?>

<?php $this->start('page_content') ?>

<?php
$this->insert('partials/breadcrumb', [
    'items' => [
        'Roles' => ''
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
            <span class="text-white lead">Roles</span>

            <span>
                <a href="<?= absolute_url('/admin/roles/new') ?>" class="btn btn-primary">New</a>
                
                <upload-modal action="<?= absolute_url('/admin/roles/import') ?>"></upload-modal>
                <export-modal action="<?= absolute_url('/admin/roles/export') ?>"></export-modal>

                <button class="btn btn-danger" id="bulk-delete" data-url="<?= absolute_url('/admin/roles/delete') ?>">
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
                        <th scope="col"><i class="fa fa-sort"></i> Title</th>
                        <th scope="col"><i class="fa fa-sort"></i> Slug</th>
                        <th scope="col"><i class="fa fa-sort"></i> Description</th>
                        <th scope="col"><i class="fa fa-sort"></i> Created at</th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($roles as $key => $role) : ?>

                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="<?= $role->id ?>" data-id="<?= $role->id ?>">
                                    <label class="custom-control-label" for="<?= $role->id ?>"></label>
                                </div>
                            </td>

                            <td><?= $key + 1 ?></td>
                            <td><?= $role->title ?></td>
                            <td><?= $role->slug ?></td>
                            <td><?= html_entity_decode($role->description) ?></td>
                            <td><?= date('Y-m-d', strtotime($role->created_at)) ?></td>

                            <td>
                                <a class="btn text-primary" href="<?= absolute_url('/admin/roles/view/' . $role->id) ?>" title="View item">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a class="btn text-primary" href="<?= absolute_url('/admin/roles/edit/' . $role->id) ?>" title="Edit item">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <confirm-delete type="icon" content='<i class="fa fa-trash-alt"></i>' action="<?= absolute_url('/admin/roles/delete/' . $role->id) ?>" redirect="<?= current_url()?>"></confirm-delete>
                            </td>
                        </tr>

                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex align-items-center justify-content-between">
        <span>Total result(s): <span class="font-weight-bold"><?= $roles->getTotalItems() ?></span></span>

        <?php
        $this->insert('partials/pagination', [
            'pagination' => $roles
        ])
        ?>
    </div>
</div>

<?php $this->stop() ?>