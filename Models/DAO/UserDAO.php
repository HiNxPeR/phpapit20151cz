<?php

namespace Models\DAO;

class UserDAO extends AbstractDAO
{
    /**
     * Devuelve un usuario a partir de su login.
     * @param string $login login del usuario
     * @return User
     * @throws \Exception
     */
    public function findByLogin($login)
    {
        try {
            $dql = "SELECT u FROM ".$this->type." u WHERE u.login = ?1";
            $users = $this->entityManager->createQuery($dql)
                                        ->setParameter(1, $login)
                                        ->setMaxResults(1)
                                        ->getResult();
            return current($users);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

}