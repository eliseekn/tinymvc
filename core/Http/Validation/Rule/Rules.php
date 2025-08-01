<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Validation\Rule;

use Somnambulist\Components\Validation\Rule as ValidationRule;

class Rules
{
    public const REQUIRED = 'required';

    public const REQUIRED_IF = 'required_if';

    public const REQUIRED_UNLESS = 'required_unless';

    public const REQUIRED_WITH = 'required_with';

    public const REQUIRED_WITHOUT = 'required_without';

    public const REQUIRED_WITH_ALL = 'required_with_all';

    public const REQUIRED_WITHOUT_ALL = 'required_without_all';

    public const MIMES = 'mimes';

    public const EMAIL = 'email';

    public const ALPHA = 'alpha';

    public const ALPHA_NUMERIC = 'alpha_num';

    public const ALPHA_DASH = 'alpha_dash';

    public const ALPHA_SPACES = 'alpha_spaces';

    public const NUMERIC = 'numeric';

    public const INTEGER = 'integer';

    public const DIGITS = 'digits';

    public const DIGITS_BETWEEN = 'digits_between';

    public const URL = 'url';

    public const JSON = 'json';

    public const MAX = 'max';

    public const MIN = 'min';

    public const IN = 'in';

    public const NOT_IN = 'not_in';

    public const LENGTH = 'length';

    public const BETWEEN = 'between';

    public const BOOLEAN = 'boolean';

    public const DATE = 'date';

    public const EXTENSION = 'extension';

    public const REGEX = 'regex';

    public const NULLABLE = 'nullable';

    public const SOMETIMES = 'sometimes';

    public const ARRAY = 'array';

    public const STRING = 'string';

    public const UPLOADED_FILE = 'uploaded_file';

    public const IP = 'ip';

    public const IPV4 = 'ipv4';

    public const IPV6 = 'ipv6';

    public const AFTER = 'after';

    public const BEFORE = 'before';

    public const PRESENT = 'present';

    public const ACCEPTED = 'accepted';

    public const REJECTED = 'rejected';

    public const SAME = 'same';

    public const DIFFERENT = 'different';

    public const FLOAT = 'float';

    public const UUID = 'uuid';

    public const PROHIBITED = 'prohibited';

    public const PROHIBITED_IF = 'prohibited_if';

    public const PROHIBITED_UNLESS = 'prohibited_unless';

    public const UPPERCASE = 'uppercase';

    public const LOWERCASE = 'lowercase';

    protected static array $rules = [];

    public static function add(string $name, array|string $rules): static
    {
        static::$rules[$name] = implode('|', parse_array($rules));

        // @phpstan-ignore-next-line
        return new static;
    }

    public function make(): array
    {
        return static::$rules;
    }

    public function after(string $data): string
    {
        return "after:$data";
    }

    public function before(string $data): string
    {
        return "before:$data";
    }

    public static function digits(string $data): string
    {
        return "digits:$data";
    }

    public static function digitsBetween(int $min, int $max): string
    {
        return "digits_between:$min,$max";
    }

    public static function in(array $data): string
    {
        return 'in:'.implode(',', $data);
    }

    public static function notIn(array $data): string
    {
        return 'not_in:'.implode(',', $data);
    }

    public static function boolean(bool $strict = false): string
    {
        return $strict ? 'boolean:strict' : 'boolean';
    }

    public static function max(int $value): string
    {
        return "max:$value";
    }

    public static function min(int $value): string
    {
        return "min:$value";
    }

    public static function length(int $value): string
    {
        return "length:$value";
    }

    public static function between(int $min, int $max): string
    {
        return "between:$min,$max";
    }

    public static function date(?string $format = null): string
    {
        return is_null($format) ? 'date' : "date:$format";
    }

    public static function extension(array $data): string
    {
        return 'extension:'.implode(',', $data);
    }

    public static function regex(string $pattern): string
    {
        return "regex:$pattern";
    }

    public static function requiredIfField(string $field, $value): string
    {
        return "required_if:$field,".implode(',', parse_array($value));
    }

    public static function requiredUnless(string $field, $value): string
    {
        return "required_unless:$field,".implode(',', parse_array($value));
    }

    public static function requiredWith(array $fields): string
    {
        return 'required_with:'.implode(',', $fields);
    }

    public static function requiredWithout(array $fields): string
    {
        return 'required_without:'.implode(',', $fields);
    }

    public static function same(string $field): string
    {
        return "same:$field";
    }

    public static function different(string $field): string
    {
        return "different:$field";
    }

    public static function mimes(array $types): string
    {
        return 'mimes:'.implode(',', $types);
    }

    public static function custom(ValidationRule $rule, $data = null): string
    {
        $rule = new $rule;

        if (is_null($data)) {
            return $rule->name();
        }

        if (is_array($data)) {
            return $rule->name().':'.implode(',', $data);
        }

        return $rule->name().':'.$data;
    }
}
