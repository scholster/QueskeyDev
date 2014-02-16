<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function searchAction()
    {
        $request=$this->get('request');
        if($request->isXmlHttpRequest() && $request->getMethod()=="POST")
        {
            $data=$request->request->all();
            //var_dump($data);
            //$cat=$data['text'];
            //$data = strtoupper($data);
            $em=$this->getDoctrine()->getManager();
            
            /*$query=$em->createQuery(
                    'SELECT id
                     FROM FrontEndBundle:CourseDetails p
                     WHERE p.category == :cat'
                        )->setParameter('cat',$cat);
                     $repository = $this->getDoctrine()->getRepository('FrontEndBundle:CourseDetails');*/
            
            $searchRepository=$em->getRepository('FrontEndBundle:CourseDetails');
            $search = $searchRepository->findBy(array("category"=>$data['text']));
            //var_dump($search);
            /*$query=$searchRepository->createQueryBuilder('p')
                    ->where('p.category' === $data['text'] )
                    ->orderBy('p.category','ASC')
                    ->getQuery();*/
            $count=count($search);
            //var_dump($count);
            //$search = $searchRepository->findOneBy(array("category"=>$data['text']));
            $searchDetails = array();
            if($count>0)
                
            {
                $j=1;
                for($i=0;$i<$count;$i++)
                {
                //$searchDetails[] = $search[$i]->getId();
                
                $searchDetails[] = array(array($search[$i]->getId(),$search[$i]->getCourseName()));
                $j=$j+1;
               // var_dump($searchDetails);
                }
                
                $response=new Response(json_encode($searchDetails));
                return $response;
                /*$searchDetails = array
                        (
                    [$i]=>array
                        (
                        $search[$i]->getId(),
                        $search[$i]->getName(),
                        $search[$i]->getImage(),
                        )
                        );*/
                
            }       
            else
            {
                $response = new Response(json_encode(array("0"=>"fail")));
                return $response;
            }
        }
                
    }

}
