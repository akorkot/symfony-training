<?php
    
namespace Blogger\BlogBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
        
    /**
     * Show a blog entry
     */
    public function showAction($id, $slug)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $blog = $em->getRepository('BloggerBlogBundle:Blog')->find($id);
            
        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }
        
        $blog_id = $blog->getId();
        $comments = $em->getRepository('BloggerBlogBundle:Comment')
                   ->getCommentsForBlog($blog_id);
        
        return $this->render('BloggerBlogBundle:Blog:show.html.twig', array(
                'blog' => $blog,
                'comments' => $comments,
                'blog_id' => $blog_id
            )
        );
        
    }
    
    
}