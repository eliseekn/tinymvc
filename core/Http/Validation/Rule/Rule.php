<?php

declare(strict_types=1);

namespace Core\Http\Validation\Rule;

class Rule
{
    public const REQUIRED = 'required';

    public const EMAIL = 'valid_email';

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

    public static function custom(string $rule, $data = null): string
    {
        $rule = new $rule();

        if (is_null($data)) {
            return $rule->name;
        }

        if (is_array($data)) {
            return $rule->name . ',' . implode(';', $data);
        }

        return $rule->name . ',' . $data;
    }

    public function make(): array
    {
        return self::$rules;
    }

    public static function in(array $data): string
    {
        return 'contains_list,' . implode(';', $data);
    }

    public static function notIn(array $data): string
    {
        return 'doesnt_contain_list,' . implode(';', $data);
    }

    public static function boolean(bool $strict): string
    {
        // @phpstan-ignore-next-line
        return 'boolean' . $strict ? ',strict' : '';
    }

    public static function maxLen(int $value): string
    {
        return "max_len,$value";
    }

    public static function minLen(int $value): string
    {
        return "min_len,$value";
    }

    public static function maxNumeric(int $value): string
    {
        return "max_numeric,$value";
    }

    public static function minNumeric(int $value): string
    {
        return "min_numeric,$value";
    }

    public static function len(int $value): string
    {
        return "exact_len,$value";
    }

    public static function betweenLen(int $start, int $end): string
    {
        return "between_len,$start;$end";
    }

    public static function date(?string $format = null): string
    {
        // @phpstan-ignore-next-line
        return 'date' . ! is_null($format) ? ",$format" : '';
    }

    public static function fileExtension(array $data): string
    {
        return 'extension,' . implode(';', $data);
    }

    public static function regEx(string $pattern): string
    {
        return "regex,$pattern";
    }
}
