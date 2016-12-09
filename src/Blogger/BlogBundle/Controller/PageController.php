<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;
use Symfony\Component\HttpFoundation\Session\Session;

class PageController extends Controller
{
    public function indexAction()
    {   
        
        $em = $this->getDoctrine()
                   ->getEntityManager();

        $blogs = $em->createQueryBuilder()
                    ->select('b')
                    ->from('BloggerBlogBundle:Blog',  'b')
                    ->addOrderBy('b.created', 'DESC')
                    ->getQuery()
                    ->getResult();
        
        var_dump($blogs);
        
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
 
	    if ($request->getMethod() == 'POST') {
	        $form->bind($request);
                
                    if ($form->isValid()) {
	            // Perform some action, such as sending an email
                     
                        
                    $templateBody = $this->renderView('BloggerBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry));
                    
                    $message = \Swift_Message::newInstance();
                    $message->setSubject('Contact enquiry from symblog');
                    $message->setFrom('ayoub.korkot@gmail.com');
                    $message->setTo($this->container->getParameter('blogger_blog.emails.contact_email'));
                    $message->setBody($templateBody);

                    $this->get('mailer')->send($message);
            
                    
                    $this->get('session')->getFlashBag()->set('blogger-notice', 'Votre message a été bien envoyé.');
                     
	            // Redirect - This is important to prevent users re-posting
	            // the form if they refresh the page
	            return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));
	        }
	    }

	    return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
	        'form' => $form->createView()
	    ));
	}
}