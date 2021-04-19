<?php

namespace App\Database\Repositories;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\System\Encryption;
use Framework\Database\Repository;

class Users extends Repository
{
    /**
     * name of table
     *
     * @var string
     */
    public $table = 'users';

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
     * retrieves user by email
     *
     * @param  string $email
     * @return mixed
     */
    public function findSingleByEmail(string $email)
    {
        return $this->findSingleBy('email', $email);
    }
    
    /**
     * retrieves all users except authenticate
     *
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public function findAllPaginate(int $items_per_pages = 10): \Framework\Support\Pager
    {
        return $this->find('!=', Auth::get('id'))
            ->oldest()
            ->paginate($items_per_pages);
    }
    
    /**
     * retrieves active users count
     *
     * @return int
     */
    public function activeCount(): int
    {
        return $this->count()
            ->where('id', '!=', Auth::get('id'))
            ->and('active', 1)
            ->single()
            ->value;
    }

    /**
     * store user
     *
     * @param  \Framework\Http\Request $request
     * @param  int $active
     * @param  string $role
     * @return int
     */
    public function store(Request $request, int $active = 1, string $role = Roles::ROLE[1]): int
    {
        return $this->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'password' => Encryption::hash($request->password),
            'active' => $active,
            'role' => $role
        ]);
    }
    
    /**
     * update user
     *
     * @param  \Framework\Http\Request $request
     * @param  int $id
     * @return bool
     */
    public function refresh(Request $request, int $id): bool
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'company' => $request->company,
            'active' => $request->account_state
		];
		
		if ($request->filled('password')) {
			$data['password'] = Encryption::hash($request->password);
		}

        return $this->updateIfExists($id, $data);
    }
    
    /**
     * delete user
     *
     * @param  \Framework\Http\Request $request
     * @param  int $id
     * @return bool
     */
    public function flush(Request $request, ?int $id = null): bool
    {
        return is_null($id) 
            ? $this->deleteBy('id', 'in', explode(',', $request->items))
            : $this->deleteIfExists($id);
    }

    /**
     * update user settings
     *
     * @param  \Framework\Http\Request $request
     * @param  int $id
     * @return bool
     */
    public function updateSettings(Request $request, int $id): bool
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'country' => $request->country,
            'company' => $request->inputs('company', ''),
            'phone' => $request->phone,
            'two_steps' => $request->has('two_steps') ? 1 : 0,
            'lang' => $request->lang,
            'timezone' => $request->timezone,
            'currency' => $request->currency,
            'dark_theme' => $request->has('dark_theme') ? 1 : 0,
            'alerts' => $request->has('alerts') ? 1 : 0,
            'email_notifications' => $request->has('email_notifications') ? 1 : 0
		];
		
		if ($request->has('password')) {
			$data['password'] = Encryption::hash($request->password);
		}

        return $this->updateIfExists($id, $data);
    }
    
    /**
     * retrieves data from date range
     *
     * @param  mixed $start
     * @param  mixed $end
     * @return array
     */
    public function findAllDateRange($date_start, $date_end): array
    {
        return $this->select()
            ->subQuery(function($query) use ($date_start, $date_end) {
                if (!empty($date_start) && !empty($date_end)) {
                    $query->whereBetween('created_at', $date_start, $date_end);
                }
            })
            ->oldest()
            ->all();
    }
}
