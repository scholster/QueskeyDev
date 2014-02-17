<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller {

     public function checkadminAction() {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $session->start();
        $loggedInUser = $session->get("User");
        
        if($loggedInUser)
        {
        return true;
        }
        else
        {
            return $this->render('FrontEndBundle:Index:dashboard.html.twig');
        }
    }
    
    
    public function dashboardAction() {
        if($this->checkadminAction())
        {
        $em = $this->getDoctrine()->getManager();

        $query1 = $em->createQuery(
                'SELECT C.id, C.name
                    FROM Queskey\FrontEndBundle\Entity\Category C
                    WHERE C.published=1
                    ');
        $category = $query1->getResult();
       /*var_dump($category);
          die*/
//for default values
       $a = $category[0]['id'];
        $query2 = $em->createQuery(
                        'SELECT SC.id,SC.name
                        FROM Queskey\FrontEndBundle\Entity\SubCategory SC
                        WHERE SC.cat=:cat and SC.published=1
                        ')->setParameter('cat', $a);
        $sub_cat = $query2->getResult();
          
        $request = $this->get("request");
        if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
            $data = $request->request->all();
            $em = $this->getDoctrine()->getManager();


            $query3 = $em->createQuery(
                            'SELECT SC.id,SC.name
                        FROM Queskey\FrontEndBundle\Entity\SubCategory SC
                        WHERE SC.cat=:cat and SC.published=1
                        ')->setParameter('cat', $data['categoryid']);
            $sub_cat = $query3->getResult();
            if ($sub_cat) {
                $response = new Response(json_encode($sub_cat));
                return $response;
            } else {
                $response = new Response(json_encode(array("0" => 'fail')));
                return $response;
            }
        }
        
        
        return $this->render('FrontEndBundle:Admin:admindashboard.html.twig', array('category' => $category,'subcategory'=>$sub_cat));
        }
        
    }

    
   
    
    public function storecourseAction(){
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $session->start();
        $loggedInUser = $session->get("User");
        $instructorId = $loggedInUser->getId();
        $request=$this->get("request");
        if($request->isXmlHttpRequest() && $request->getMethod()=="POST")
        {
            $data=$request->request->all();
            $em=$this->getDoctrine()->getManager();
            
            $newCourse=new \Queskey\FrontEndBundle\Entity\Course;
            //error in the commented lines below
            //$newCourse->setSubcat($data['subcat']);
            $newCourse->setName($data['coursename']);
            //$newCourse->setInstructor($instructorId);
            $newCourse->setDescription($data['description']);
            
            $em->persist($newCourse);
            $em->flush();
        }
    }

}