<?php

namespace App\Database\Models;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Database\Model;
use Framework\Support\Encryption;

class Users
{
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'users';

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
     * retrieves user by email
     *
     * @param  string $email
     * @return mixed
     */
    public static function findSingleByEmail(string $email)
    {
        return self::model()->findSingleBy('email', $email);
    }
    
    /**
     * retrieves all users except authenticate
     *
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public static function paginate(int $items_per_pages = 20): \Framework\Support\Pager
    {
        return self::model()
            ->find('!=', Auth::get()->id)
            ->oldest()
            ->paginate($items_per_pages);
    }
    
    /**
     * retrieves active users count
     *
     * @return int
     */
    public static function activeCount(): int
    {
        return self::model()
            ->count()
            ->where('id', '!=', Auth::get()->id)
            ->and('active', 1)
            ->single()
            ->value;
    }

    /**
     * store user
     *
     * @param  \Framework\Http\Request $request
     * @return int
     */
    public static function store(Request $request): int
    {
        return self::model()
            ->insert([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'company' => $request->company,
                'password' => Encryption::hash($request->password),
                'active' => 1,
                'role' => Roles::ROLE[1]
            ]);
    }
    
    /**
     * update user
     *
     * @param  \Framework\Http\Request $request
     * @param  int $id
     * @return bool
     */
    public static function update(Request $request, int $id): bool
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'company' => $request->company,
            'active' => $request->account_state
		];
		
		if ($request->has('password')) {
			$data['password'] = Encryption::hash($request->password);
		}

        return self::model()->updateIfExists($id, $data);
    }
    
    /**
     * delete user
     *
     * @param  \Framework\Http\Request $request
     * @param  int $id
     * @return bool
     */
    public static function delete(Request $request, ?int $id = null): bool
    {
        return is_null($id) 
            ? self::model()->deleteBy('id', 'in', explode(',', $request->items))
            : self::model()->deleteIfExists($id);
    }

    /**
     * update user settings
     *
     * @param  \Framework\Http\Request $request
     * @param  int $id
     * @return bool
     */
    public static function updateSettings(Request $request, int $id): bool
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'country' => $request->country,
            'company' => $request->inputs('company', ''),
            'phone' => $request->phone,
            'two_steps' => $request->exists('two_steps') ? 1 : 0,
            'lang' => $request->lang,
            'timezone' => $request->timezone,
            'currency' => $request->currency,
            'dark_theme' => $request->exists('dark_theme') ? 1 : 0,
            'alerts' => $request->exists('alerts') ? 1 : 0,
            'email_notifications' => $request->exists('email_notifications') ? 1 : 0
		];
		
		if ($request->has('password')) {
			$data['password'] = Encryption::hash($request->password);
		}

        return self::model()->updateIfExists($id, $data);
    }
}
