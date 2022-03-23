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
    /** @var bool|array */
    protected $errors;
    protected array $inputs = [];

    public function validate(array $inputs): self
    {
        $this->inputs = $inputs;
        $this->errors = GUMP::is_valid($this->inputs, $this->rules(), $this->messages());

        if ($this->fails()) (new Response())->redirect()->back()->withErrors($this->errors())->withInputs($this->inputs)->go();
        return $this;
    }

    public function rules(): array
    {
        return [];
    }
    
    public function messages(): array
    {
        return [];
    }
    
    public function addCustomRule(string $rule, callable $callback, string $error_message): self
    {
        GUMP::add_validator($rule, $callback, $error_message);
        return $this;
    }

    public function fails(): bool
    {
        return is_array($this->errors);
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

        foreach ($this->errors as $error) {
            foreach ($this->inputs as $key => $value) {
                if (strpos(strtolower($error), strval($key))) {
                    $errors = array_merge($errors, [$key => $error]);
                }
            }
        }

        return $errors;
    }

    public function validated(): array
    {
        $validated = [];
        $inputs = array_keys($this->rules());

        foreach ($inputs as $input) {
            foreach ($this->inputs as $key => $value) {
                if ($input === $key) {
                    $validated = array_merge($validated, [$key => $value]);
                }
            }
        }

        return $validated;
    }
}
