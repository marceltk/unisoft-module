<?php
/**
 * Created by PhpStorm.
 * User: marcel
 * Date: 07/01/18
 * Time: 23:19
 */

namespace Unisoft\ORM\Filter;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;

class FilterORMUtil
{

    public static function setEmpresaFilter(EntityManager $entityManager, $empresaId)
    {
        try {
            $entityManager->getConfiguration()->addFilter("appempresa_id", new \Application\ORM\Filter\Appempresa($entityManager));
            $filters = $entityManager->getFilters();

            if (!$filters->isEnabled('appempresa_id')) {
                $filters->enable("appempresa_id")->setParameter('appempresa_id', $empresaId);
            } else {
                $filters->getFilter('appempresa_id')->setParameter('appempresa_id', $empresaId);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
