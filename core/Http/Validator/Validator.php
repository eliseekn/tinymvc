<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Validator;

use GUMP;
use Core\Http\Response;

/**
 * Request fields validator
 */
class Validator implements ValidatorInterface
{
    /**
     * @param bool|array|null $errors
     */
    public function __construct(
        protected array $rules = [],
        protected array $messages = [],
        protected array $inputs = [],
        protected mixed $errors = null,
    ) {}

    public function validate(array $inputs, ?Response $response = null): self
    {
        $this->inputs = $inputs;
        $this->rules = empty($this->rules) ? $this->rules() : $this->rules;
        $this->messages = empty($this->messages) ? $this->messages() : $this->messages;
        $this->errors = GUMP::is_valid($this->inputs, $this->rules, $this->messages);

        if ($this->fails() && !is_null($response)) {
            $response
                ->back()
                ->withErrors($this->errors())
                ->withInputs($this->inputs)
                ->send(302);
        }
        
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
