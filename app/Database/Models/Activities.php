<?php

namespace App\Database\Models;

use App\Helpers\Auth;
use Framework\Database\Model;
use Framework\Http\Request;

class Activities
{
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'activities';

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
     * retrieves all activites
     * 
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public static function paginate($items_per_pages = 20): \Framework\Support\Pager
    {
        return self::model()
            ->select(['id', 'user', 'url', 'ip_address', 'action', 'created_at'])
            ->subQuery(function ($query) {
                if (Auth::get()->role !== Roles::ROLE[0]) {
                    $query->where('user', Auth::get()->email);
                }
            })
            ->oldest()
            ->paginate($items_per_pages);
    }
    
    /**
     * delete activities by id
     *
     * @param  \Framework\Http\Request $request
     * @return bool
     */
    public static function deleteById(Request $request): bool
    {
        return self::model()->deleteBy('id', 'in', explode(',', $request->items));
    }
}
