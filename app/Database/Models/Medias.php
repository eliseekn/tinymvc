<?php

namespace App\Database\Models;

use Carbon\Carbon;
use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Database\Model;

class Medias
{
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'medias';

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
     * create new model instance 
     *
     * @return \Framework\Database\Model
     */
    private static function model(): \Framework\Database\Model
    {
        return new Model(self::$table);
    }

    /**
     * retrieves all medias
     *
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public static function paginate(int $items_per_pages = 10): \Framework\Support\Pager
    {
        return self::model()
            ->findBy('user_id', Auth::get('id'))
            ->oldest()
            ->paginate($items_per_pages);
    }
    
    /**
     * retrieves medias count by type
     *
     * @return array
     */
    public static function findByType(): array
    {
        $medias = self::model()->findAllBy('user_id', Auth::get('id'));

        $images = 0; $videos = 0; $audios = 0;

        foreach ($medias as $media) {
            if (self::getMediaType($media->filename) === 'image') {
                $images++;
            } elseif (self::getMediaType($media->filename) === 'video') {
                $videos++;
            } elseif (self::getMediaType($media->filename) === 'audio') {
                $audios++;
            } 
        }

        return [$images, $videos, $audios];
    }

    /**
     * search for medias
     *
     * @param  string $q
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public static function paginateQuery(string $q, int $items_per_pages = 10): \Framework\Support\Pager
    {
        return self::model()
            ->findBy('user_id', Auth::get('id'))
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
    public static function findByTypeQuery(string $q): array
    {
        $medias = self::model()
            ->findBy('user_id', Auth::get('id'))
            ->and('filename', 'like', $q)
            ->or('created_at', 'like', $q)
            ->all();

        $images = 0; $videos = 0; $audios = 0;

        foreach ($medias as $media) {
            if (self::getMediaType($media->filename) === 'image') {
                $images++;
            } elseif (self::getMediaType($media->filename) === 'video') {
                $videos++;
            } elseif (self::getMediaType($media->filename) === 'audio') {
                $audios++;
            } 
        }

        return [$images, $videos, $audios];
    }
    
    /**
     * store media
     *
     * @param  mixed $media
     * @return int
     */
    public static function store($media): int
    {
        return self::model()
            ->insert([
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
    public static function update(Request $request, $id, $year, $month): bool
    {
        return self::model()
            ->updateIfExists($id, [
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
    private static function getMediaType(string $filename): string
    {
        if (in_array(get_file_extension($filename), self::TYPE[0])) {
            return 'image';
        } elseif (in_array(get_file_extension($filename), self::TYPE[1])) {
            return 'video';
        } elseif (in_array(get_file_extension($filename), self::TYPE[2])) {
            return 'audio';
        }
    }
}
