<?php

use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$rules = [
    // Базовые стандарты PSR-12 и миграция на PHP 7.1+
    '@PSR12' => true,
    '@PHP71Migration' => true,

    // Дополнительные правила (не входящие в наборы)
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => [
        'operators' => [
            '='  => 'align_single_space',
            '=>' => 'align_single_space',
        ],
    ],
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement' => [
        'statements' => ['return', 'throw', 'try'],
    ],
    'cast_spaces' => ['space' => 'single'],
    'concat_space' => ['spacing' => 'one'],
    'declare_equal_normalize' => ['space' => 'single'],
    'function_typehint_space' => true,
    'lowercase_cast' => true,
    'magic_constant_casing' => true,
    'native_function_casing' => true,
    'no_blank_lines_after_class_opening' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_empty_phpdoc' => true,
    'no_empty_statement' => true,
    'no_extra_blank_lines' => true,
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_short_bool_cast' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_spaces_after_function_name' => true,
    'no_spaces_around_offset' => true,
    'no_trailing_comma_in_singleline' => true,
    'no_unused_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'no_whitespace_before_comma_in_array' => true,
    'normalize_index_brace' => true,
    'object_operator_without_whitespace' => true,
    'ordered_imports' => [
        'sort_algorithm' => 'length',
        'imports_order' => ['const', 'class', 'function'],
    ],
    'return_type_declaration' => true,
    'self_accessor' => true,
    'short_scalar_cast' => true,
    'single_blank_line_at_eof' => true,
    'single_line_after_imports' => true,
    'single_quote' => true,
    'space_after_semicolon' => true,
    'standardize_not_equals' => true,
    'ternary_operator_spaces' => true,
    'trim_array_spaces' => true,
    'unary_operator_spaces' => true,
    'void_return' => true,
    'whitespace_after_comma_in_array' => true,

    // PHPDoc
    'phpdoc_align' => true,
    'phpdoc_annotation_without_dot' => true,
    'phpdoc_indent' => true,
    'phpdoc_inline_tag_normalizer' => true,
    'phpdoc_no_access' => true,
    'phpdoc_no_package' => true,
    'phpdoc_no_useless_inheritdoc' => true,
    'phpdoc_order' => true,
    'phpdoc_return_self_reference' => true,
    'phpdoc_scalar' => true,
    'phpdoc_separation' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_summary' => true,
    'phpdoc_to_comment' => false,   // если хотите оставить комментарии вместо аннотаций – оставьте false
    'phpdoc_trim' => true,
    'phpdoc_types' => true,
    'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
    'phpdoc_var_without_name' => true,

    // Массивы
    'array_indentation' => true,
    'no_multiline_whitespace_around_double_arrow' => true, // уже есть выше
    'trailing_comma_in_multiline' => true,
    'trim_array_spaces' => true,

    // Классы
    'class_attributes_separation' => [
        'elements' => [
            'const'    => 'only_if_meta',
            'method'   => 'one',
            'property' => 'one',
        ],
    ],
    'visibility_required' => true,
    'single_class_element_per_statement' => [
        'elements' => ['const', 'property'],
    ],
    'ordered_class_elements' => true,

    // Прочее
    'combine_consecutive_issets' => true,
    'combine_consecutive_unsets' => true,
    'compact_nullable_typehint' => true,
    'dir_constant' => true,
    'escape_implicit_backslashes' => ['double_quoted' => true],
    'explicit_indirect_variable' => true,
    'explicit_string_variable' => true,
    'fopen_flag_order' => true,
    'fopen_flags' => ['b_mode' => true],
    'fully_qualified_strict_types' => true,
    'global_namespace_import' => [
        'import_classes' => false,
        'import_constants' => false,
        'import_functions' => false,
    ],
    'is_null' => true,
    'lowercase_static_reference' => true,
    'magic_method_casing' => true,
    'method_chaining_indentation' => true,
    'modernize_types_casting' => false,
    'multiline_comment_opening_closing' => true,
    'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
    'native_constant_invocation' => ['fix_built_in' => true],
    'native_function_invocation' => ['scope' => 'namespaced', 'strict' => false],
    'native_function_type_declaration_casing' => true,
    'no_alternative_syntax' => true,
    'no_closing_tag' => true,
    'no_null_property_initialization' => true,
    'no_unset_cast' => true,
    'no_whitespace_in_blank_line' => true,
    'non_printable_character' => true,
    'not_operator_with_successor_space' => true,
    'nullable_type_declaration' => ['syntax' => 'question_mark'],
    'nullable_type_declaration_for_default_null_value' => ['use_nullable_type_declaration' => true],
    'phpdoc_var_annotation_correct_order' => true,
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    'return_assignment' => false,
    'set_type_to_cast' => true,
    'simple_to_complex_string_variable' => true,
    'single_line_comment_style' => true,
    'single_space_around_construct' => true,
    'single_trait_insert_per_statement' => false,
    'standardize_increment' => true,
    'static_lambda' => false,
    'string_line_ending' => true,
    'ternary_to_null_coalescing' => true,
    'yoda_style' => [
        'equal' => false,
        'identical' => false,
        'less_and_greater' => false,
    ],

    // PHPUnit
    'php_unit_construct' => [
        'assertions' => ['assertEquals', 'assertSame', 'assertNotEquals', 'assertNotSame'],
    ],
    'php_unit_dedicate_assert' => true,
    'php_unit_dedicate_assert_internal_type' => true,
    'php_unit_expectation' => ['target' => '5.6'],
    'php_unit_fqcn_annotation' => true,
    'php_unit_method_casing' => ['case' => 'camel_case'],
    'php_unit_mock_short_will_return' => true,
    'php_unit_namespaced' => true,
    'php_unit_no_expectation_annotation' => true,
    'php_unit_set_up_tear_down_visibility' => true,
    'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],
    'php_unit_test_class_requires_covers' => true,
];

$finder = Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setRules($rules)
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')   // добавьте для ускорения
    ->setRiskyAllowed(true);                           // разрешить рискованные правки (если нужно)
