<?php

namespace Models\DAO;

use \Doctrine\ORM\Query\Expr;
use \Doctrine\ORM\Tools\Pagination\Paginator;

define("DELIMITER", '|');

abstract class AbstractDAO {

    
    protected $entityManager;
    protected $type;


    /**
     * Constructor generico para los DAOs
     * @param type $et
     * @param type $em
     */
    public function __construct($et, $em) {
        $this->type = $et;    
        $this->entityManager = $em;
    }

    /**
     * Devuelve una entidad a partir de su id.
     * @param type $id id de la entidad
     * @return type
     * @throws \Exception
     */
    public function findByPK($id)
    {
        try {
            return $this->entityManager->find($this->type, $id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function save($entity) {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function delete($entity) {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function findAll() {
        $query = $this->entityManager->createQuery("SELECT e FROM ". $this->type . " e");
        //$query = $this->createQuery();
        return $query->getResult();
    }
    
    /**
     * Devuelve el parámetro offset. Utilizado desde findCollection
     * @param array $parameters
     * @return type
     * @throws \Exception
     */
    private function getOffset($parameters) 
    {
        $offset=$parameters["offset"];
        
        if(!empty($offset) && $offset < 0){
            throw new \Exception("offset debe ser un entero igual o mayor a 0 (cero)");
        }
        return $offset;
    }
    
    /**
     * Devuelve el parámetro limit. Utilzado desde findCollection
     * @param array $parameters
     * @return type
     * @throws \Exception
     */
    private function getLimit($parameters) {
        $limit=$parameters["limit"];
        
        if(!is_null($limit) && $limit < 0 ){
            if(!empty($limit)){
                throw new \Exception("limit debe ser un entero mayor que offset");
            }
        }
        return $limit;
    }
    
    /**
     * Devuelve el parámetro filterfields. Utilzado desde findCollection
     * @param array $parameters
     * @return type
     * @throws \Exception
     */
    private function getFilterFields($parameters)
    {
        if(empty($parameters["filterfields"])){
            return null;
        }
        
        $filterfields=explode(DELIMITER, $parameters["filterfields"]);
                
        if(!empty($filterfields) && !is_array($filterfields)){
            throw new \Exception("filterfields debe ser un array");
        }
        return $filterfields;
    }
    
    /**
     * Devuelve el parámetro filterconditions. Utilzado desde findCollection
     * @param array $parameters
     * @return type
     * @throws \Exception
     */
    private function getFilterConditions($parameters)
    {
        if(empty($parameters["filterconditions"])){
            return null;
        }
        
        $filterconditions=explode(DELIMITER, $parameters["filterconditions"]);
        
        if(!empty($filterconditions) && !is_array($filterconditions)){
            throw new \Exception("filterconditions debe ser un array");
        }
        return $filterconditions;
    }
    
    /**
     * Devuelve el parámetro filtervalues. Utilzado desde findCollection
     * @param array $parameters
     * @return type
     * @throws \Exception
     */
    private function getFilterValues($parameters) 
    {
        if(empty($parameters["filtervalues"])){
            return null;
        }    
        
        $filtervalues=explode(DELIMITER, $parameters["filtervalues"]);

        if(!empty($filtervalues) && !is_array($filtervalues)){
            throw new \Exception("filtervalues debe ser un array");
        }
        
        return $filtervalues;
    }
    
    /**
     * Devuelve el parámetro order. Utilzado desde findCollection
     * @param array $parameters
     * @return type
     * @throws \Exception
     */
    private function getOrder($parameters) {
        if(empty($parameters["order"])){
            return null;
        }  
        
        $order=explode(SEPARATOR, $parameters["order"]);
        
        if(!empty($order) && !is_array($order)){
            throw new \Exception("order debe ser un array");
        }
        
        return $order;
    }

    /**
     * Devuelve el parámetro ordercriteria. Utilzado desde findCollection
     * @param array $parameters
     * @return type
     * @throws \Exception
     */
    private function getOrderCriteria($parameters) {
        $allowedvalues = array('ASC', 'DESC');
        $ordercriteria=$parameters["ordercriteria"];
        
        if(!empty($ordercriteria) && !is_string($ordercriteria)){
            throw new \Exception("ordercriteria debe ser un string");
        }
        
        if(!empty($ordercriteria) && !in_array($ordercriteria, $allowedvalues)){
            throw new \Exception("ordercriteria debe ser 'ASC' o 'DESC'");
        }
        
        return $ordercriteria;
    }
    
    /**
     * Crea un query a partir de los parametros recibidos en $parameters.
     * @param array $parameters con los siguientes campos: offset, limit, 
     * filterfields, filterconditions, filtervalues, order, ordercriteria.
     * @return objeto Query
     * @throws \Exception
     */
    protected function createQuery($parameters)
    {
        $offset = $this->getOffset($parameters);
        $limit = $this->getLimit($parameters);
        $filterfields = $this->getFilterFields($parameters);
        $filterconditions = $this->getFilterConditions($parameters);
        $filtervalues = $this->getFilterValues($parameters);
        $order = $this->getOrder($parameters);
        $ordercriteria = $this->getOrderCriteria($parameters);

        if(count($filterfields)!=count($filtervalues) || 
                                count($filterfields)!=count($filterconditions)){
            throw new \Exception("La cantidad de campos, condiciones y valores para filtrar no coincide");
        }
        
        $querybuilder = $this->entityManager->createQueryBuilder();
        $querybuilder->select('e');
        $querybuilder->from($this->type, 'e');
        
        $andx = $querybuilder->expr()->andX();
        $values=array();
        for ($i = 0; $i < count($filterfields); $i++) {
            $andx->add($querybuilder->expr()->$filterconditions[$i]
                                                ('e.'.$filterfields[$i], '?'.$i)
            );
            $values[$i]=$filtervalues[$i];
        }
        
        if(!empty($filterfields)){
            $querybuilder->where($andx);
            $querybuilder->setParameters($values);
        }    
        
        if(!empty($order)){
            
            foreach ($order as &$field) {
                $field = 'e.'.$field;
            }
            if(empty($ordercriteria)){
                $ordercriteria='ASC';
            }
            
            $querybuilder->orderBy(new Expr\OrderBy(implode(',',$order), 
                                                                $ordercriteria));
        }
        
        $querybuilder->setFirstResult($offset);
        $querybuilder->setMaxResults($limit);
        
        return $querybuilder->getQuery();
    }
    
    /**
     * Devuelve una colección de entidades, según los parámetros establecidos en 
     * $parameters
     * @param array $parameters con los siguientes campos: offset, limit, 
     * filterfields, filterconditions, filtervalues, order, ordercriteria.
     * @return array de Entidades.
     */
    public function findCollection($parameters)
    {
        $query = $this->createQuery($parameters);
        return $query->getResult();
    }
    
    /**
     * Devuelve la cantidad total de registros que devolvería la consulta sin
     * establecer el límite de registros.
     * @param type $parameters
     * @return int cantidad de registros.
     */
    public function getTotalCount($parameters)
    {
        $query = $this->createQuery($parameters);
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        return count($paginator);        
    }
    
}
