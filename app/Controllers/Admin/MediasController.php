<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use App\Helpers\Auth;
use App\Requests\UpdateMedia;
use Framework\Support\Storage;
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
        $medias = $this->model('medias')
            ->findBy('user_id', Auth::get()->id)
            ->oldest()
            ->paginate(10);

        $images = 0; $videos = 0; $sounds = 0;

        $medias_types = $this->model('medias')->findAllBy('user_id', Auth::get()->id);

        foreach ($medias_types as $media) {
            if ($this->getMediaType($media->filename) === 'image') {
                $images++;
            } elseif ($this->getMediaType($media->filename) === 'video') {
                $videos++;
            } elseif ($this->getMediaType($media->filename) === 'audio') {
                $sounds++;
            } 
        }

		$this->render('admin.medias.index', compact('medias', 'images', 'videos', 'sounds'));
	}
    
    /**
     * search
     *
     * @return void
     */
    public function search(): void
	{
        $medias = $this->model('medias')
            ->findBy('user_id', Auth::get()->id)
            ->and('filename', 'like', $this->request->q)
            ->or('created_at', 'like', $this->request->q)
            ->oldest()
            ->paginate(10);

        $q = $this->request->q ?? ''; $images = 0; $videos = 0; $sounds = 0;

        foreach(
            $this->model('medias')
                ->findBy('user_id', Auth::get()->id)
                ->and('filename', 'like', $this->request->q)
                ->all() as $media
        ) {
            if ($this->getMediaType($media->filename) === 'image') {
                $images++;
            } elseif ($this->getMediaType($media->filename) === 'video') {
                $videos++;
            } elseif ($this->getMediaType($media->filename) === 'audio') {
                $sounds++;
            }
        }

		$this->render('admin.medias.index', compact('medias', 'images', 'videos', 'sounds', 'q'));
	}

	/**
	 * edit
	 * 
	 * @param  int $id
	 * @return void
	 */
    public function edit(int $id): void
	{
		$this->render('admin.medias.edit', ['media' => $this->model('medias')->findSingle($id)]);
	}

	/**
	 * create
	 *
	 * @return void
	 */
    public function create(): void
	{
        $medias = $this->request->files('files', [], true);

        foreach($medias as $media) {
            if (!$media->isUploaded()) {
                $this->redirect('admin/resources/medias')->withToast(__('import_data_error'))->error();
            }
    
            if (!$media->save(absolute_path('storage.uploads.' . Carbon::now()->year. '.' . Carbon::now()->month))) {
                $this->redirect('admin/resources/medias')->withToast(__('upload_error'))->error();
            }

            $this->model('medias')->insert([
                'user_id' => Auth::get()->id,
                'filename' => $media->filename,
                'url' => absolute_url('storage/uploads/' . Carbon::now()->year. '/' . Carbon::now()->month . '/' . $media->filename)
            ]);
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
		$this->render('admin.medias.read', ['media' => $this->model('medias')->findSingle($id)]);
	}
    
	/**
	 * update
	 *
     * @param  int $id
	 * @return void
	 */
	public function update(int $id): void
	{
        UpdateMedia::validate($this->request->inputs())->redirectOnFail();

        $media = $this->model('medias')->findSingle($id);

        list($month, $year) = $this->getMediasFolders($media);

        if (
            !Storage::path(config('storage.uploads'))->add($year. '/' . $month)
                ->renameFile($media->filename, $this->request->filename)
        ) {
            $this->back()->withToast(__('media_not_updated'))->error();
        }

        $this->model('medias')->updateIfExists($id, [
            'filename' => $this->request->filename,
            'title' => $this->request->title,
            'description' => $this->request->description,
            'url' => absolute_url('storage/uploads/' . $year. '/' . $month . '/' . $this->request->filename)
        ]);

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
			foreach (explode(',', $this->request->items) as $id) {
                $media = $this->model('medias')->findSingle($id);
                
                if ($media !== false) {
                    list($month, $year) = $this->getMediasFolders($media);
                    Storage::path(config('storage.uploads'))->add($year. '/' . $month)->deleteFile($media->filename);

                    $this->model('medias')->deleteIfExists($id);
                }
            }
			
            $this->log(__('medias_deleted'));
            $this->alert('toast', __('medias_deleted'))->success();
            $this->response([absolute_url('admin/resources/medias')], true);
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
    
    /**
     * retrieves media type
     *
     * @param  string $filename
     * @return string
     */
    private function getMediaType(string $filename): string
    {
        if (in_array(get_file_extension($filename), $this->model('medias')->TYPE[0])) {
            return 'image';
        } elseif (in_array(get_file_extension($filename), $this->model('medias')->TYPE[1])) {
            return 'video';
        } elseif (in_array(get_file_extension($filename), $this->model('medias')->TYPE[2])) {
            return 'audio';
        }
    }
}
