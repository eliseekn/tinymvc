<?php $this->layout('layouts/admin', [
    'page_title' => __('activities') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<div class="row mb-4">
    <div class="col-md-4 mb-md-0 mb-4">
        <div class="card card-metrics bg-light shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <p><i class="fa fa-user-clock fa-lg"></i> Total</p>
                <p class="font-weight-bold"><?= $activities->getTotalItems() ?></p>
            </div>
        </div>
    </div>
</div>

<?php if (auth()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="d-flex flex-lg-row flex-column align-items-lg-center justify-content-lg-between">
            <span><?= __('activities') ?></span>

            <div class="d-flex flex-lg-row flex-column mt-lg-0 mt-2">
                <span class="mr-md-2">
                    <input type="search" class="form-control" id="filter" placeholder="<?= __('search') ?>">
                </span>

                <span class="mt-lg-0 mt-2">
                    <export-modal action="<?= absolute_url('admin/account/activities/export') ?>">
                        <button class="btn btn-outline-dark mx-2"><?= __('export') ?></button>
                    </export-modal>

                    <button class="btn btn-danger" id="bulk-delete" data-url="<?= absolute_url('admin/account/activities/delete') ?>">
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

                        <th scope="col"><i class="fa fa-sort"></i> ID</th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('user') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('url') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('method') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('ip_address') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('action') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('created_at') ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($activities as $activity) : ?>
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="<?= $activity->id ?>" data-id="<?= $activity->id ?>">
                                <label class="custom-control-label" for="<?= $activity->id ?>"></label>
                            </div>
                        </td>

                        <td><?= $activity->id ?></td>
                        <td><?= $activity->user ?></td>
                        <td><?= $activity->url ?></td>
                        <td><?= $activity->method ?></td>
                        <td><?= $activity->ip_address ?></td>
                        <td><?= $activity->action ?></td>
                        <td><?= \App\Helpers\DateHelper::format($activity->created_at)->time_elapsed() ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex align-items-center justify-content-between">
        <span><?= __('total_results') ?> <span class="font-weight-bold"><?= $activities->getTotalItems() ?></span></span>
        <span><?= __('showing') ?> <span class="font-weight-bold"><?= $activities->getPageTotalItems() === 0 ? $activities->getFirstItem() : $activities->getFirstItem() + 1 ?></span> <?= __('to') ?> <span class="font-weight-bold"><?= $activities->getPageTotalItems() + $activities->getFirstItem() ?></span></span>

        <?php $this->insert('partials/pagination', [
            'pagination' => $activities
        ]) ?>
    </div>
</div>

<?php $this->stop() ?>
