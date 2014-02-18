<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    
    private $array = array();

    public function searchAction()
    {
        $request=$this->get('request');
        if($request->isXmlHttpRequest() && $request->getMethod()=="POST")
        {
            $string=$request->request->get('string');
            $em=$this->getDoctrine()->getManager();
            
            $query = $em->createQuery('SELECT c.name, c.id, c.description 
                                       FROM Queskey\FrontEndBundle\Entity\Course c 
                                       where c.name LIKE :string')->setParameter('string', '%'.$string.'%');

            $result = $query->getResult();
            
            if($result)
                {
                $response=new Response(json_encode($result));
                return $response;
                }
                
                else 
                {
                $response=new Response(json_encode(array("0"=>"fail")));
                return $response;
                
                }
                
            }       
            else
            {
                $response = new Response(json_encode(array("0"=>"fail")));
                return $response;
            }
        }
                
    

    
      
    public function filterAction()
    {
        $request = $this->get('request');

        if($request->isXmlHttpRequest() && $request->getMethod()=="POST")
        {
            
            $subCategories = $request->get('subcat');        
            $this->dbHandle($subCategories);          
            $courses = $this->array;          
            $response = new Response(json_encode($courses));
            return $response;
        }
        
        $response = new Response(json_encode(array( '0'=>"error")));
        return $response;
    }

    
    
    public function dbHandle($subCategories)
    {
        $em = $this->getDoctrine()->getManager();
        $courses = array();
            
        foreach($subCategories as $subcat)
        {
            $courseQuery = $em->createQuery('SELECT c.id, c.name, c.description 
                                             FROM Queskey\FrontEndBundle\Entity\Course c  
                                             where c.subcat = :subcat and c.published = 1')->setParameter('subcat' , $subcat);

            $courses = $courseQuery->getResult();
            $this->multiToOne($courses);
        }
        return ;
    }

    
    
    public function multiToOne($courses)
    {
        foreach($courses as $course)
        {
        $this->array[] = $course;
        }
    }
    
}
