<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function filterAction()
    {
        $request = $this->get('request');
        if($request->isXmlHttpRequest() && $request->getMethod()=="POST")
        {
            $data = $request->request->all();
            $count = count($data);
            var_dump($count);
            $em = $this->getDoctrine()->getManager();
            $courseRepository = $em->getRepository('FrontEndBundle:Course');
            $course = array();
            var_dump($data);
            for($a = 0; $a<$count; $a++)
            {
                $course[$a] = $courseRepository->findBy(array('category'=>$data[$a]));
                var_dump($data[$a]);
                var_dump($course);
            }
            var_dump(count($course));
            //$array = array();
            $array = new RecursiveIteratorIterator(new RecursiveArrayIterator($course));
            var_dump($array);
            die();
            for($b=0; $b<$count; $b++)
            {
                if($course[$b])
                $array[$b] = $course[$b]->getDescription();
                else
                    $array[$b] = false;
            }
            //die();
            $response = new Response(json_encode($array));
            return $response;
        }
        $response = new Response(json_encode(array( '0'=>"error")));
        return $response;
    }

}
