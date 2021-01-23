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