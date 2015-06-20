<?php

// Activate composer autoloading
$loader = require __DIR__.'/../vendor/autoload.php';

// use composer loader for own purposes
$loader->add('Bowling', __DIR__ . '/../src');
