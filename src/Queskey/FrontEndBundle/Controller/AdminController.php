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
            /* var_dump($category);
              die; */
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
        //$session = new \Symfony\Component\HttpFoundation\Session\Session();
        //$session->start();
        // $loggedInUser = $session->get("User");
        //$instructorId = $loggedInUser->getId();
        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                $newCourse = new \Queskey\FrontEndBundle\Entity\Course;
                //error in the commented lines below
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
        /* $session = new \Symfony\Component\HttpFoundation\Session\Session();
          $session->start();
          $loggedInUser = $session->get("User");
          $instructorId = $loggedInUser->getId(); */

        $instructorId = $this->checkadminAction();
        if ($instructorId) {
            $request = $this->get("request");
            if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();
                //$courseId=$data['course_id']; 

                $qry = $em->createQuery('SELECT p.course FROM Queskey\FrontEndBundle\Entity\PaymentAssociation p WHERE p.course=:id')->setParameter('id', $data['course_id']);
                $res = $qry->getResult();
                var_dump($res);
                die;
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

}