<?php

declare(strict_types=1);

namespace Core\Http\Validator;

class Rule
{
    public const REQUIRED = 'required';
    public const EMAIL = 'valid_email';
    public const FILE = 'required_file';
    public const ALPHA = 'alpha';
    public const ALPHA_NUMERIC = 'alpha_numeric';
    public const ALPHA_DASH = 'alpha_dash';
    public const ALPHA_NUMERIC_DASH = 'alpha_numeric_dash';
    public const ALPHA_NUMERIC_SPACE = 'alpha_numeric_space';
    public const ALPHA_SPACE = 'alpha_space';
    public const NUMERIC = 'numeric';
    public const INTEGER = 'integer';
    public const FLOAT = 'float';
    public const URL = 'url';
    public const URL_EXISTS = 'url_exists';
    public const JSON = 'valid_json_string';

    protected static array $rules;

    public static function add(string $name, array|string $rules): self
    {
        self::$rules[$name] = implode('|', parse_array($rules));

        return new self();
    }

    public function get(): array
    {
        return self::$rules;
    }

    public static function In(array $data): string
    {
        return 'contains_list,' . implode(';', $data);
    }

    public static function NotIn(array $data): string
    {
        return 'doesnt_contain_list,' . implode(';', $data);
    }

    public static function Boolean(bool $strict = false): string
    {
        return 'boolean' . $strict ? ',strict' : '';
    }

    public static function MaxLen(int $value): string
    {
        return "max_len,$value";
    }

    public static function MinLen(int $value): string
    {
        return "min_len,$value";
    }

    public static function MaxNumeric(int $value): string
    {
        return "max_numeric,$value";
    }

    public static function MinNumeric(int $value): string
    {
        return "min_numeric,$value";
    }

    public static function Len(int $value): string
    {
        return "exact_len,$value";
    }

    public static function BetweenLen(int $start, int $end): string
    {
        return "between_len,$start;$end";
    }

    public static function Date(?string $format = null): string
    {
        return 'date' . ! is_null($format) ? ",$format" : '';
    }

    public static function FileExtension(array $data): string
    {
        return 'extension,' . implode(';', $data);
    }

    public static function RegEx(string $pattern): string
    {
        return "regex,$pattern";
    }
}
