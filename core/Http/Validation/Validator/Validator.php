<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Validation\Validator;

use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Somnambulist\Components\Validation\Factory as RakitValidator;
use Somnambulist\Components\Validation\Rule;
use Somnambulist\Components\Validation\Validation;
use Spatie\StructureDiscoverer\Discover;

/**
 * Request data validator.
 */
class Validator implements ValidatorInterface
{
    protected Validation $validation;

    protected RakitValidator $validator;

    public function __construct(
        protected array $rules = [],
        protected array $messages = [],
    ) {
        $this->validator = new RakitValidator;
        $rules = Discover::in(config('storage.rules'))->classes()->get();

        if (! empty($rules)) {
            foreach ($rules as $rule) {
                /** @var Rule $rule */
                $rule = new $rule;

                $this->validator->addRule($rule->name(), $rule);
            }
        }
    }

    public static function make(array $rules = [], array $messages = []): static
    {
        // @phpstan-ignore-next-line
        return new static($rules, $messages);
    }

    public function validate(): self
    {
        if (empty($this->rules)) {
            $this->rules = $this->rules();
        }

        if (empty($this->messages)) {
            $this->messages = $this->messages();
        }

        $this->validation = $this->validator->make(
            request()->inputs(),
            $this->rules,
        );

        $this->formatErrorMessages();

        $this->validation->validate();

        if ($this->validation->fails()) {
            $this->validationFailed();
        }

        $this->validationSucceeded();

        return $this;
    }

    public function failed(): bool
    {
        return $this->validation->fails();
    }

    public function errors(): array
    {
        return $this->validation->errors()->firstOfAll(dotNotation: true);
    }

    public function inputs(string|array|null $name = null): array|string|null
    {
        $inputs = $this->validation->getValidatedData();

        if (is_null($name)) {
            return $inputs;
        }

        if (is_string($name)) {
            return $inputs[$name] ?? null;
        }

        return array_intersect_key($inputs, array_flip($name));
    }

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }

    public function validationFailed(): void
    {
        if (request()->isJson()) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'data' => $this->errors(),
            ])->send(HttpCode::BAD_REQUEST);
        }

        response()->back()
            ->withErrors($this->errors())
            ->withInputs($this->validation->getValidatedData())
            ->send();
    }

    public function validationSucceeded(): void
    {
        //
    }

    protected function formatErrorMessages()
    {
        foreach ($this->messages as $input => $messages) {
            foreach ($messages as $rule => $message) {
                $this->validation->messages()->replace(config('app.lang'), $input.':'.$rule, $message);
            }
        }
    }
}
