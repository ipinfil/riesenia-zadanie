<?php
namespace Rshop\CS\Config;

use PhpCsFixer\Config;

class Rshop extends Config
{
    /** @var string */
    private $header;

    /** @var bool */
    private $strict = false;

    /** @var array */
    private $rules = [
        '@Symfony' => true,
        'array_indentation' => true,
        'array_syntax' => [
            'syntax' => 'short'
        ],
        'blank_line_before_statement' => [
            'statements' => ['break', 'case', 'continue', 'declare', 'default', 'do', 'for', 'foreach', 'if', 'include', 'include_once', 'require', 'require_once', 'return', 'switch', 'throw', 'try', 'while', 'yield']
        ],
        'concat_space' => [
            'spacing' => 'one'
        ],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'compact_nullable_typehint' => true,
        'echo_tag_syntax' => false,
        'explicit_indirect_variable' => true,
        'hash_to_slash_comment' => false,
        'is_null' => [
            'use_yoda_style' => false
        ],
        'logical_operators' => true,
        'method_chaining_indentation' => true,
        'no_multiline_whitespace_before_semicolons' => true,
        'no_null_property_initialization' => true,
        'no_php4_constructor' => true,
        'no_superfluous_phpdoc_tags' => false,
        'no_useless_return' => true,
        'ordered_class_elements' => [
            'order' => ['use_trait', 'constant_public', 'constant_protected', 'constant_private', 'property_public', 'property_protected', 'property_private', 'construct', 'destruct', 'method_public', 'method_protected', 'method_private', 'magic', 'phpunit']
        ],
        'ordered_imports' => [
            'sortAlgorithm' => 'alpha'
        ],
        'phpdoc_add_missing_param_annotation' => [
            'only_untyped' => false
        ],
        'phpdoc_order' => true,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last'
        ],
        'protected_to_private' => false,
        'single_line_comment_style' => false,
        'ternary_to_null_coalescing' => true,
        'trailing_comma_in_multiline_array' => false,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false
        ]
    ];

    /**
     * @param string $header
     */
    public function __construct($header = null)
    {
        $this->header = $header;

        parent::__construct('rshop');

        $this->setRiskyAllowed(true);
    }

    /**
     * @param string $rule
     * @param mixed  $value
     *
     * @return $this
     */
    public function setRule(string $rule, $value)
    {
        $this->rules[$rule] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function setStrict()
    {
        $this->strict = true;

        return $this;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        $this->rules['declare_strict_types'] = $this->strict;

        if (!$this->strict) {
            $this->rules['no_blank_lines_before_namespace'] = true;
            $this->rules['single_blank_line_before_namespace'] = false;
        }

        if ($this->header !== null) {
            $this->rules['header_comment'] = [
                'commentType' => 'PHPDoc',
                'header' => $this->header,
                'location' => 'after_open',
                'separate' => 'bottom'
            ];
        }

        return $this->rules;
    }
}

$config = new Rshop();

$config->getFinder()->in(__DIR__);

return $config;