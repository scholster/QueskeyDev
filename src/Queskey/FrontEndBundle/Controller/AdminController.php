<?php

namespace Queskey\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller {

    public function checkadminAction() {
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
        $session->start();
        $loggedInUser = $session->get("User");
/*$checkadmin = new AdminCourse();
        return $checkadmin->checkadminAction();*/
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
                
                $id=$newCourse->getId();
                
                //send data back to js page
                $response = new Response(json_encode(array("0" => $id)));
                return $response;
            } else {
                $response = new Response(json_encode(array("0" => '0')));
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
                foreach ($courses as $key => $course) {
                    $courses[$key]['url'] = $this->generateUrl('course', array('id' => $course['id']));
                }
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

                /* $qry = $em->createQuery('SELECT p.course 
                  FROM Queskey\FrontEndBundle\Entity\PaymentAssociation p
                  WHERE p.course=:id')->setParameter('id', $data['course_id']);
                  $res = $qry->getResult(); */

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

            $coursepay_query = $em->createQuery('SELECT c.name, c.description, c.id, pp.price, pp.expirytime, pp.discountPercent, pp.resubscriptionPrice, pp.description as ppdesc, pp.id as planid
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

    public function paymentplanupdateAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $qry = $em->createQuery('UPDATE \Queskey\FrontEndBundle\Entity\PaymentPlans P
                                       SET P.price=:price, P.expirytime=:exptime, P.discountPercent=:discper, P.resubscriptionPrice=:resubprice, P.description=:desc
                                       WHERE P.id=:id
                                       ')->setParameters(array(
                    'id' => $data['plan_id'],
                    'price' => $data['price'],
                    'exptime' => $data['expirytime'],
                    'discper' => $data['discount'],
                    'resubprice' => $data['resubprice'],
                    'desc' => $data['paydescription']
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

    public function topicsAction($courseId=null) {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
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

                if ($courseId != NULL) {
                    $qry = $em->createQuery('SELECT C.id, C.name, C.description, s.name as sname, c.name as cname 
                                   FROM \Queskey\FrontEndBundle\Entity\Course C
                                   JOIN C.subcat s
                                   JOIN s.cat c 
                                   WHERE C.id=:cid and C.instructor=:iid')->setParameters(array(
                                                                        'cid' => $courseId,
                                                                        'iid' => $instructorId ));
                    $course = $qry->getResult();
                    if($course == NULL){
                        return $this->render('FrontEndBundle:Index:index.html.twig');
                    }
                }
            else
            {
                $qry = $em->createQuery('SELECT C.id, C.name, C.description, s.name as sname, c.name as cname 
                                   FROM \Queskey\FrontEndBundle\Entity\Course C
                                   JOIN C.subcat s
                                   JOIN s.cat c
                                   WHERE C.instructor=:iid
                                   ORDER BY C.id DESC')->setParameter('iid',$instructorId);
                $course = $qry->setMaxResults(1)->getResult();
                if($course == NULL){
                        return $this->render('FrontEndBundle:Index:index.html.twig');
                    }
            }

            $subjectqry = $em->createQuery('SELECT Cs.id, Cs.subjectname
                                   FROM \Queskey\FrontEndBundle\Entity\Coursesubjects Cs
                                   JOIN Cs.courseid c
                                   WHERE c.id=:id')->setParameter('id', $course[0]['id']);
            $subject = $subjectqry->getResult();

            $subdescqry = $em->createQuery('SELECT C.subjectdescription
                                            FROM \Queskey\FrontEndBundle\Entity\Coursesubjects C
                                            WHERE C.id=:id')->setParameter('id', $subject[0]['id']);
            $subdesc = $subdescqry->getResult();

            $topics_qry = $em->createQuery('SELECT Ct.id,Ct.topicname
                                          FROM \Queskey\FrontEndBundle\Entity\Coursetopics Ct
                                          JOIN Ct.subjectid si
                                          WHERE si.id=:sid')->setParameter('sid',$subject[0]['id']);
            $topics = $topics_qry->getResult();
            
            $topdescqry = $em->createQuery('SELECT C.topicdescription
                                            FROM \Queskey\FrontEndBundle\Entity\Coursetopics C
                                            WHERE C.id=:id')->setParameter('id', $topics[0]['id']);
            $topdesc = $topdescqry->getResult();
            
            $lessons_qry = $em->createQuery('SELECT Cl.id,Cl.lessonname
                                          FROM \Queskey\FrontEndBundle\Entity\Courselessons Cl
                                          JOIN Cl.topicid ti
                                          WHERE ti.id=:tid')->setParameter('tid',$topics[0]['id']);
            $lessons = $lessons_qry->getResult();

            $lestypeqry = $em->createQuery('SELECT C.lessontype
                                            FROM \Queskey\FrontEndBundle\Entity\Courselessons C
                                            WHERE C.id=:id')->setParameter('id', $lessons[0]['id']);
            $lestype = $lestypeqry->getResult();
            
            $innerlessons_qry = $em->createQuery('SELECT Il.id,Il.innerlessonname
                                          FROM \Queskey\FrontEndBundle\Entity\Courseinnerlessons Il
                                          JOIN Il.lessonid li
                                          WHERE li.id=:lid')->setParameter('lid',$lessons[0]['id']);
            $innerlessons = $innerlessons_qry->getResult();
            
            $contentqry = $em->createQuery('SELECT Lc.id, Lc.content
                                            FROM \Queskey\FrontEndBundle\Entity\Lessoncontents Lc
                                            JOIN Lc.innerlessonid il
                                            WHERE il.id=:id')->setParameter('id',$innerlessons[0]['id']);
            $contents = $contentqry->getResult();

            $ques_qry = $em->createQuery('SELECT Q.id, Q.question 
                                        FROM \Queskey\FrontEndBundle\Entity\Questions Q
                                        JOIN Q.lessonid li
                                        JOIN li.topicid ti
                                        JOIN ti.subjectid si                    
                                        WHERE si.id=:s and ti.id=:t and li.id=:l')->setParameters(array('s' => $subject[0]['id'],
                                                                                                        't' => $topics[0]['id'],
                                                                                                        'l' => $lessons[0]['id'] ));
            $questions = $ques_qry->getResult();

            $quiz_qry = $em->createQuery('SELECT Q.id FROM \Queskey\FrontEndBundle\Entity\Quiz Q ');
            $quiz = $quiz_qry->getResult();

            $quiz_ques_qry = $em->createQuery('SELECT Q.id,Q.question
                                             FROM \Queskey\FrontEndBundle\Entity\Questions Q
                                             JOIN Q.quizid qi
                                             WHERE qi.id=:qid')->setParameter('qid', $quiz[0]['id']);
            $quiz_ques = $quiz_ques_qry->getResult();

            return $this->render('FrontEndBundle:Admin:admin_topics.html.twig', array('course' => $course, 'category' => $category,
                        'subcategory' => $sub_cat, 'subject' => $subject, 'topic' => $topics, 'lesson' => $lessons,
                        'innerlesson' => $innerlessons, 'question' => $questions, 'quiz' => $quiz, 'quizques' => $quiz_ques,
                        'subdesc' => $subdesc, 'topdesc' => $topdesc, 'lestype' => $lestype, 'contents' => $contents));
        } else {
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

                $qry = $em->createQuery('UPDATE \Queskey\FrontEndBundle\Entity\Course C
                                       SET C.name=:name, C.subcat=:subcat, C.description=:desc
                                       WHERE C.id=:id
                                       ')->setParameters(array(
                    'id' => $data['courseid'],
                    'name' => $data['coursename'],
                    'subcat' => $data['subcat'],
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

                $gettopics = $em->createQuery('SELECT Ct.id, Ct.topicname
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

    public function storeinnerlessonAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $newInnerLesson = new \Queskey\FrontEndBundle\Entity\Courseinnerlessons;
                $lessonId = $this->getDoctrine()->getRepository('FrontEndBundle:Courselessons')->find($data['innerlesson_lessonid']);
                $newInnerLesson->setLessonid($lessonId);

                $newInnerLesson->setInnerlessonname($data['innerlesson_name']);

                $em->persist($newInnerLesson);
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

    public function viewinnerlessonsAction() {
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();

            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $getinnerlessons = $em->createQuery('SELECT Il.id, Il.innerlessonname
                        FROM Queskey\FrontEndBundle\Entity\Courseinnerlessons Il
                        JOIN Il.lessonid li
                        WHERE li.id=:lid
                        ')->setParameter('lid', $data['topic_lesson']);
                $innerlessons = $getinnerlessons->getResult();
                
                if ($innerlessons) {
                    $response = new Response(json_encode($innerlessons));
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
                $innerlessonId = $this->getDoctrine()->getRepository('FrontEndBundle:Courseinnerlessons')->find($data['content_inner_lessonid']);
                $newContent->setInnerlessonid($innerlessonId);
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
                $options = json_decode($data['options']);
                /* var_dump($data);
                  die; */
                $em = $this->getDoctrine()->getManager();

                $newQuestion = new \Queskey\FrontEndBundle\Entity\Questions;

                $subjectId = $this->getDoctrine()->getRepository('FrontEndBundle:Coursesubjects')->find($data['subjectid']);
                $newQuestion->setSubjectid($subjectId);

                $topicId = $this->getDoctrine()->getRepository('FrontEndBundle:Coursetopics')->find($data['topicid']);
                $newQuestion->setTopicid($topicId);

                $newQuestion->setQuestion($data['question']);

                if ($options[0] != NULL)
                    $newQuestion->setOption1($options[0]);
                if ($options[1] != NULL)
                    $newQuestion->setOption2($options[1]);
                if ($options[2] != NULL)
                    $newQuestion->setOption3($options[2]);
                if ($options[3] != NULL)
                    $newQuestion->setOption4($options[3]);
                if ($options[4] != NULL)
                    $newQuestion->setOption5($options[4]);

                $newQuestion->setCorrectoption($data['correctopt']);
                $newQuestion->setSolution($data['solution']);
                $newQuestion->setLevel($data['level']);

                if ($data['lessonid'] != NULL) {
                    $lessonId = $this->getDoctrine()->getRepository('FrontEndBundle:Courselessons')->find($data['lessonid']);
                    $newQuestion->setLessonid($lessonId);
                }

                $em->persist($newQuestion);
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

    public function store_dataquestionAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                var_dump($data);
                die;
                $em = $this->getDoctrine()->getManager();
                $newData = new \Queskey\FrontEndBundle\Entity\QuestionData;
                $newData->setData($data['ques_data']);
                $em->persist($newData);
                $em->flush();

                $dataid = $newData->getId();

                $newQuestion = new \Queskey\FrontEndBundle\Entity\Questions;

                $subjectId = $this->getDoctrine()->getRepository('FrontEndBundle:Coursesubjects')->find($data['subjectid']);
                $newQuestion->setSubjectid($subjectId);

                $topicId = $this->getDoctrine()->getRepository('FrontEndBundle:Coursetopics')->find($data['topicid']);
                $newQuestion->setTopicid($topicId);

                $dataId = $this->getDoctrine()->getRepository('FrontEndBundle:QuestionData')->find($dataid);
                $newQuestion->setDataid($dataId);

                if ($data['lessonid'] != NULL) {
                    $lessonId = $this->getDoctrine()->getRepository('FrontEndBundle:Courselessons')->find($data['lessonid']);
                    $newQuestion->setLessonid($lessonId);
                }

                $newQuestion->setLevel($data['level']);

                $options_1 = json_decode($data['options_1']);
                $options_2 = json_decode($data['options_2']);
                $options_3 = json_decode($data['options_3']);

                $newQuestion->setQuestion($data['question_1']);
                if ($options_1[0] != NULL)
                    $newQuestion->setOption1($options_1[0]);
                if ($options_1[1] != NULL)
                    $newQuestion->setOption2($options_1[1]);
                if ($options_1[2] != NULL)
                    $newQuestion->setOption3($options_1[2]);
                if ($options_1[3] != NULL)
                    $newQuestion->setOption4($options_1[3]);
                if ($options_1[4] != NULL)
                    $newQuestion->setOption5($options_1[4]);

                $newQuestion->setCorrectoption($data['correctopt_1']);
                $newQuestion->setSolution($data['solution_1']);

                $em->persist($newQuestion);
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

    public function storequizAction() {
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $ques = json_decode($data['quesid']);
                $em = $this->getDoctrine()->getManager();

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
    
    public function viewsubdescAction() {
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();

            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $subdescqry = $em->createQuery('SELECT C.subjectname, C.subjectdescription
                                            FROM \Queskey\FrontEndBundle\Entity\Coursesubjects C
                                            WHERE C.id=:id')->setParameter('id', $data['subject']);
                $subdesc = $subdescqry->getResult();

                if ($subdesc) {
                    $response = new Response(json_encode($subdesc));
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
    
    public function viewtopdescAction() {
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();

            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $topdescqry = $em->createQuery('SELECT C.topicname, C.topicdescription
                                            FROM \Queskey\FrontEndBundle\Entity\Coursetopics C
                                            WHERE C.id=:id')->setParameter('id', $data['topic']);
                $topdesc = $topdescqry->getResult();

                if ($topdesc) {
                    $response = new Response(json_encode($topdesc));
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

    public function viewlestypeAction() {
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();

            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $lestypeqry = $em->createQuery('SELECT C.lessonname, C.lessontype
                                            FROM \Queskey\FrontEndBundle\Entity\Courselessons C
                                            WHERE C.id=:id')->setParameter('id', $data['lesson']);
                $lestype = $lestypeqry->getResult();

                if ($lestype) {
                    $response = new Response(json_encode($lestype));
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
    
    public function viewcontentAction() {
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();

            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $contentqry = $em->createQuery('SELECT Il.content, Il.id
                                            FROM \Queskey\FrontEndBundle\Entity\Lessoncontents Il
                                            JOIN Il.innerlessonid il
                                            WHERE il.id=:id')->setParameter('id', $data['ilessonid']);
                $content = $contentqry->getResult();

                $ilnameqry = $em->createQuery('SELECT Il.innerlessonname
                                               FROM \Queskey\FrontEndBundle\Entity\Courseinnerlessons Il
                                               WHERE Il.id=:id')->setParameter('id', $data['ilessonid']);
                $ilname = $ilnameqry->getResult();
                if ($content && $ilname) {
                    $response = new Response(json_encode(array("0"=>$ilname,"1"=>$content)));
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
    
    public function view_sub_questionsAction() {
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();

            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $getquestions = $em->createQuery('SELECT Q.id, Q.question
                        FROM Queskey\FrontEndBundle\Entity\Questions Q
                        JOIN Q.subjectid si
                        WHERE si.id=:sid
                        ')->setParameter('sid', $data['subid']);
                $questions = $getquestions->getResult();

                if ($questions) {
                    $response = new Response(json_encode($questions));
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

    public function view_topic_questionsAction() {
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();

            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $getquestions = $em->createQuery('SELECT Q.id, Q.question
                        FROM Queskey\FrontEndBundle\Entity\Questions Q
                        JOIN Q.topicid si
                        WHERE si.id=:sid
                        ')->setParameter('sid', $data['topicid']);
                $questions = $getquestions->getResult();

                if ($questions) {
                    $response = new Response(json_encode($questions));
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

    public function view_lesson_questionsAction() {
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();

            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                if ($data['lessonid'] != 0) {
                    $getquestions = $em->createQuery('SELECT Q.id, Q.question
                        FROM Queskey\FrontEndBundle\Entity\Questions Q
                        JOIN Q.lessonid li
                        WHERE li.id=:lid
                        ')->setParameter('lid', $data['lessonid']);
                    $questions = $getquestions->getResult();
                } else {
                    $getquestions = $em->createQuery('SELECT Q.id, Q.question
                        FROM Queskey\FrontEndBundle\Entity\Questions Q
                        JOIN Q.topicid ti
                        WHERE ti.id=:tid
                        ')->setParameter('tid', $data['topicid']);
                    $questions = $getquestions->getResult();
                }

                if ($questions) {
                    $response = new Response(json_encode($questions));
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

    public function view_quiz_questionsAction() {
        if ($this->checkadminAction()) {
            $em = $this->getDoctrine()->getManager();

            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $quiz_ques_qry = $em->createQuery('SELECT Q.id,Q.question
                                             FROM \Queskey\FrontEndBundle\Entity\Questions Q
                                             JOIN Q.quizid qi
                                             WHERE qi.id=:qid')->setParameter('qid', $data['quizid']);
                $quiz_ques = $quiz_ques_qry->getResult();

                if ($quiz_ques) {
                    $response = new Response(json_encode($quiz_ques));
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

}