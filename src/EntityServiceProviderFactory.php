<?php


namespace Vekas\EntityService;

use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use PHPUnit\Runner\FileDoesNotExistException;
use Psr\Container\ContainerInterface;
use Vekas\EntityService\EntityServiceProvider;
use Vekas\EntityService\Exceptions\FileDoesNotExistException as ExceptionsFileDoesNotExistException;

class EntityServiceProviderFactory {

    /**
     * @param EntityManagerInterface $entityManager 
     */
    function __construct(
        private ContainerInterface $container,
        private  $entityManager
    ) {}
    
    /**
     * @param $arr the array that contains the entity full qualified class name in the key
     * and service full qualified class name in the value
     */
    function registerFromArray($arr) {
        $entityServiceProvider = new EntityServiceProvider($this->container,$this->entityManager);
        foreach($arr as $entityClass => $serviceClass) {
            $entityServiceProvider->registerService($entityClass , $serviceClass);
        }
        return $entityServiceProvider;
    }


    /**
     * @param $path the php file that is returned with array of entities and its services
     */
    function registerFromPhpFile($path) {
        if ( file_exists($path) ) {
            $contents = include($path);
            if ( is_array($contents) ) {
                return $this->registerFromArray($contents);
            } else {
                throw new InvalidArgumentException("you must provide php file and with return type array");
            }
        } else {
            throw new ExceptionsFileDoesNotExistException("file path provided : $path does not exists");
        }
    }

    
}