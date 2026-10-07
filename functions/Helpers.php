<?php
if (!function_exists('d'))
{
    function d($vars): void
    {
        var_dump($vars);
    }
}

if (!function_exists('dd'))
{
    function dd($vars)
    {
        echo '<pre>';
        d($vars);
        echo '</pre>';

        exit();
    }
}