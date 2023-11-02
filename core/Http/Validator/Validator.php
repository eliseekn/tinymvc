<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Validator;

use GUMP;
use Core\Http\Response;
use Spatie\StructureDiscoverer\Discover;

/**
 * Request fields validator
 */
class Validator implements ValidatorInterface
{
    public function __construct(
        protected array $rules = [],
        protected array $messages = [],
        protected array $inputs = [],
        protected mixed $errors = null,
    ) {
        $rules = Discover::in(config('storage.rules'))->classes()->get();

        if (!empty($rules)) {
            foreach ($rules as $rule) {
                $rule = new $rule();

                GUMP::add_validator(
                    $rule->name,
                    $rule->rule(...),
                    $rule->errorMessage
                );
            }
        }
    }

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
                ->send();
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

        if (!$this->fails()) {
            return $errors;
        }

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
