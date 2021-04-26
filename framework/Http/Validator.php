<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Http;

use GUMP;

/**
 * Request fields validator
 */
class Validator
{
    /**
     * rules
     * 
     * @var array $rules
     */
    protected static $rules = [];

    /**
     * custom errors messages
     * 
     * @var array $messages
     */
    protected static $messages = [];

    /**
     * validation errors messages
     * 
     * @var bool|array $errors
     */
    protected static $errors;

    /**
     * request inputs
     * 
     * @var array $inputs
     */
    protected static $inputs = [];

    /**
     * validate inputs 
     *
     * @param  array $inputs
     * @return \Framework\Support\Validator
     */
    public static function validate(array $inputs, array $rules = [], array $messages = []): self
    {
        $validators = empty($rules) ? static::$rules : $rules;
        $error_messages = empty($messages) ? static::$messages : $messages;
        static::$inputs = $inputs;
        static::$errors = GUMP::is_valid(static::$inputs, $validators, $error_messages);

        return new self();
    }
    
    /**
     * check if validation fails
     *
     * @return bool
     */
    public function fails(): bool
    {
        return is_array(static::$errors);
    }
    
    /**
     * get validation errors
     *
     * @return array
     */
    public function errors(): array
    {
        $errors = [];

        foreach ((array) static::$errors as $error) {
            foreach (static::$inputs as $key => $input) {
                if (strpos(strtolower($error), $key)) {
                    $errors = array_merge($errors, [$key => $error]);
                }
            }
        }

        return $errors;
    }
    
    /**
     * get request inputs
     *
     * @return array
     */
    public function inputs(): array
    {
        return static::$inputs;
    }
    
    /**
     * redirect on fail or return validator instance
     *
     * @return self|void
     */
    public function redirectOnFail()
    {
        return !$this->fails() ? $this 
            : redirect()->back()->withErrors($this->errors())->withInputs($this->inputs())->go();
    }
}
