<?php

/**
 * Recurso base para todos los recursos.
 * Define métodos para validar formato de entrada.
 * Define métodos para formatear salida de datos.
 */

namespace Resources;

use Tonic\Resource;
use Tonic\Response;
use Tonic\Exception;
use Tonic\ConditionException;

abstract class AbstractBaseResource extends Resource{

    protected $logger;
    
    /**
     * Valida el formato de los datos de entrada.
     * Verifica que el ContentType sea application/json y que el JSON de entrada
     * esté bien formado.
     */
    protected function validateInput() 
    {
        $this->before(function ($request) {
            if (strtolower($request->contentType) !== "application/json") {
                throw new Exception("El ContentType debe ser application/json", 
                                                        Response::BADREQUEST);
            }
            
            try{
                $request->data = json_decode($request->data, true);
                switch (json_last_error()) {
                    case JSON_ERROR_NONE:
                        break;
                    case JSON_ERROR_SYNTAX:
                        throw new Exception("JSON mal formado",
                                                        Response::BADREQUEST);
                    case JSON_ERROR_UTF8:
                        throw new Exception("La codificación de caracteres debe ser UTF-8", 
                                                        Response::BADREQUEST);
                    default:
                        throw new Exception("Error en el objeto de entrada.", 
                                                        Response::BADREQUEST);
                }

                if(empty($request->data)){
                    throw new Exception("No hay datos para procesar", 
                                                        Response::BADREQUEST);
                }
            } catch (Exception $e) {
                $response = json_encode($this->getErrorArray($e->getMessage(), 
                                                                $e->getCode()));
                
                throw new Exception($response, $e->getCode());
            }

        });
    }
    
    /**
     * Formatea la respuesta para que sea un string con formato JSON.
     * Establece el encabezado de la respuesta
     */
    protected function formatOutput() 
    {
        $this->after(function($response) {
            $response->contentType = "application/json";
            $response->body = json_encode($response->body);
        });
    }
    
    /**
     * Devuelve un array con los datos del error producido.
     * @param string $errormessage mensaje de error
     * @param int $errorcode código de error
     * @param string $href referencia al recurso de error.
     */
    protected function getErrorArray($errormessage, $errorcode, $href = null) 
    {
        return array("message" => $errormessage, "code" => $errorcode, 
                                                                "href" => $href);
    }

}
