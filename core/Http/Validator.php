<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http;

use GUMP;
use Core\Http\Response\Response;

/**
 * Request fields validator
 */
class Validator extends Response
{
    protected static $rules = [];
    protected static $messages = [];
    protected static $errors;
    protected static $inputs = [];

    public static function validate(array $inputs, array $rules = [], array $messages = []): self
    {
        $rules = empty($rules) ? static::$rules : $rules;
        $messages = empty($messages) ? static::$messages : $messages;
        static::$inputs = $inputs;
        static::$errors = GUMP::is_valid(static::$inputs, $rules, $messages);

        return new self();
    }
    
    public function fails()
    {
        return is_array(static::$errors);
    }
        
    /**
     * Generate errors messages array according to input field name
     * 
     * To work properly on custom errors messages you must explicitly define the
     * input field name as {field} in your error message string
     *
     * @return array
     */
    public function errors()
    {
        $errors = [];

        if (!$this->fails()) return $errors;

        foreach (static::$errors as $error) {
            foreach (static::$inputs as $key => $value) {
                if (strpos(strtolower($error), strval($key))) {
                    $errors = array_merge($errors, [$key => $error]);
                }
            }
        }

        return $errors;
    }
    
    public function inputs()
    {
        return static::$inputs;
    }
    
    public function redirectBackOnFail()
    {
        return !$this->fails() ? $this 
            : $this->redirect()->back()->withErrors($this->errors())->withInputs($this->inputs())->go();
    }
}
