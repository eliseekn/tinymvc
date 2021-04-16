<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use Framework\Http\Request;
use App\Validators\UpdateMedia;
use Framework\System\Storage;
use App\Helpers\DownloadHelper;
use Framework\Routing\Controller;
use App\Database\Repositories\Medias;

class MediasController extends Controller
{
    /**
     * @var \App\Database\Repositories\Medias $medias
     */
    private $medias;
    
    /**
     * __construct
     *
     * @param  \App\Database\Repositories\Medias $medias
     * @return void
     */
    public function __construct(Medias $medias)
    {
        $this->medias = $medias;
    }

    /**
     * index
     *
     * @return void
     */
    public function index(): void
	{
        $data = $this->medias->findAllPaginate();
        list($images, $videos, $audios, $others) = $this->medias->findAllByType();
		$this->render('admin.medias.index', compact('data', 'images', 'videos', 'audios', 'others'));
	}
    
    /**
     * search
     *
     * @param  \Framework\Http\Request $request
     * @return void
     */
    public function search(Request $request): void
	{
        $q = $request->queries('q', '');
        $data = $this->medias->findAllPaginateQuery($q);
        list($images, $videos, $audios, $others) = $this->medias->findAllByTypeQuery($q);
		$this->render('admin.medias.index', compact('data', 'images', 'videos', 'audios', 'others', 'q'));
	}
    
    /**
     * download
     *
	 * @param  int $id
     * @return void
     */
    public function download(int $id): void
	{
        $data = $this->medias->findSingle($id);
        DownloadHelper::init($data->filename, true)->send();
	}

	/**
	 * edit
	 * 
	 * @param  int $id
	 * @return void
	 */
    public function edit(int $id): void
	{
        $data = $this->medias->findSingle($id);
		$this->render('admin.medias.edit', compact('data'));
	}

	/**
	 * create
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
    public function create(Request $request): void
	{
        $files = $request->files('files', [], true);

        foreach($files as $file) {
            if (!$file->isUploaded()) {
                redirect()->route('medias.index')->withToast('error', __('import_data_error'))->go();
            }
    
            if (!$file->save(absolute_path('storage.uploads.' . Carbon::now()->year. '.' . Carbon::now()->month))) {
                redirect()->route('medias.index')->withToast('error', __('upload_error'))->go();
            }

            $this->medias->store($file);
        }

        $this->log(__('medias_uploaded'));
        redirect()->route('medias.index')->withToast('success', __('medias_uploaded'))->go();
	}
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function read(int $id): void
	{
        $data = $this->medias->findSingle($id);
		$this->render('admin.medias.read', compact('data'));
	}
    
	/**
	 * update
	 *
     * @param  \Framework\Http\Request $request
     * @param  int $id
	 * @return void
	 */
	public function update(Request $request, int $id): void
	{
        UpdateMedia::validate($request->inputs())->redirectOnFail();

        $media = $this->medias->findSingle($id);

        list($month, $year) = $this->getMediasFolders($media);

        if (
            !Storage::path(config('storage.uploads'))->add($year. '/' . $month)
                ->renameFile($media->filename, $request->filename)
        ) {
            redirect()->back()->withToast('error', __('media_not_updated'))->go();
        }

        $this->medias->refresh($request, $id, $year, $month);
        $this->log(__('media_updated'));
		redirect()->back()->withToast('success', __('media_updated'))->go();
	}

	/**
	 * delete
	 *
     * @param  \Framework\Http\Request $request
     * @param  int|null $id
	 * @return void
	 */
	public function delete(Request $request, ?int $id = null): void
	{
        if (!is_null($id)) {
            $media = $this->medias->findSingle($id);

            if ($media === false) {
				redirect()->back()->withToast('error', __('media_not_found'))->go();
			}

            list($month, $year) = $this->getMediasFolders($media);
            Storage::path(config('storage.uploads'))->add($year. '/' . $month)->deleteFile($media->filename);

			$this->medias->deleteIfExists($id);
            $this->log(__('media_deleted'));
            redirect()->back()->withToast('success', __('media_deleted'))->go();
		} else {
			foreach (explode(',', $request->items) as $id) {
                $media = $this->medias->findSingle($id);
                
                if ($media !== false) {
                    list($month, $year) = $this->getMediasFolders($media);
                    Storage::path(config('storage.uploads'))->add($year. '/' . $month)->deleteFile($media->filename);

                    $this->medias->deleteIfExists($id);
                }
            }
			
            $this->log(__('medias_deleted'));
            $this->toast('success', __('medias_deleted'));
            response()->json(['redirect' => route('medias.index')]);
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
