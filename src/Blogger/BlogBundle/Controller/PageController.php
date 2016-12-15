<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType; 

class PageController extends Controller
{
    public function indexAction()
    {   
        
        $em = $this->getDoctrine()
                   ->getEntityManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
                    ->getLatestBlogs();
        
        return $this->render('BloggerBlogBundle:Page:index.html.twig', array(
            "blogs" => $blogs
        ));
    }

    public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }

    public function contactAction()
    {
        $enquiry = new Enquiry();
        $enquiryType = new EnquiryType();

        $form = $this->createForm($enquiryType, $enquiry);

        $request = $this->getRequest();
        $form->bind($request);

        if ($form->isValid()) { 

            $templateBody = $this->renderView('BloggerBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry));

            $message = \Swift_Message::newInstance();
            $message->setSubject('Contact enquiry from symblog');
            $message->setFrom('ayoub.korkot@gmail.com');
            $message->setTo($this->container->getParameter('blogger_blog.emails.contact_email'));
            $message->setBody($templateBody);
            
            $this->get('mailer')->send($message);
            $this->get('session')->getFlashBag()->set('blogger-notice', 'Votre message a été bien envoyé.');
            
            
            $generatedUrl = $this->generateUrl('BloggerBlogBundle_contact');
            
            return $this->redirect($generatedUrl);
        }
        
        return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
            'form' => $form->createView()
        ));
    }
}