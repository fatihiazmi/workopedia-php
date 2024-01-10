<?php

/**
 * Get base path
 * 
 * @param string $path
 * @return string
 */

function basePath($path = '')
{
    return __DIR__ . '/' . $path;
}

/**
 * Load view
 * 
 * @param string $name
 * @return void
 */
function loadView($name)
{

    $viewPath = basePath("views/{$name}.php");

    if (file_exists($viewPath)) {
        require $viewPath;
    } else {
        echo "View file {$name} not found";
    }
}

/**
 * Load partial
 * 
 * @param string $name
 * @return void
 */
function loadPartial($name)
{
    $partialPath = basePath("views/partials/{$name}.php");

    if (file_exists($partialPath)) {
        require $partialPath;
    } else {
        echo "View file {$name} not found";
    }
}

/**
 * Inspect values
 * 
 * @param mixed $value
 * @return void
 */

function inspect($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

/**
 * Inspect values and die
 * 
 * @param mixed $value
 * @return void
 */

function inspectAndDie($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die();
}