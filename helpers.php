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
function loadView($name, $data = [])
{

    $viewPath = basePath("App/views/{$name}.php");

    if (file_exists($viewPath)) {
        extract($data);
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
function loadPartial($name, $data = [])
{
    $partialPath = basePath("App/views/partials/{$name}.php");

    if (file_exists($partialPath)) {
        extract($data);
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

function formatSalary($salary)
{
    return '$' . number_format(floatval($salary));
}

function formatTags($tag)
{
    return $tag ? ucwords($tag) : $tag;
}

function sanitize($dirty)
{
    return filter_var(trim($dirty), FILTER_SANITIZE_SPECIAL_CHARS);
}

function redirect($url)
{
    header("Location: {$url}");
    exit;
}
