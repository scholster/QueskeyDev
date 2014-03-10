<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller {

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

    public function dashboardAction() {
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();

            $query1 = $em->createQuery(
                    'SELECT C.id, C.name
                    FROM Queskey\FrontEndBundle\Entity\Category C
                    WHERE C.published=1
                    ');
            $category = $query1->getResult();
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

                $query3 = $em->createQuery('SELECT SC.id,SC.name
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

            return $this->render('FrontEndBundle:Admin:admindashboard.html.twig', array('category' => $category, 'subcategory' => $sub_cat));
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }

    public function storecourseAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $newCourse = new \Queskey\FrontEndBundle\Entity\Course;
                $subCat = $this->getDoctrine()->getRepository('FrontEndBundle:SubCategory')->find($data['subcat']);
                $newCourse->setSubcat($subCat);

                //$newCourse->setSubcat($data['subcat']);
                $newCourse->setName($data['coursename']);

                $instId = $this->getDoctrine()->getRepository('FrontEndBundle:User')->find($instructorId);
                $newCourse->setInstructor($instId);
                $newCourse->setDescription($data['description']);
                $newCourse->setPublished('1');

                $em->persist($newCourse);
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

    //view all the courses created by instructor
    public function viewcourseAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $em = $this->getDoctrine()->getManager();

            $course_query = $em->createQuery(
                            'SELECT C.name,C.description,C.id
                             FROM Queskey\FrontEndBundle\Entity\Course C
                             WHERE C.instructor=:iid
                             ')->setParameter('iid', $instructorId);

            $courses = $course_query->getResult();

            if ($courses) {
                $response = new Response(json_encode($courses));
                return $response;
            } else {
                $response = new Response(json_encode(array("0" => 'fail')));
                return $response;
            }
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }

    //list of all courses for which payment plan has not been created
    public function createpplancourseAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $em = $this->getDoctrine()->getManager();

            $course_query = $em->createQuery('SELECT c.name, c.description, c.id
                                              FROM Queskey\FrontEndBundle\Entity\Course c
                                              JOIN c.instructor u
                                              WHERE u.id = :id and c.id NOT IN (SELECT ci.id FROM Queskey\FrontEndBundle\Entity\PaymentAssociation pa JOIN pa.course ci)
                                              ')->setParameter('id', $instructorId);
            $courses = $course_query->getResult();

            if ($courses) {
                $response = new Response(json_encode($courses));
                return $response;
            } else {
                $response = new Response(json_encode(array("0" => 'fail')));
                return $response;
            }
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }

    public function createpplanviewAction() {
        if ($this->checkadminAction()) {
            return $this->render('FrontEndBundle:Admin:admin_paymentplan.html.twig');
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }

    public function paymentplancreateAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();
                //$courseId=$data['course_id']; 

                $qry = $em->createQuery('SELECT p.course FROM Queskey\FrontEndBundle\Entity\PaymentAssociation p WHERE p.course=:id')->setParameter('id', $data['course_id']);
                $res = $qry->getResult();
    
                $newPaymentPlan = new \Queskey\FrontEndBundle\Entity\PaymentPlans;

                $newPaymentPlan->setPrice($data['price']);
                $newPaymentPlan->setExpirytime($data['expirytime']);
                $newPaymentPlan->setDiscountPercent($data['discount']);
                $newPaymentPlan->setResubscriptionPrice($data['resubprice']);
                $newPaymentPlan->setDescription($data['paydescription']);

                $em->persist($newPaymentPlan);
                $em->flush();

                $lastpayid = $newPaymentPlan->getId();

                $newPaymentPlanAssociation = new \Queskey\FrontEndBundle\Entity\PaymentAssociation;

                $courseId = $this->getDoctrine()->getRepository('FrontEndBundle:Course')->find($data['course_id']);
                $newPaymentPlanAssociation->setCourse($courseId);

                $payplanId = $this->getDoctrine()->getRepository('FrontEndBundle:PaymentPlans')->find($lastpayid);
                $newPaymentPlanAssociation->setPaymentplan($payplanId);
                $newPaymentPlanAssociation->setAllLimit('1');

                $em->persist($newPaymentPlanAssociation);
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

    public function updatepplanviewAction() {
        if ($this->checkadminAction()) {
            return $this->render('FrontEndBundle:Admin:admin_updatepaymentplan.html.twig');
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }

    //list of courses for which payment plan has been created
    public function updatepplancourseAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $em = $this->getDoctrine()->getManager();

            $coursepay_query = $em->createQuery('SELECT c.name, c.description, c.id, pp.price, pp.expirytime, pp.discountPercent, pp.resubscriptionPrice, pp.description as ppdesc
              FROM Queskey\FrontEndBundle\Entity\PaymentAssociation pass
              JOIN pass.paymentplan pp
              JOIN pass.course c
              JOIN c.instructor u
              WHERE u.id = :id')->setParameter('id', $instructorId);
            $courses = $coursepay_query->getResult();

            if ($courses) {
                $response = new Response(json_encode($courses));
                return $response;
            } else {
                $response = new Response(json_encode(array("0" => 'fail')));
                return $response;
            }
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }
    
    public function topicsAction(){
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();
            
            $query1 = $em->createQuery(
                    'SELECT C.id, C.name
                    FROM Queskey\FrontEndBundle\Entity\Category C
                    WHERE C.published=1
                    ');
            $category = $query1->getResult();
            $a = $category[0]['id'];
            $query2 = $em->createQuery(
                            'SELECT SC.id,SC.name
                        FROM Queskey\FrontEndBundle\Entity\SubCategory SC
                        WHERE SC.cat=:cat and SC.published=1
                        ')->setParameter('cat', $a);
            $sub_cat = $query2->getResult();
            
            $qry=$em->createQuery('SELECT C.id, C.name, C.description, s.name as sname, c.name as cname 
                                   FROM \Queskey\FrontEndBundle\Entity\Course C
                                   JOIN C.subcat s
                                   JOIN s.cat c
                                   ORDER BY C.id DESC');
            $course=$qry->setMaxResults(1)->getResult();
            
            $subjectqry=$em->createQuery('SELECT Cs.id, Cs.subjectname, Cs.type
                                   FROM \Queskey\FrontEndBundle\Entity\Coursesubjects Cs
                                   JOIN Cs.courseid c
                                   WHERE c.id=:id')->setParameter('id',$course[0]['id']);
            $subject = $subjectqry->getResult();
            
            $qrycon = $em->createQuery('SELECT C.content FROM \Queskey\FrontEndBundle\Entity\Lessoncontents C WHERE C.id=2');
            $content=$qrycon->getResult();
            /*var_dump($content);
            die;*/
            return $this->render('FrontEndBundle:Admin:admin_topics.html.twig',array('course' => $course, 'category'=>$category, 'subcategory'=>$sub_cat, 'subject'=>$subject, 'content'=>$content));
            
            $query1 = $em->createQuery(
                    'SELECT C.id, C.name
                    FROM Queskey\FrontEndBundle\Entity\Category C
                    WHERE C.published=1
                    ');
            $category = $query1->getResult();
            $a = $category[0]['id'];
            $query2 = $em->createQuery(
                            'SELECT SC.id,SC.name
                        FROM Queskey\FrontEndBundle\Entity\SubCategory SC
                        WHERE SC.cat=:cat and SC.published=1
                        ')->setParameter('cat', $a);
            $sub_cat = $query2->getResult();
            
            $qry=$em->createQuery('SELECT C.id, C.name, C.description, s.name as sname, c.name as cname 
                                   FROM \Queskey\FrontEndBundle\Entity\Course C
                                   JOIN C.subcat s
                                   JOIN s.cat c
                                   ORDER BY C.id DESC');
            $course=$qry->setMaxResults(1)->getResult();
            
            $subjectqry=$em->createQuery('SELECT Cs.id, Cs.subjectname, Cs.type
                                   FROM \Queskey\FrontEndBundle\Entity\Coursesubjects Cs
                                   JOIN Cs.courseid c
                                   WHERE c.id=:id')->setParameter('id',$course[0]['id']);
            $subject = $subjectqry->getResult();
            //content extracted just for an initial display
            $qrycon = $em->createQuery('SELECT C.content FROM \Queskey\FrontEndBundle\Entity\Lessoncontents C WHERE C.id=2');
            $content=$qrycon->getResult();
            /*var_dump($content);
            die;*/
            
            $topics_qry=$em->createQuery('SELECT Ct.id,Ct.topicname
                                          FROM \Queskey\FrontEndBundle\Entity\Coursetopics Ct
                                          JOIN Ct.subjectid si
                                          WHERE si.id=1');
            $topics = $topics_qry->getResult();
            
            $lessons_qry=$em->createQuery('SELECT Cl.id,Cl.lessonname
                                          FROM \Queskey\FrontEndBundle\Entity\Courselessons Cl
                                          JOIN Cl.topicid ti
                                          WHERE ti.id=1');
            $lessons = $lessons_qry->getResult();
            
            return $this->render('FrontEndBundle:Admin:admin_topics.html.twig',array('course' => $course, 'category'=>$category, 
                                 'subcategory'=>$sub_cat, 'subject'=>$subject, 'content'=>$content, 'topic'=>$topics, 'lesson'=>$lessons));
        } else 
            {
                return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }
    
    public function updatecourseAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();
              
                $qry=$em->createQuery('UPDATE \Queskey\FrontEndBundle\Entity\Course C
                                       SET C.name=:name, C.subcat=:subcat, C.description=:desc
                                       WHERE C.id=:id
                                       ')->setParameters(array(
                                                               'id'=>$data['courseid'], 
                                                               'name'=>$data['coursename'], 
                                                               'subcat'=>$data['subcat'],
                                                               'desc'=>$data['description'] 
                                                               ));

                $result=$qry->execute();
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
    
    public function storesubjectAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $newSubject = new \Queskey\FrontEndBundle\Entity\Coursesubjects;
                $courseId = $this->getDoctrine()->getRepository('FrontEndBundle:Course')->find($data['courseid']);
                $newSubject->setCourseid($courseId);

                $newSubject->setSubjectname($data['subjectname']);

                $newSubject->setSubjectdescription($data['subdescription']);
                $newSubject->setType($data['type']);

                $em->persist($newSubject);
                $em->flush();

                $newSubject->setSubjectdescription($data['subdescription']);
                $newSubject->setType($data['type']);

                $em->persist($newSubject);
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
    
    public function storetopicAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $newTopic = new \Queskey\FrontEndBundle\Entity\Coursetopics;
                $subjectId = $this->getDoctrine()->getRepository('FrontEndBundle:Coursesubjects')->find($data['subjectid']);
                $newTopic->setSubjectid($subjectId);

                $newTopic->setTopicname($data['topicname']);
                $newTopic->setTopicdescription($data['topicdescription']);
                $newTopic->setTopictype($data['type']);

                $em->persist($newTopic);
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
    
    public function viewtopicsAction() {
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();

            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $gettopics = $em->createQuery('SELECT Ct.id, Ct.topicname, Ct.topictype
                        FROM Queskey\FrontEndBundle\Entity\Coursetopics Ct
                        JOIN Ct.subjectid si
                        WHERE si.id=:sid
                        ')->setParameter('sid', $data['lesson_subject']);
                $topics = $gettopics->getResult();
                
                if ($topics) {
                    $response = new Response(json_encode($topics));
                    return $response;
                } else {
                    $response = new Response(json_encode(array("0" => 'fail')));
                    return $response;
                }
            }
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }
    
    public function storelessonAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $newLesson = new \Queskey\FrontEndBundle\Entity\Courselessons;
                $topicId = $this->getDoctrine()->getRepository('FrontEndBundle:Coursetopics')->find($data['lesson_topicid']);
                $newLesson->setTopicid($topicId);

                $newLesson->setLessonname($data['lessonname']);
                $newLesson->setLessontype($data['lessontype']);

                $em->persist($newLesson);
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
    
    public function viewlessonsAction() {
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();

            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $getlessons = $em->createQuery('SELECT Cl.id, Cl.lessonname, Cl.lessontype
                        FROM Queskey\FrontEndBundle\Entity\Courselessons Cl
                        JOIN Cl.topicid ti
                        WHERE ti.id=:tid
                        ')->setParameter('tid', $data['topic_lesson']);
                $lessons = $getlessons->getResult();
                
                if ($lessons) {
                    $response = new Response(json_encode($lessons));
                    return $response;
                } else {
                    $response = new Response(json_encode(array("0" => 'fail')));
                    return $response;
                }
            }
        } else {
            return $this->render('FrontEndBundle:Index:index.html.twig');
        }
    }
    
    public function storecontentAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $newContent = new \Queskey\FrontEndBundle\Entity\Lessoncontents;
                $lessonId = $this->getDoctrine()->getRepository('FrontEndBundle:Courselessons')->find($data['content_lessonid']);
                $newContent->setLessonid($lessonId);

                $newContent->setContentname($data['contentname']);
                $newContent->setContenttype($data['contenttype']);
                $newContent->setContent($data['content']);

                $em->persist($newContent);
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
    
   public function storequestionAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                var_dump($data);
                die;
                $em = $this->getDoctrine()->getManager();

                $newContent = new \Queskey\FrontEndBundle\Entity\Lessoncontents;
                $lessonId = $this->getDoctrine()->getRepository('FrontEndBundle:Courselessons')->find($data['content_lessonid']);
                $newContent->setLessonid($lessonId);

                $newContent->setContentname($data['contentname']);
                $newContent->setContenttype($data['contenttype']);
                $newContent->setContent($data['content']);

                $em->persist($newContent);
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