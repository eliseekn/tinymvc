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
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;
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

    protected array $inputs;

    public function __construct(
        protected array $rules = [],
        protected array $messages = [],
    ) {
        $this->validator = new RakitValidator;
        $rules = Discover::in(config('storage.rules'))->classes()->get();
        $this->inputs = request()->inputs()->get();

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

    public function beforeValidation(): array
    {
        return [];
    }

    public function afterValidation(): array
    {
        return [];
    }

    public function validate(): self
    {
        if (empty($this->rules)) {
            $this->rules = $this->rules();
        }

        if (empty($this->messages)) {
            $this->messages = $this->messages();
        }

        $this->inputs = array_merge($this->inputs, $this->beforeValidation());

        $this->validation = $this->validator->make($this->inputs, $this->rules);

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

    public function validated(string|array|null $name = null): array|string|null
    {
        $inputs = $this->validation->getValidatedData();
        $inputs = array_merge($inputs, $this->afterValidation());

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
            new JsonResponse([
                'status' => ResponseStatus::ERROR,
                'data' => $this->errors(),
            ], HttpCode::BAD_REQUEST)->send();
        }

        new RedirectResponse()
            ->toBack()
            ->withErrors($this->errors())
            ->withInputs($this->validation->getValidatedData())
            ->setStatusCode()
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
