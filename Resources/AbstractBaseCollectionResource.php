<?php

namespace Resources;

/**
 * Clase abstracta para Colecciones de Recursos
 *
 * @author alejandro
 */
abstract class AbstractBaseCollectionResource extends AuthenticationResource
{
    protected $offset;
    protected $limit;
    protected $filterField;
    protected $filterValue;

    /**
     * Devuelve la descripción de la colección. Utilizada para armar el objeto
     * Meta de las colecciones.
     * @return string
     */
    protected function getCollectionDescription($entityName){
        return "Colección de ".$entityName;
    }
    
    /**
     * Devuelve el valor de un parámetro del array $_GET
     * @param string $name nombre del parametro a obtener del array $_GET
     * @return mixed
     */
    private function getParameterValue($name){
        return isset($_GET[$name]) && !empty($_GET[$name])?$_GET[$name]:null;
    }

    /**
     * Settea los parámetros del request para un pedido de colección de algún 
     * recurso.
     */
    protected function getCollectionRequestParameters()
    {
        $this->offset = $this->getParameterValue("offset");
        $this->limit =$this->getParameterValue("limit");
        $this->filterField = $this->getParameterValue("field");
        $this->filterValue = $this->getParameterValue("value");
    }
    
    /**
     * Devuelve objeto Meta para las colecciones.
     * @param int $totalCount cantidad total de registros en la colección
     */
    protected function getCollectionMetaInformation($totalCount){
        return array(
                "description"=>$this->getCollectionDescription(),
                "totalCount"=>$totalCount,
                "offset"=>$this->offset,
                "limit"=>$this->limit,
                "next"=>$this->getNextHref($totalCount),
                "previous"=>$this->getPreviousHref($totalCount)
            );
    }

    /**
     * Devuelve el href para la página siguiente
     * @param int $offset número de registros a omitir en el select
     * @param int $limit cantidad máxima de registros a devolver.
     * @param int $totalCount cantidad total de registros en la colección
     * @return string href para la página siguiente
     */
    protected function getNextHref($totalCount)
    {
        $href = null;
        if(!empty($this->limit)){
            $next = $this->offset + $this->limit;
            if($totalCount > 0 && $next < $totalCount){
                $href = $this->request->uri.'?offset='.$next.'&limit='.$this->limit;
            }
        }
        return $href;
    }
    
    /**
     * Devuelve el href para la página previa
     * @param int $offset número de registros a omitir en el select
     * @param int $limit cantidad máxima de registros a devolver.
     * @param int $totalCount cantidad total de registros en la colección
     * @return string href para la página previa
     */
    protected function getPreviousHref($totalCount)
    {
        $href = null;
        $limit = $this->limit;
        if(empty($limit)){
            $limit = $totalCount;
        }
        $previous = $this->offset - $limit;

        if($this->offset > 0 && $previous < 0)
            $previous = 0;
        
        if($totalCount > 0 && $previous >= 0){
            $href = $this->request->uri.'?offset='.$previous.'&limit='.$limit;
        }
        return $href;
    }
    
}
