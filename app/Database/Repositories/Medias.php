<?php

namespace App\Database\Repositories;

use Carbon\Carbon;
use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Support\Uploader;
use Framework\Database\Repository;

class Medias extends Repository
{
    /**
     * name of table
     *
     * @var string
     */
    public $table = 'medias';
    
    /**
     * medias extensions constants
     * 
     * @var array
     */
    public const TYPE = [
        0 => ['jpg', 'jpeg', 'png', 'gif', 'svg'],
        1 => ['mp4', 'flv', 'mpeg', 'webm'],
        2 => ['mp3', 'wav']
    ];
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct($this->table);
    }

    /**
     * retrieve all medias by authenticated user
     *
     * @return array
     */
    public function findAllByUser(): array
    {
        return $this->findAllBy('user_id', Auth::get('id'));
    }

    /**
     * retrieves all medias and paginate
     *
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public function findAllPaginate(int $items_per_pages = 10): \Framework\Support\Pager
    {
        return $this->findBy('user_id', Auth::get('id'))
            ->oldest()
            ->paginate($items_per_pages);
    }
    
    /**
     * retrieves medias count by type
     *
     * @return array
     */
    public function findAllByType(): array
    {
        $medias = $this->findAllByUser();

        $images = 0; $videos = 0; $audios = 0; $others = 0;

        foreach ($medias as $media) {
            if ($this->getMediaType($media->filename) === 'image') {
                $images++;
            } else if ($this->getMediaType($media->filename) === 'video') {
                $videos++;
            } else if ($this->getMediaType($media->filename) === 'audio') {
                $audios++;
            } else {
                $others++;
            }
        }

        return [$images, $videos, $audios, $others];
    }

    /**
     * search for medias
     *
     * @param  string $q
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public function findAllPaginateQuery(string $q, int $items_per_pages = 10): \Framework\Support\Pager
    {
        return $this->findBy('user_id', Auth::get('id'))
            ->and('filename', 'like', $q)
            ->or('created_at', 'like', $q)
            ->oldest()
            ->paginate($items_per_pages);
    }

    /**
     * retrieves medias count by type
     *
     * @param  string $q
     * @return array
     */
    public function findAllByTypeQuery(string $q): array
    {
        $medias = $this->findBy('user_id', Auth::get('id'))
            ->and('filename', 'like', $q)
            ->or('created_at', 'like', $q)
            ->all();

        $images = 0; $videos = 0; $audios = 0; $others = 0;

        foreach ($medias as $media) {
            if ($this->getMediaType($media->filename) === 'image') {
                $images++;
            } else if ($this->getMediaType($media->filename) === 'video') {
                $videos++;
            } else if ($this->getMediaType($media->filename) === 'audio') {
                $audios++;
            }  else {
                $others++;
            }
        }

        return [$images, $videos, $audios, $others];
    }
    
    /**
     * store media
     *
     * @param  \Framework\Support\Uploader $media
     * @return int
     */
    public function store(Uploader $media): int
    {
        return $this->insert([
            'user_id' => Auth::get('id'),
            'filename' => $media->filename,
            'url' => url('storage/uploads', [Carbon::now()->year, Carbon::now()->month , $media->filename])
        ]);
    }
    
    /**
     * update media
     *
     * @param  \Framework\Http\Request $request
     * @param  int $id
     * @param  int $year
     * @param  int $month
     * @return bool
     */
    public function refresh(Request $request, $id, $year, $month): bool
    {
        return $this->updateIfExists($id, [
            'filename' => $request->filename,
            'title' => $request->title,
            'description' => $request->description,
            'url' => url('storage/uploads', [$year, $month , $request->filename])
        ]);
    }
    
    /**
     * retrieves media type
     *
     * @param  string $filename
     * @return string
     */
    private function getMediaType(string $filename): string
    {
        if (in_array(get_file_extension($filename), self::TYPE[0])) {
            return 'image';
        } else if (in_array(get_file_extension($filename), self::TYPE[1])) {
            return 'video';
        } else if (in_array(get_file_extension($filename), self::TYPE[2])) {
            return 'audio';
        } else {
            return 'other';
        }
    }
}
