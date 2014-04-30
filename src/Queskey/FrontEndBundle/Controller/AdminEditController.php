<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminEditController extends Controller {

    public function checkadminAction() {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $session->start();
        $loggedInUser = $session->get("User");
        if ($loggedInUser) {
            $instructorId = $loggedInUser->getId();
            return $instructorId;
        } else {
            return false;
        }
    }

    public function editsubjectAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $qry = $em->createQuery('UPDATE \Queskey\FrontEndBundle\Entity\Coursesubjects Cs
                                       SET Cs.subjectname=:sname, Cs.subjectdescription=:desc
                                       WHERE Cs.id=:id
                                       ')->setParameters(array(
                    'id' => $data['subjectid'],
                    'sname' => $data['subname'],
                    'desc' => $data['description']
                ));

                $result = $qry->execute();
                $em->flush();

                //send data back to js page
                $response = new Response(json_encode(array("0" => 'success')));
                return $response;
            } else {
                $response = new Response(json_encode(array("0" => 'fail')));
                return $response;
            }
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }

    
    public function edittopicAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $qry = $em->createQuery('UPDATE \Queskey\FrontEndBundle\Entity\Coursetopics Cs
                                       SET Cs.topicname=:sname, Cs.topicdescription=:desc
                                       WHERE Cs.id=:id
                                       ')->setParameters(array(
                    'id' => $data['topicid'],
                    'sname' => $data['topname'],
                    'desc' => $data['description']
                ));

                $result = $qry->execute();
                $em->flush();

                //send data back to js page
                $response = new Response(json_encode(array("0" => 'success')));
                return $response;
            } else {
                $response = new Response(json_encode(array("0" => 'fail')));
                return $response;
            }
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }
    
    public function editlessonAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                    $qry = $em->createQuery('UPDATE \Queskey\FrontEndBundle\Entity\Courselessons Cl
                                       SET Cl.lessonname=:lname, Cl.lessontype=:lestype
                                       WHERE Cl.id=:id
                                       ')->setParameters(array(
                    'id' => $data['lessonid'],
                    'lname' => $data['lesname'],
                    'lestype' => $data['lestype']
                ));

                $result = $qry->execute();
                $em->flush();

                //send data back to js page
                $response = new Response(json_encode(array("0" => 'success')));
                return $response;
            } else {
                $response = new Response(json_encode(array("0" => 'fail')));
                return $response;
            }
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }
    
    public function editinnerlessonAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                    $qry = $em->createQuery('UPDATE \Queskey\FrontEndBundle\Entity\Courseinnerlessons Cil
                                       SET Cil.innerlessonname=:ilname
                                       WHERE Cil.id=:id
                                       ')->setParameters(array(
                    'id' => $data['innerlessonid'],
                    'ilname' => $data['innerlesname']
                ));

                $result = $qry->execute();
                $em->flush();

                if($data['content']!= NULL){
                $qry1 = $em->createQuery('UPDATE \Queskey\FrontEndBundle\Entity\Lessoncontents Cil
                                       SET Cil.content=:cont
                                       WHERE Cil.id=:id
                                       ')->setParameters(array(
                    'id' => $data['contentid'],
                    'cont' => $data['content']
                ));

                $result = $qry1->execute();
                $em->flush();}
                
                //send data back to js page
                $response = new Response(json_encode(array("0" => 'success')));
                return $response;
            } else {
                $response = new Response(json_encode(array("0" => 'fail')));
                return $response;
            }
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }
    
    public function edit_quizAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $ques = json_decode($data['quesid']);
                $em = $this->getDoctrine()->getManager();
var_dump($data);
die;
                $newQuiz = new \Queskey\FrontEndBundle\Entity\Quiz;

                $courseId = $this->getDoctrine()->getRepository('FrontEndBundle:Course')->find($data['courseid']);
                $newQuiz->setCourseid($courseId);
                if ($data['quiztype'] != 4) {
                    $subjectId = $this->getDoctrine()->getRepository('FrontEndBundle:Coursesubjects')->find($data['subjectid']);
                    $newQuiz->setSubjectid($subjectId);
                    if ($data['quiztype'] != 1) {
                        $topicId = $this->getDoctrine()->getRepository('FrontEndBundle:Coursetopics')->find($data['topicid']);
                        $newQuiz->setTopicid($topicId);
                        if ($data['lessonid'] != NULL) {
                            $lessonId = $this->getDoctrine()->getRepository('FrontEndBundle:Courselessons')->find($data['lessonid']);
                            $newQuiz->setLessonid($lessonId);
                        }
                    }
                }
                $type = $this->getDoctrine()->getRepository('FrontEndBundle:Quiztypes')->find($data['quiztype']);
                $newQuiz->setQuiztype($type);

                $c = count($ques);
                for ($x = 0; $x < $c; $x++) {
                    $quesId = $this->getDoctrine()->getRepository('FrontEndBundle:Questions')->find($ques[$x]);
                    $newQuiz->addQuestionid($quesId);
                }
                $em->persist($newQuiz);
                $em->flush();

                //send data back to js page
                $response = new Response(json_encode(array("0" => 'success')));
                return $response;
            } else {
                $response = new Response(json_encode(array("0" => 'fail')));
                return $response;
            }
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }
}
    