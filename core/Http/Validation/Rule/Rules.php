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

    public static function required(): string
    {
        return 'required';
    }

    public static function requiredWithAll(): string
    {
        return 'required_with_all';
    }

    public static function requiredWithoutAll(): string
    {
        return 'required_without_all';
    }

    public static function email(): string
    {
        return 'email';
    }

    public static function alpha(): string
    {
        return 'alpha';
    }

    public static function alphaNumeric(): string
    {
        return 'alpha_num';
    }

    public static function alphaDash(): string
    {
        return 'alpha_dash';
    }

    public static function alphaSpaces(): string
    {
        return 'alpha_spaces';
    }

    public static function numeric(): string
    {
        return 'numeric';
    }

    public static function integer(): string
    {
        return 'integer';
    }

    public static function digitsConst(): string
    {
        return 'digits';
    }

    public static function digitsBetweenConst(): string
    {
        return 'digits_between';
    }

    public static function url(): string
    {
        return 'url';
    }

    public static function json(): string
    {
        return 'json';
    }

    public static function maxConst(): string
    {
        return 'max';
    }

    public static function minConst(): string
    {
        return 'min';
    }

    public static function inConst(): string
    {
        return 'in';
    }

    public static function notInConst(): string
    {
        return 'not_in';
    }

    public static function lengthConst(): string
    {
        return 'length';
    }

    public static function betweenConst(): string
    {
        return 'between';
    }

    public static function booleanConst(): string
    {
        return 'boolean';
    }

    public static function dateConst(): string
    {
        return 'date';
    }

    public static function extensionConst(): string
    {
        return 'extension';
    }

    public static function regexConst(): string
    {
        return 'regex';
    }

    public static function nullable(): string
    {
        return 'nullable';
    }

    public static function sometimes(): string
    {
        return 'sometimes';
    }

    public static function array(): string
    {
        return 'array';
    }

    public static function string(): string
    {
        return 'string';
    }

    public static function uploadedFile(): string
    {
        return 'uploaded_file';
    }

    public static function ip(): string
    {
        return 'ip';
    }

    public static function ipv4(): string
    {
        return 'ipv4';
    }

    public static function ipv6(): string
    {
        return 'ipv6';
    }

    public static function afterConst(): string
    {
        return 'after';
    }

    public static function beforeConst(): string
    {
        return 'before';
    }

    public static function present(): string
    {
        return 'present';
    }

    public static function accepted(): string
    {
        return 'accepted';
    }

    public static function rejected(): string
    {
        return 'rejected';
    }

    public static function sameConst(): string
    {
        return 'same';
    }

    public static function differentConst(): string
    {
        return 'different';
    }

    public static function float(): string
    {
        return 'float';
    }

    public static function uuid(): string
    {
        return 'uuid';
    }

    public static function prohibited(): string
    {
        return 'prohibited';
    }

    public static function prohibitedIf(): string
    {
        return 'prohibited_if';
    }

    public static function prohibitedUnless(): string
    {
        return 'prohibited_unless';
    }

    public static function uppercase(): string
    {
        return 'uppercase';
    }

    public static function lowercase(): string
    {
        return 'lowercase';
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
