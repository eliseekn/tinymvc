<?php $this->layout('admin/layout', [
    'page_title' => __('medias') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<div class="row mb-4">
    <div class="col-md-3 mb-md-0 mb-4">
        <div class="card card-metrics bg-light shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <p><i class="fa fa-photo-video fa-lg"></i> <?= __('total') ?></p>
                <p class="font-weight-bold"><?= $medias->getTotalItems() ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-md-0 mb-4">
        <div class="card card-metrics bg-light shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <p><i class="fa fa-image fa-lg"></i> <?= __('images') ?></p>
                <p class="font-weight-bold"><?= $images ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-md-0 mb-4">
        <div class="card card-metrics bg-light shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <p><i class="fa fa-film fa-lg"></i> <?= __('videos') ?></p>
                <p class="font-weight-bold"><?= $videos ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-md-0 mb-4">
        <div class="card card-metrics bg-light shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <p><i class="fa fa-music fa-lg"></i> <?= __('sounds') ?></p>
                <p class="font-weight-bold"><?= $sounds ?></p>
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
                <span class="mr-md-2">
                    <input type="search" class="form-control" id="filter" placeholder="<?= __('search') ?>">
                </span>

                <span class="mt-lg-0 mt-2">
                    <upload-modal 
                        action="<?= absolute_url('admin/resources/medias/create') ?>" 
                        title="<?= __('new') ?>" 
                        multiple="multiple"
                        csrf_token='<?= csrf_token_input() ?>'>
                    </upload-modal>
                    
                    <button class="btn btn-danger ml-2" id="bulk-delete" data-url="<?= absolute_url('admin/resources/medias/delete') ?>">
                        <?= __('delete') ?>
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="select-all">
                            <label class="custom-control-label" for="select-all"><?= __('select_all') ?></label>
                        </div>
                    </th>
                </tr>
            </thead>
            
            <tbody>
                <tr>
                    <td>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">

                            <?php foreach ($medias as $media) : ?>
                            
                            <div class="col mb-3">
                                <div class="card h-100">
                                    <?php if (in_array(get_file_extension($media->filename), \App\Database\Models\MediasModel::FORMATS[0])) : ?>
                                    <img class="card-img-top" src="<?= $media->url ?>" width="200" height="200">
                                    <?php elseif (in_array(get_file_extension($media->filename), \App\Database\Models\MediasModel::FORMATS[1])) : ?>
                                    <video class="card-img-top" width="200" height="200">
                                        <source src="<?= $media->url ?>"></source>
                                    </video>
                                    <?php elseif (in_array(get_file_extension($media->filename), \App\Database\Models\MediasModel::FORMATS[2])) : ?>
                                    <audio controls class="card-img-top" width="200" height="200">
                                        <source src="<?= $media->url ?>"></source>
                                    </audio>
                                    <?php endif ?>

                                    <div class="card-body text-center p-2">
                                        <p class="mb-0"><?= $media->filename ?></p>

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


                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="<?= $media->id ?>" data-id="<?= $media->id ?>">
                                            <label class="custom-control-label" for="<?= $media->id ?>"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php endforeach ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
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
