<?php

namespace App\Controllers\Admin;

use App\Helpers\ReportHelper;
use Framework\Routing\Controller;
use App\Database\Models\GalleriesModel;
use App\Database\Models\MediasModel;

class GalleriesController extends Controller
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
	{
		$this->render('admin/resources/galleries/index', [
            'galleries' => GalleriesModel::select()->orderDesc('created_at')->paginate(20)
        ]);
	}

    /**
	 * display new page
	 * 
	 * @return void
	 */
    public function new(): void
	{
		$this->render('admin/resources/galleries/new', [
            'medias' => MediasModel::select()->orderDesc('created_at')->all()
        ]);
	}
	
	/**
	 * display edit page
	 * 
	 * @param  int $id
	 * @return void
	 */
    public function edit(int $id): void
	{
		$this->render('admin/resources/galleries/edit', [
            'gallery' => GalleriesModel::findSingle($id)
        ]);
	}

	/**
	 * create
	 *
	 * @return void
	 */
    public function create(): void
	{
        $id = GalleriesModel::insert([
            //
        ]);

		$this->redirect('admin/resources/galleries/view', $id)->only();
	}
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function view(int $id): void
	{
		$this->render('admin/resources/galleries/view', [
            'gallery' => GalleriesModel::findSingle($id)
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
        GalleriesModel::update([
            //
        ])->where('id', $id)->persist();

		$this->redirect('admin/resources/galleries/view', $id)->only();
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
			if (!GalleriesModel::find($id)->exists()) {
				$this->redirect()->only();
			}
	
			GalleriesModel::deleteWhere('id', $id);
            $this->redirect('admin/resources/galleries')->only();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				GalleriesModel::deleteWhere('id', $id);
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
            $this->redirect('admin/resources/galleries')->only();
		}

		if (!$file->isUploaded()) {
			$this->redirect('admin/resources/galleries')->only();
		}

		ReportHelper::import($file->getTempFilename(), GalleriesModel::class, [
			//
		]);

		$this->redirect('admin/resources/galleries')->only();
	}
	
	/**
	 * export data
	 *
	 * @return void
	 */
    public function export(): void
	{
		if ($this->request->has('date_start') && $this->request->has('date_end')) {
			$galleries = GalleriesModel::select()
                ->whereBetween('created_at', $this->request->date_start, $this->request->date_end)
                ->orderDesc('created_at')
                ->all();
		} else {
			$galleries = GalleriesModel::select()->orderAsc('name')->all();
        }
        
        $filename = 'galleries_' . date('Y_m_d') . '.' . $this->request->file_type;

		ReportHelper::export($filename, $galleries, [
			//
		]);
	}
}
