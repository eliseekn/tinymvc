<?php

namespace App\Controllers\Admin;

use App\Helpers\DateHelper;
use App\Helpers\ReportHelper;
use Framework\Routing\Controller;
use Framework\Http\Redirect;
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
		$this->render('admin/resources/medias/index', [
            'medias' => MediasModel::select()->orderDesc('created_at')->paginate(20)
        ]);
	}

    /**
	 * display new page
	 * 
	 * @return void
	 */
    public function new(): void
	{
		$this->render('admin/resources/medias/new');
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
            'medias' => MediasModel::findSingle($id)
        ]);
	}

	/**
	 * create
	 *
	 * @return void
	 */
    public function create(): void
	{
        $id = MediasModel::insert([
            //
        ]);

        $this->redirect('admin/resources/medias/view', $id)->only();
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
            'medias' => MediasModel::findSingle($id)
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
        MediasModel::update([
            //
        ])->where('id', $id)->persist();

		$this->redirect('admin/resources/medias/view', $id)->only();
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
				$this->redirect()->only();
			}
	
			MediasModel::deleteWhere('id', $id);
            $this->redirect('admin/resources/medias')->only();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				MediasModel::deleteWhere('id', $id);
			}
		}
	}

	/**
	 * import data
	 *
	 * @return void
	 */
    public function import(): void
	{
		$file = $this->request->files('file', ['csv']);

		if (!$file->isAllowed()) {
            $this->redirect('admin/resources/medias')->only();
		}

		if (!$file->isUploaded()) {
			$this->redirect('admin/resources/medias')->only();
		}

		ReportHelper::import($file->getTempFilename(), MediasModel::class, [
			//
		]);

		$this->redirect('admin/resources/medias')->only();
	}
	
	/**
	 * export data
	 *
	 * @return void
	 */
    public function export(): void
	{
		if ($this->request->has('date_start') && $this->request->has('date_end')) {
			$medias = MediasModel::select()
                ->whereBetween('created_at', $this->request->date_start, $this->request->date_end)
                ->orderDesc('created_at')
                ->all();
		} else {
			$medias = MediasModel::select()->orderAsc('name')->all();
        }
        
        $filename = 'medias_' . date('Y_m_d') . '.' . $this->request->file_type;

		ReportHelper::export($filename, $medias, [
			//
		]);
	}
}
