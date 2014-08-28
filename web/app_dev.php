<?php
if(
   !isset($_SERVER['PHP_AUTH_USER']) 
   || !isset($_SERVER['PHP_AUTH_PW'])
    || (
        $_SERVER['PHP_AUTH_USER']!='demo'
        && $_SERVER['PHP_AUTH_USER']!='admin'
    )
){
    header('WWW-Authenticate: Basic realm="Development Area"');
    exit('Access Denied.');
}

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
