<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => [
        'default' => 'single_space'
    ],
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'class_attributes_separation' => [
        'elements' => [
            'method' => 'one',
            'property' => 'one',
        ]
    ],
    'class_definition' => true,
    'concat_space' => [
        'spacing' => 'one'
    ],
    'declare_strict_types' => true,
    'function_declaration' => true,
    'include' => true,
    'increment_style' => ['style' => 'post'],
    'lowercase_cast' => true,
    'lowercase_keywords' => true,
    'method_argument_space' => ['on_multiline' => 'ignore'],
    'native_function_casing' => true,
    'no_alias_functions' => true,
    'no_blank_lines_after_class_opening' => true,
    'no_closing_tag' => true,
    'no_empty_phpdoc' => true,
    'no_empty_statement' => true,
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_mixed_echo_print' => ['use' => 'echo'],
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_short_bool_cast' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_trailing_comma_in_singleline' => true,
    'no_trailing_whitespace' => true,
    'no_trailing_whitespace_in_comment' => true,
    'no_unused_imports' => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_whitespace_in_blank_line' => true,
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'phpdoc_align' => true,
    'phpdoc_no_empty_return' => true,
    'phpdoc_scalar' => true,
    'phpdoc_summary' => false,
    'phpdoc_to_comment' => true,
    'phpdoc_trim' => true,
    'return_type_declaration' => true,
    'single_blank_line_at_eof' => true,
    'single_class_element_per_statement' => true,
    'single_import_per_statement' => true,
    'single_line_after_imports' => true,
    'single_quote' => true,
    'space_after_semicolon' => true,
    'standardize_not_equals' => true,
    'ternary_operator_spaces' => true,
    'trailing_comma_in_multiline' => true,
    'trim_array_spaces' => true,
    'unary_operator_spaces' => true,
    'visibility_required' => [
        'elements' => ['method', 'property']
    ],
    'whitespace_after_comma_in_array' => true,
    'not_operator_with_successor_space' => true,
];

$finder = Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/core',
        __DIR__ . '/resources/lang',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new Config();

return $config
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);