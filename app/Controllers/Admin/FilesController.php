<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use App\Helpers\Activity;
use App\Requests\FileInfos;
use Framework\Support\Alert;
use Framework\Support\Storage;
use Framework\Routing\Controller;
use App\Database\Models\FilesModel;
use App\Helpers\Auth;

class FilesController extends Controller
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
	{
        $files = FilesModel::findBy('user_id', Auth::get()->id)->orderDesc('created_at')->paginate(10);
        $images  = 0; $videos = 0; $sounds = 0; $documents = 0; $others = 0;

        foreach(FilesModel::findAllBy('user_id', Auth::get()->id) as $file) {
            if (in_array(get_file_extension($file->filename), FilesModel::TYPE[0])) {
                $images++;
            } elseif (in_array(get_file_extension($file->filename), FilesModel::TYPE[1])) {
                $videos++;
            } elseif (in_array(get_file_extension($file->filename), FilesModel::TYPE[2])) {
                $sounds++;
            } elseif (in_array(get_file_extension($file->filename), FilesModel::TYPE[3])) {
                $documents++;
            } else {
                $others++;
            }
        }

		$this->render('admin/resources/files/index', compact('files', 'images', 'videos', 'sounds', 'documents', 'others'));
	}

	/**
	 * display edit page
	 * 
	 * @param  int $id
	 * @return void
	 */
    public function edit(int $id): void
	{
		$this->render('admin/resources/files/edit', [
            'file' => FilesModel::findSingle($id)
        ]);
	}

	/**
	 * create
	 *
	 * @return void
	 */
    public function create(): void
	{
        $files = $this->request->files('files', [], true);

        foreach($files as $file) {
            if (!$file->isUploaded()) {
                $this->redirect('admin/resources/files')->withToast(__('import_data_error'))->error();
            }
    
            if (!$file->save(absolute_path('storage.uploads.' . Carbon::now()->year. '.' . Carbon::now()->month))) {
                $this->redirect('admin/resources/files')->withToast(__('upload_error'))->error();
            }

            FilesModel::insert([
                'user_id' => Auth::get()->id,
                'filename' => $file->filename,
                'url' => absolute_url('storage/uploads/' . Carbon::now()->year. '/' . Carbon::now()->month . '/' . $file->filename)
            ]);
        }

        Activity::log(__('files_created'));
        $this->redirect('admin/resources/files')->withToast(__('files_created'))->success();
	}
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function view(int $id): void
	{
		$this->render('admin/resources/files/view', [
            'file' => FilesModel::findSingle($id)
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
        $validator = FileInfos::validate($this->request->inputs());

        if ($validator->fails()) {
            $this->redirect()->withErrors($validator->errors())->withInputs($validator->inputs())
                ->withToast(__('file_not_updated', true))->error();
        }

        $file = FilesModel::findSingle($id);

        //retrieves year and month folders of file
        $folder = explode('/', $file->url);
        end($folder);

        $month = prev($folder);
        $year = prev($folder);

        if (!Storage::path(config('storage.uploads'))->add($year. '/' . $month)->renameFile($file->filename, $this->request->filename)) {
            $this->redirect()->withToast(__('file_not_updated', true))->error();
        }

        FilesModel::update([
            'filename' => $this->request->filename,
            'title' => $this->request->title,
            'description' => $this->request->description,
            'url' => absolute_url('storage/uploads/' . $year. '/' . $month . '/' . $this->request->filename)
        ])->where('id', $id)->persist();

        Activity::log(__('file_updated'));
		$this->redirect()->withToast(__('file_updated'))->success();
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
			if (!FilesModel::find($id)->exists()) {
				$this->redirect()->withToast(__('file_not_found'))->error();
			}
	
			FilesModel::deleteWhere('id', $id);
            Activity::log(__('file_deleted'));
            $this->redirect('admin/resources/files')->withToast(__('file_deleted'))->success();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				FilesModel::deleteWhere('id', $id);
            }
			
            Activity::log(__('files_deleted'));
            Alert::toast(__('files_deleted'))->success();
		}
	}
}
