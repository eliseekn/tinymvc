<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use App\Requests\UpdateMedia;
use Framework\Support\Storage;
use App\Database\Models\Medias;
use App\Helpers\DownloadHelper;
use Framework\Routing\Controller;

class MediasController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(): void
	{
        $medias = Medias::paginate();
        list($images, $videos, $audios) = Medias::findByType();
		$this->render('admin.medias.index', compact('medias', 'images', 'videos', 'audios'));
	}
    
    /**
     * search
     *
     * @return void
     */
    public function search(): void
	{
        $q = $this->request()->queries('q', '');
        $medias = Medias::paginateQuery($q);
        list($images, $videos, $audios) = Medias::findByTypeQuery($q);
		$this->render('admin.medias.index', compact('medias', 'images', 'videos', 'audios', 'q'));
	}
    
    /**
     * download
     *
	 * @param  int $id
     * @return void
     */
    public function download(int $id): void
	{
        $media = $this->model('medias')->findSingle($id);
        DownloadHelper::init($media->filename, true)->send();
	}

	/**
	 * edit
	 * 
	 * @param  int $id
	 * @return void
	 */
    public function edit(int $id): void
	{
        $media = $this->model('medias')->findSingle($id);
		$this->render('admin.medias.edit', compact('media'));
	}

	/**
	 * create
	 *
	 * @return void
	 */
    public function create(): void
	{
        $medias = $this->request()->files('files', [], true);

        foreach($medias as $media) {
            if (!$media->isUploaded()) {
                $this->redirect('admin/resources/medias')->withToast(__('import_data_error'))->error();
            }
    
            if (!$media->save(absolute_path('storage.uploads.' . Carbon::now()->year. '.' . Carbon::now()->month))) {
                $this->redirect('admin/resources/medias')->withToast(__('upload_error'))->error();
            }

            Medias::store($media);
        }

        $this->log(__('medias_uploaded'));
        $this->redirect('admin/resources/medias')->withToast(__('medias_uploaded'))->success();
	}
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function read(int $id): void
	{
        $media = $this->model('medias')->findSingle($id);
		$this->render('admin.medias.read', compact('media'));
	}
    
	/**
	 * update
	 *
     * @param  int $id
	 * @return void
	 */
	public function update(int $id): void
	{
        UpdateMedia::validate($this->request()->inputs())->redirectOnFail();

        $media = $this->model('medias')->findSingle($id);

        list($month, $year) = $this->getMediasFolders($media);

        if (
            !Storage::path(config('storage.uploads'))->add($year. '/' . $month)
                ->renameFile($media->filename, $this->request('filename'))
        ) {
            $this->back()->withToast(__('media_not_updated'))->error();
        }

        Medias::update($this->request(), $id, $year, $month);
        $this->log(__('media_updated'));
		$this->back()->withToast(__('media_updated'))->success();
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
            $media = $this->model('medias')->findSingle($id);

            if ($media === false) {
				$this->back()->withToast(__('media_not_found'))->error();
			}

            list($month, $year) = $this->getMediasFolders($media);
            Storage::path(config('storage.uploads'))->add($year. '/' . $month)->deleteFile($media->filename);

			$this->model('medias')->deleteIfExists($id);
            $this->log(__('media_deleted'));
            $this->back()->withToast(__('media_deleted'))->success();
		} else {
			foreach (explode(',', $this->request('items')) as $id) {
                $media = $this->model('medias')->findSingle($id);
                
                if ($media !== false) {
                    list($month, $year) = $this->getMediasFolders($media);
                    Storage::path(config('storage.uploads'))->add($year. '/' . $month)->deleteFile($media->filename);

                    $this->model('medias')->deleteIfExists($id);
                }
            }
			
            $this->log(__('medias_deleted'));
            $this->alert('toast', __('medias_deleted'))->success();
            $this->response(['redirect' => absolute_url('admin/resources/medias')], true);
		}
	}
    
    /**
     * retrieves year and month folders of file
     *
     * @param  mixed $media
     * @return array
     */
    private function getMediasFolders($media): array
    {
        $folder = explode('/', $media->url);
        end($folder);

        $month = prev($folder);
        $year = prev($folder);

        return [$month, $year];
    }
}
