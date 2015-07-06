<?php

// Include Composer Autoload (relative to project root).
require_once "lib/autoload.php";

\Logger::configure('Config/loggerConfig.xml');
$config = array( 'load' => array(__DIR__.'/Resources/AbstractBaseResource.php',
                                __DIR__.'/Resources/AuthenticationResource.php',
                                __DIR__.'/Resources/*.php'));

$app = new Tonic\Application($config);
$request = new Tonic\Request();

try {

    $resource = $app->getResource($request);
    $response = $resource->exec();

} catch (Tonic\NotFoundException $e) {
    $response = new Tonic\Response(404, $e->getMessage(),
            array("contentType"=>"application/json"));

} catch (Tonic\UnauthorizedException $e) {
    $realm = 'Restricted area';
    $response = new Tonic\Response($e->getCode(), $e->getMessage(),
            array("contentType"=>"application/json"));
} catch (Tonic\MethodNotAllowedException $e) {
    $response = new Tonic\Response($e->getCode(), $e->getMessage(),
            array("contentType"=>"application/json"));
} catch (Tonic\ConditionException $e) {
    $response = new Tonic\Response($e->getCode(), $e->getMessage(),
            array("contentType"=>"application/json"));
} catch (Tonic\Exception $e) {
    $response = new Tonic\Response($e->getCode(), $e->getMessage(),
            array("contentType"=>"application/json"));
}

$response->output();
