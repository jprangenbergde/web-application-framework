<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/config',
        __DIR__ . '/framework',
        __DIR__ . '/src',
    ]);

return (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRules([
        '@PER-CS2.0' => true,
        '@PHP84Migration' => true,
        'no_unused_imports' => true,
        'global_namespace_import' => true,
        'cast_spaces' => ['space' => 'single'],
        'blank_line_before_statement' => true,
    ])
    ->setFinder($finder);
