<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR2' => true,
    'no_unused_imports' => true
];

return Config::create()
    ->setRules($rules)
    ->setFinder(Finder::create()->in(__DIR__));
