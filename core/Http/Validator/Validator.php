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
class Validator implements ValidatorInterface
{
    protected static array $rules = [];
    protected static array $messages = [];

    /** @var bool|array */
    protected static $errors;
    protected static array $inputs = [];

    public static function add(string $rule, callable $callback, string $error_message): self
    {
        GUMP::add_validator($rule, $callback, $error_message);

        return new self();
    }

    public function validate(array $inputs): self
    {
        static::$inputs = $inputs;
        static::$errors = GUMP::is_valid(static::$inputs, static::$rules, static::$messages);

        return $this;
    }
    
    public function fails(): bool
    {
        return is_array(static::$errors);
    }
        
    /**
     * Generate errors messages array according to input field name
     * 
     * To work properly on custom errors messages you must explicitly define the
     * input field name as {field} in your error message string
     */
    public function errors(): array
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

    public function inputs(): array
    {
        return static::$inputs;
    }
    
    public function validated(): array
    {
        $validated = [];
        $inputs = array_keys(static::$rules);

        foreach ($inputs as $input) {
            foreach (static::$inputs as $key => $value) {
                if ($input === $key) {
                    $validated = array_merge($validated, [$key => $value]);
                }
            }
        }

        return $validated;
    }
    
    public function redirectBackOnFail(Response $response)
    {
        return !$this->fails() ? $this 
            : $response->redirect()->back()->withErrors($this->errors())->withInputs($this->inputs())->go();
    }
}
