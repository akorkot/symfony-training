<?php

namespace Blogger\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BlogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BlogRepository extends EntityRepository
{
    
    /*public function createQueryBuilder($alias)
    {
        return $this->_em->createQueryBuilder()
            ->select($alias)
            ->from($this->_entityName, $alias);
    }*/

    public function getLatestBlogs($limit = null)
    {
        $qb = $this->createQueryBuilder('b')
                   ->select('b, c')
                   ->leftJoin('b.comments', 'c')
                   ->addOrderBy('b.created', 'DESC');

        if (false === is_null($limit)) {
            $qb->setMaxResults($limit);
        }
        
        return $qb->getQuery()
                  ->getResult();
    }

    public function getTags()
    {
        $blogTags = $this->createQueryBuilder('b')
                         ->select('b.tags')
                         ->getQuery()
                         ->getResult();

        $tags = array();
        foreach ($blogTags as $blogTag)
        {
            $bTags = explode(",", $blogTag['tags']);
            $tags = array_merge($bTags, $tags);
        }

        foreach ($tags as &$tag)
        {
            $tag = trim($tag);
        }

        return $tags;
    }

    public function getTagWeights($tags)
    {
        $tagWeights = array();
        
        if (empty($tags)) {
            return $tagWeights;
        }
        
        foreach ($tags as $tag)
        {
            $tagWeights[$tag] = (isset($tagWeights[$tag])) ? $tagWeights[$tag] + 1 : 1;
        }
        
        // Shuffle the tags
        uksort($tagWeights, function() {
            return rand() > rand();
        });
        
        return $tagWeights;
    }
    
}


