<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    /**
     * @Route("/page/test", name="test")
     */
    public function indexAction(Request $request)
    {
        $number = rand(0, 100);
        // replace this example code with whatever you need
        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}
