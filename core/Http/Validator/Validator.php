<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Validator;

use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Http\Request;
use Core\Http\Response;
use GUMP;
use Spatie\StructureDiscoverer\Discover;

/**
 * Request fields validator.
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

        if (! empty($rules)) {
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

    public function validate(Request $request, ?Response $response = null): self
    {
        $this->inputs = $request->inputs();
        $this->rules = empty($this->rules) ? $this->rules() : $this->rules;
        $this->messages = empty($this->messages) ? $this->messages() : $this->messages;
        $this->errors = GUMP::is_valid($this->inputs, $this->rules, $this->messages);

        if ($this->failed() && ! is_null($response)) {
            $this->validationFailed($request, $response);
        }

        return $this;
    }

    public function validationFailed(Request $request, ?Response $response = null): void
    {
        if ($request->isJson()) {
            $response?->json([
                'status' => ResponseStatus::ERROR,
                'data' => $this->errors()
            ])
            ->send(HttpCode::BAD_REQUEST);
        }

        $response
            ?->back()
            ->withErrors($this->errors())
            ->withInputs($this->inputs)
            ->send(HttpCode::BAD_REQUEST);
    }

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }

    public function failed(): bool
    {
        return is_array($this->errors);
    }

    /**
     * Generate errors messages array according to input field name.
     *
     * To work properly on custom errors messages you must explicitly define the
     * input field name as {field} in your error message string
     */
    public function errors(): array
    {
        $errors = [];

        if (! $this->failed()) {
            return $errors;
        }

        foreach ($this->errors as $error) {
            foreach ($this->inputs as $key => $value) {
                if (strpos(strtolower($error), strval($key))) {
                    $error = str_replace(['<span class="gump-field">', '</span>'], ['', ''], $error);
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
