<?php

namespace Models\DAO;

Use Models\Movie;
use Utils\Serializor;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * DAO para manejo de películas
 */
class MovieDAO extends AbstractDAO
{
    /**
     * Devuelve un array con la colección de películas y la cantidad de películas.
     * Permite realizar filtros:
     * Si la propiedad es de tipo string por like '%<valor>%'. Case insensitive.
     * Si la propiedad es de tipo int por = <valor>
     * @param string $value valor por el cual filtrar
     * @param string $field campo por el cual filtrar.
     * @param int $offset nro de registro a partir del cual se devuelve
     * @param int $limit cantidad de registros a devolver.
     * @return array con la colección de películas y la cantidad de películas.
     */
    public function findMovieCollection($value='%', $field='title', $offset=0,
                                                                    $limit=10) {
        
        if(empty($value) || empty($field)){
            $value = '%';
            $field = 'title';            
        }
        
        if($offset<0)
            $offset = 0;
        if($limit<0)
            $limit=0;
        
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('m')
            ->from($this->type, 'm')
            ->orderBy('m.'.$field, 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        
        if(in_array($field, Movie::queryableStringProperties())){
            
            $qb->add('where', $qb->expr()->like($qb->expr()->upper('m.'.$field), '?1'))
                ->setParameter(1,'%'.strtoupper($value).'%');
        }
        else{
            $qb->add('where', $qb->expr()->eq('m.'.$field, '?1'))
            ->setParameter(1,$value);
        }
            
        $query = $qb->getQuery();
        $paginator = new Paginator($query);
        $response["totalCount"] = count($paginator);
        $response["movies"] = $this->collectionToArray($query->getResult());
        return $response;
    }
    
    
    /**
     * Verifica que se envíen todas las propiedades del recurso.
     * @param type $movieData
     * @throws Exception
     */
    private function validateMovieData($movieData) {
        $data = array_keys($movieData);
        $properties = array('title', 'description', 'director', 'duration',
            'releaseDate', 'actors', 'stars', 'writter', 'href');
        $diff = array_diff($properties, $data);
        if(count($diff)){
            throw new \Exception('Es obligatorio el envío de las propiedades: '.
                                                            implode(',', $diff));
        }
                
    }

    /**
     * Setea las propiedades del modelo y lo devuelve.
     * @param Models\Movie $movie instancia de la clase Models\Movie
     * @param array $movieData array con las propiedades a setear.
     * @return Models\Movie con las propiedades seteadas.
     */
    private function setMovieData($movie, $movieData) {
        $movie->setTitle($movieData["title"]);
        $movie->setDescription($movieData["description"]);
        $movie->setDirector($movieData["director"]);
        $movie->setDuration($movieData["duration"]);
        $movie->setReleaseDate($movieData["releaseDate"]);
        $movie->setActors($movieData["actors"]);
        $movie->setStars($movieData["stars"]);
        $movie->setWritter($movieData["writter"]);
        return $movie;
    }
    
    /**
     * Setea las propiedades de la película en el modelo y lo persiste.
     * @param Models\Movie $movie instancia de la clase Models\Movie
     * @param array $movieData array con las propiedades a setear.
     * @return array con las propiedades del modelo.
     */
    private function setAndSaveMovie($movie, $movieData) {
        $this->validateMovieData($movieData);
        $movie = $this->setMovieData($movie, $movieData);
        $this->save($movie);
        return Serializor::toArray($movie, 1, NULL, 
                                            array($this->type=>array('id')));  
    }
    
    /**
     * Inserta una película
     * @param array $movieData datos de la película a insertar.
     * @return devuelve array con la película creada.
     */
    public function saveMovie($movieData) {
        $movie = new Movie();
        try {
            return $this->setAndSaveMovie($movie, $movieData);
        } catch (\Exception $exc) {
            unset($movie);
            throw new \Exception($exc->getMessage(), $exc->getCode());
        }
    }
    
    /**
     * 
     * @param Models\Movie $movie instancia de la clase Models\Movie
     * @param array $movieData array con las propiedades a setear.
     * @return devuelve array con la película actualizada.
     */
    public function updateMovie($movie, $movieData) {
        return $this->setAndSaveMovie($movie, $movieData);      
    }
    
    /**
     * Devuelve un array con las propiedades de la película. Sin el id.
     * @param Models\Movie $movie
     * @return array array con las propiedades de la película. Sin el id.
     * @return null si el parametro no es instancia de la clase Models\Movie
     */
    public function modelToArray($movie) {
        if (get_class($movie) != $this->type) {
            return array();
        }

        return Serializor::toArray($movie, 1, \NULL, 
                                            array($this->type=>array('id')));
    }
    
        /**
     * Devuelve un array con las propiedades de la película. Sin el id.
     * @param Models\Movie $movieCollection
     * @return array array con las propiedades de la película. Sin el id.
     * @return null si el parametro no es un array de Models\Movie
     */
    public function collectionToArray($movieCollection) {
        if (count($movieCollection) && 
                    get_class(current($movieCollection)) != $this->type) {
            return array();
        }

        return Serializor::toArray($movieCollection, 1, \NULL, 
                                            array($this->type=>array('id')));
    }
}