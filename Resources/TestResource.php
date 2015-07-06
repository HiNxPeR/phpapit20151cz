<?php
/**
 * Recurso de Test para verificar instalación de freamework Tonic.
 */

namespace Resources;

use Tonic\Resource;
use Tonic\Response;
use Tonic\Exception;
use Tonic\ConditionException;

/**
 * @uri /test
 */
class TestResource extends AuthenticationResource
{
    
    /**
     * Metodo para atender pedidos del verbo GET
     * @method GET
     * @formatOutput
     */
    public function getTest(){        
        return new Response(Response::OK, 'propiedad: Hola mundo!');
    }

    /**
     * Metodo para atender pedidos del verbo POST
     * @method POST
     */
    public function postTest(){
        $response = new Response();
        $response->contentType = "application/json";
        $response->body = json_encode(array("Id"=>"1", "Nombre"=>"Sheldon"));
        $response->code = Response::CREATED;
        return $response;
    }

    /**
     * @method PUT
     * @formatOutput
     */
    public function putTest(){
        $response = new Response();
        $response->contentType = "application/json";
        $response->body = json_encode(array("Id"=>"1", "Nombre"=>"Sheldon Cooper"));
        $response->code = Response::OK;
        return $response;
    }
    
    /**
     * @method DELETE
     * @formatOutput
     */
    public function deleteTest(){
            $response = new Response();
        $response->contentType = "application/json";
        $response->body = json_encode(array("Id"=>null, "Nombre"=>"Sheldon Cooper"));
        $response->code = Response::OK;
        return $response;
    }
}
