<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * clase base de la cual deben heredar los controladores utilizados
 * @author Eduardo Escallon
 */
abstract class BaseController extends AbstractController
{
    // class Vars
    protected EntityManagerInterface $em;
    protected RequestStack           $request;
    
    /**
     * Construct
     * @param EntityManagerInterface $em                  Objeto con la conexión a base de datos
     * @param RequestStack           $request             Objeto con la información del Request
     */
    public function __construct(EntityManagerInterface $em, RequestStack $request)
    {
        $this->em                 = $em;
        $this->request            = $request;
    }
}