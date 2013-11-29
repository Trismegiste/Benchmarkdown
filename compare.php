<?php

require_once 'vendor/autoload.php';

use \Parsedown;
use Michelf\Markdown as MichMd;
use Ciconia\Ciconia;

$text = file_get_contents(__DIR__ . '/example.md');
$nbLoop = 1000;

// erusev/parsedown
$stopwatch = microtime(true);
for ($k = 0; $k < $nbLoop; $k++) {
    Parsedown::instance()->parse($text);
}
printf("erusev/parsedown: %.1f s\n", microtime(true) - $stopwatch);

// michelf/php-markdown
$stopwatch = microtime(true);
for ($k = 0; $k < $nbLoop; $k++) {
    MichMd::defaultTransform($text);
}
printf("michelf/php-markdown: %.1f s\n", microtime(true) - $stopwatch);

// kzykhys/ciconia
// You can debate about instantiate or not the service outside the loop 
// in case of multiple concurrent threads in a webserver for example,
// but here I want to know the raw speed.
$ciconia = new Ciconia();
$stopwatch = microtime(true);
for ($k = 0; $k < $nbLoop; $k++) {
    $ciconia->render($text);
}
printf("kzykhys/ciconia: %.1f s\n", microtime(true) - $stopwatch);
