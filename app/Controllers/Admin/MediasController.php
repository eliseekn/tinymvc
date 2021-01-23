<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use App\Helpers\Activity;
use App\Requests\MediaInfos;
use Framework\Support\Alert;
use Framework\Support\Storage;
use Framework\Routing\Controller;
use App\Database\Models\MediasModel;

class MediasController extends Controller
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
	{
        $medias = MediasModel::select()->orderDesc('created_at')->paginate(10);
        $images  = 0; $videos = 0; $sounds = 0;

        foreach(MediasModel::select()->orderDesc('created_at')->all() as $media) {
            if (in_array(get_file_extension($media->filename), MediasModel::FORMATS[0])) {
                $images++;
            } elseif (in_array(get_file_extension($media->filename), MediasModel::FORMATS[1])) {
                $videos++;
            } elseif (in_array(get_file_extension($media->filename), MediasModel::FORMATS[2])) {
                $sounds++;
            }
        }

		$this->render('admin/resources/medias/index', compact('medias', 'images', 'videos', 'sounds'));
	}

	/**
	 * display edit page
	 * 
	 * @param  int $id
	 * @return void
	 */
    public function edit(int $id): void
	{
		$this->render('admin/resources/medias/edit', [
            'media' => MediasModel::findSingle($id)
        ]);
	}

	/**
	 * create
	 *
	 * @return void
	 */
    public function create(): void
	{
        $allowed_extensions = array_merge(MediasModel::FORMATS[0], MediasModel::FORMATS[1], MediasModel::FORMATS[2]);
        $medias = $this->request->files('files', $allowed_extensions, true);

        foreach($medias as $media) {
            if (!$media->isAllowed()) {
                $this->redirect('admin/resources/medias')->withToast(__('import_file_type_error') . implode(', ', $allowed_extensions))->error();
            }
    
            if (!$media->isUploaded()) {
                $this->redirect('admin/resources/medias')->withToast(__('import_data_error'))->error();
            }
    
            if (!$media->save(absolute_path('storage.uploads.' . Carbon::now()->year. '.' . Carbon::now()->month))) {
                $this->redirect('admin/resources/medias')->withToast(__('upload_error'))->error();
            }

            MediasModel::insert([
                'filename' => $media->filename,
                'url' => absolute_url('storage/uploads/' . Carbon::now()->year. '/' . Carbon::now()->month . '/' . $media->filename)
            ]);
        }

        Activity::log(__('medias_created'));
        $this->redirect('admin/resources/medias')->withToast(__('medias_created'))->success();
	}
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function view(int $id): void
	{
		$this->render('admin/resources/medias/view', [
            'media' => MediasModel::findSingle($id)
        ]);
	}
    
	/**
	 * update
	 *
     * @param  int $id
	 * @return void
	 */
	public function update(int $id): void
	{
        $validator = MediaInfos::validate($this->request->inputs());

        if ($validator->fails()) {
            $this->redirect()->withErrors($validator->errors())->withInputs($validator->inputs())
                ->withToast(__('media_not_updated', true))->error();
        }

        $media = MediasModel::findSingle($id);

        //retrieves year and month folders of media
        $folder = explode('/', $media->url);
        end($folder);

        $month = prev($folder);
        $year = prev($folder);

        if (!Storage::path(config('storage.uploads'))->add($year. '/' . $month)->renameFile($media->filename, $this->request->filename)) {
            $this->redirect()->withToast(__('media_not_updated', true))->error();
        }

        MediasModel::update([
            'filename' => $this->request->filename,
            'title' => $this->request->title,
            'description' => $this->request->description,
            'url' => absolute_url('storage/uploads/' . $year. '/' . $month . '/' . $this->request->filename)
        ])->where('id', $id)->persist();

        Activity::log(__('media_updated'));
		$this->redirect()->withToast(__('media_updated'))->success();
	}

	/**
	 * delete
	 *
     * @param  int|null $id
	 * @return void
	 */
	public function delete(?int $id = null): void
	{
        if (!is_null($id)) {
			if (!MediasModel::find($id)->exists()) {
				$this->redirect()->withToast(__('media_not_found'))->error();
			}
	
			MediasModel::deleteWhere('id', $id);
            Activity::log(__('media_deleted'));
            $this->redirect('admin/resources/medias')->withToast(__('media_deleted'))->success();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				MediasModel::deleteWhere('id', $id);
            }
			
            Activity::log(__('medias_deleted'));
            Alert::toast(__('medias_deleted'))->success();
		}
	}
}
