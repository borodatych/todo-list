<?php

/**
 * autoload classes
 *
 * @param string $class
 * @return void
 */

function autoloadClass($class)
{
    /// format class path correctly
    $class  = @explode('\\', $class);

    /// require class file
    /// Absolute Path
    $_aPath = implode('/', $class) . '.php';

    ///
    $path1 = ROOT . DS . $_aPath;
    ///$path2 = ROOT . '/App/Helpers' . $_aPath;
    ///$path3 = ROOT . '/App/Libraries' . $_aPath;

    /// lets check if thie class exists and include it
    if( file_exists($path1) ) require $path1;
    ///else if( file_exists($path2) ) require $path2;
    ///else if( file_exists($path3) ) require $path3;
}
spl_autoload_register('autoloadClass');


