<?php $this->layout('admin/layout', [
    'page_title' => __('medias') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<div class="row mb-4">
    <div class="col-md-4 mb-md-0">
        <div class="card card-metrics bg-light shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <p><i class="fa fa-photo-video fa-lg"></i> <?= __('total') ?></p>
                <p class="font-weight-bold"><?= $medias->getTotalItems() ?></p>
            </div>
        </div>
    </div>
</div>

<?php if (auth()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="d-flex flex-lg-row flex-column align-items-lg-center justify-content-lg-between">
            <span><?= __('medias') ?></span>

            <div class="d-flex flex-lg-row flex-column mt-lg-0 mt-2">
                <span class="mr-md-3">
                    <input type="search" class="form-control" id="filter" placeholder="<?= __('search') ?>">
                </span>

                <span class="mt-lg-0 mt-2">
                    <a href="<?= absolute_url('admin/resources/medias/new') ?>" class="btn btn-outline-dark">
                        <?= __('new') ?>
                    </a>
                    
                    <upload-modal 
                        action="<?= absolute_url('admin/resources/medias/import') ?>" 
                        title="<?= __('import') ?>" 
                        csrf_token='<?= csrf_token_input() ?>'>
                    </upload-modal>

                    <export-modal 
                        action="<?= absolute_url('admin/resources/medias/export') ?>" 
                        title="<?= __('export') ?>" 
                        csrf_token='<?= csrf_token_input() ?>'>
                    </export-modal>

                    <button class="btn btn-danger" id="bulk-delete" data-url="<?= absolute_url('admin/resources/medias/delete') ?>">
                        <?= __('delete') ?>
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th scope="col">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="select-all">
                                <label class="custom-control-label" for="select-all"></label>
                            </div>
                        </th>

                        <th scope="col"><i class="fa fa-sort"></i> #</th>
                        <th scope="col"><i class="fa fa-sort"></i> ID</th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('created_at') ?></th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($medias as $key => $media) : ?>
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="<?= $media->id ?>" data-id="<?= $media->id ?>">
                                <label class="custom-control-label" for="<?= $media->id ?>"></label>
                            </div>
                        </td>

                        <td><?= $medias->getFirstItem() + $key + 1 ?></td>
                        <td><?= $medias->id ?></td>
                        <td><?= \App\Helpers\DateHelper::format($media->created_at)->human() ?></td>

                        <td>
                            <a class="btn text-dark p-1" href="<?= absolute_url('admin/resources/medias/view/' . $media->id) ?>" title="<?= __('details') ?>">
                                <i class="fa fa-eye"></i>
                            </a>

                            <a class="btn text-dark p-1" href="<?= absolute_url('admin/resources/medias/edit/' . $media->id) ?>" title="<?= __('edit') ?>">
                                <i class="fa fa-edit"></i>
                            </a>

                            <confirm-delete 
                                type="icon" 
                                title="<?= __('delete') ?>"
                                content='<i class="fa fa-trash-alt"></i>' 
                                action="<?= absolute_url('admin/resources/medias/delete/' . $media->id) ?>">
                            </confirm-delete>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex align-items-center justify-content-between">
        <span><?= __('total_results') ?> <span class="font-weight-bold"><?= $medias->getTotalItems() ?></span></span>
        <span><?= __('showing') ?> <span class="font-weight-bold"><?= $medias->getPageTotalItems() === 0 ? $medias->getFirstItem() : $medias->getFirstItem() + 1 ?></span> <?= __('to') ?> <span class="font-weight-bold"><?= $medias->getPageTotalItems() + $medias->getFirstItem() ?></span></span>

        <?php $this->insert('partials/pagination', [
            'pagination' => $medias
        ]) ?>
    </div>
</div>

<?php $this->stop() ?>
