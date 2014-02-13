<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    
    private $array = array();
    
    public function filterAction()
    {
        $request = $this->get('request');
        if($request->isXmlHttpRequest() && $request->getMethod()=="POST")
        {
            
            $data = $request->request->all();
            $course = $this->dbHandle($data);
            $this->multiToOne($course);
            $response = new Response(json_encode($this->array));
            return $response;
        }
        
        $response = new Response(json_encode(array( '0'=>"error")));
        return $response;
    }

    
    
    public function dbHandle($data)
    {
        $count = count($data);
        $em = $this->getDoctrine()->getManager();
        $courseRepository = $em->getRepository('FrontEndBundle:Course');
        $course = array();
            
        for($a = 0; $a<$count; $a++)
        {
            $course[] = $courseRepository->findBy(array('category'=>$data[$a]));
        }
        
        return $course;
    }

    
    
    public function multiToOne($course)
    {
         if (!is_array($course)) 
             {
             
                $this->array[] = $course->getDescription();
                //var_dump($this->array);
                return;
            }

        foreach($course as $a) 
            {
                $this->multiToOne($a);
            }
    }
}
