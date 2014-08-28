<?php
//protect development and staging areas
if(
    (
        substr($_SERVER['HTTP_HOST'],0,4)=='dev.'
        || substr($_SERVER['HTTP_HOST'],0,6)=='stage.'
    )
    &&
    (
        !isset($_SERVER['PHP_AUTH_USER'])
        || !isset($_SERVER['PHP_AUTH_PW'])
        || (
            $_SERVER['PHP_AUTH_USER']!='demo'
            && $_SERVER['PHP_AUTH_USER']!='admin'
        )
    )
){
    header('WWW-Authenticate: Basic realm="Development Area"');
    exit('Access Denied.');
}

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.
/*
$loader = new ApcClassLoader('sf2', $loader);
$loader->register(true);
*/

require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
