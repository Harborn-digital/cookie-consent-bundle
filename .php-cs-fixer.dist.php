<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony'               => true,
        'declare_strict_types'   => true,
        'ordered_imports'        => true,
        'psr0'                   => false,
        'yoda_style'             => false,
        'phpdoc_order'           => true,
        'array_syntax'           => [
            'syntax' => 'short',
        ],
        'binary_operator_spaces' => [
            'align_equals'       => true,
            'align_double_arrow' => true,
        ],
        'header_comment'         => [
            'header' => <<<EOH
This file is part of the CookieConsentBundle package.
Originally created by Connect Holland.
EOH
                ,
            ]
    ])
    ->setFinder($finder);
