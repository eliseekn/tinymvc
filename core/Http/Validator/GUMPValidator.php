<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Validator;

use GUMP;
use Core\Http\Response\Response;
use Core\Http\Validator\ValidatorInterface;

/**
 * Request fields validator
 */
class GUMPValidator implements ValidatorInterface
{
    protected static array $rules = [];
    protected static array $messages = [];

    /** @var bool|array */
    protected static $errors;
    protected static array $inputs = [];

    public static function add(string $rule, callable $callback, string $error_message)
    {
        GUMP::add_validator($rule, $callback, $error_message);
    }

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
    
    public function redirectBackOnFail(Response $response)
    {
        return !$this->fails() ? $this 
            : $response->redirect()->back()->withErrors($this->errors())->withInputs($this->inputs())->go();
    }
}
