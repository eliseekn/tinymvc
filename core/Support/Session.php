<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

/**
 * Manage session
 */
class Session
{
    public function __construct()
    {
        $this->start();
    }

    protected function start(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.gc_maxlifetime', config('security.session.lifetime'));
            session_set_cookie_params(config('security.session.lifetime'));
			session_start();
		}
	}

    public function create(string $name, $data): void
    {
		$_SESSION[strtolower(config('app.name')) . '_' . $name] = $data;
    }
    
    public function get(string $name, $default = null): mixed
    {
        return $_SESSION[strtolower(config('app.name')) . '_' . $name] ?? $default;
    }
    
    public function has(string $name): bool
    {
		return isset($_SESSION[strtolower(config('app.name')) . '_' . $name]);
    }
    
    public function forget(array|string $names): void
    {
        $names = parse_array($names);

        foreach ($names as $name) {
            unset($_SESSION[strtolower(config('app.name')) . '_' . $name]);
        }
    }
    
    /**
     * Get session data and close it
     */
    public function pull(string $name): mixed
    {
        $data = $this->get($name);
        $this->forget($name);
        return $data;
    }
    
    /**
     * Add data to session or create if empty
     */
    public function push(string $name, $data, $default = null): void
    {
        $stored_data = $this->get($name, $default);

        if (empty($stored_data)) {
            $stored_data = $data;
        } else {
            if (is_array($stored_data)) {
                $stored_data = array_merge($stored_data, $data);
            } elseif (is_string($stored_data)) {
                $stored_data .= $data;
            } elseif (is_numeric($stored_data)) {
                $stored_data += $data;
            } elseif (is_object($stored_data)) {
                $stored_data = (object) array_merge((array) $stored_data, (array) $data);
            }
        }

        $_SESSION[strtolower(config('app.name')) . '_' . $name] = $stored_data;
    }
}
