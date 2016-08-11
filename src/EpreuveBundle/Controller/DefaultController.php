<?php

namespace EpreuveBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EpreuveBundle:Default:index.html.twig');
    }
}
