<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Http;

use Exception;
use Framework\Routing\Route;
use Framework\Support\Alert;
use Framework\Support\Cookies;
use Framework\Support\Session;

/**
 * Handle HTTP redirection
 */
class Redirect
{
    /**
     * url to redirect
     *
     * @var string $url
     */
    protected static $url = '';

    /**
     * @var \Framework\Support\Alert $alert
     */
    protected $alert;
    
    /**
     * process redirection
     *
     * @return void
     */
    private function redirect(): void
    {
        redirect_to(self::$url);
    } 

    /**
     * redirect to url 
     *
     * @param  string $url
     * @param  array|string $params
     * @return \Framework\Http\Redirect
     */
    public static function url(string $url = '/', $params = null): self
    {
        $params = is_array($params) ? (empty($params) ? '' : implode('/', $params)) : $params;
        self::$url = is_null($params) ? $url : $url . '/' . $params;
        return new self();
    }

    /**
     * go to previous page
     *
     * @return \Framework\Http\Redirect
     */
    public static function back(): self
    {
        $history = Session::get('history');

        if (!empty($history)) {
            end($history);
            self::$url = prev($history);
        }

        return new self();
    }

    /**
     * redirects only
     *
     * @return void
     */
    public function only(): void
    {
        $this->redirect();
    }
    
    /**
     * redirects and create session data
     *
     * @param  string $key
     * @param  mixed $data
     * @return \Framework\Http\Redirect
     */
    public function with(string $key, $data): self
    {
        Session::create($key, $data);
        return $this;
    }
    
    /**
     * redirects and create cookie
     *
     * @param  string $name
     * @param  string $value
     * @param  int $expires in seconds
     * @param  bool $secure
     * @param  string $domain
     * @return \Framework\Http\Redirect
     */
    public function withCookie(string $name, string $value, int $expires = 3600, bool $secure = false, string $domain = ''): self
    {
        Cookies::create($name, $value, $expires, $secure, $domain);
        return $this;
    }
    
    /**
     * redirects with errors session data
     *
     * @param  array $errors
     * @return \Framework\Http\Redirect
     */
    public function withErrors(array $errors): self
    {
        Session::create('errors', $errors);
        return $this;
    }

    /**
     * redirects with inputs session data
     *
     * @param  array $inputs
     * @return \Framework\Http\Redirect
     */
    public function withInputs(array $inputs): self
    {
        Session::create('inputs', $inputs);
        return $this;
    }

    /**
     * redirects with default alert message
     *
     * @param  mixed $message
     * @param  bool $dismiss
     * @return \Framework\Http\Redirect
     */
    public function withAlert($message, bool $dismiss = true): self
    {
        $this->alert = Alert::default($message, $dismiss);
        return $this;
    }

    /**
     * redirects with toast message
     *
     * @param  mixed $message
     * @return \Framework\Http\Redirect
     */
    public function withToast($message): self
    {
        $this->alert = Alert::toast($message);
        return $this;
    }
    
    /**
     * redirects with popup message
     *
     * @param  mixed $message
     * @return \Framework\Http\Redirect
     */
    public function withPopup($message): self
    {
        $this->alert = Alert::popup($message);
        return $this;
    }
        
    /**
     * success
     *
     * @param  string $title
     * @return void
     */
    public function success(string $title = 'Success'): void
    {
        $this->alert->success($title);
        $this->redirect();
    }
        
    /**
     * error
     *
     * @param  string $title
     * @return void
     */
    public function error(string $title = 'Error'): void
    {
        $this->alert->error($title);
        $this->redirect();
    }
        
    /**
     * info
     *
     * @param  string $title
     * @return void
     */
    public function info(string $title = 'Info'): void
    {
        $this->alert->info($title);
        $this->redirect();
    }
        
    /**
     * warning
     *
     * @param  string $title
     * @return void
     */
    public function warning(string $title = 'Warning'): void
    {
        $this->alert->warning($title);
        $this->redirect();
    }
}
