<?php

namespace Krombox\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Krombox\MainBundle\DBAL\Types\CategoryType;
//use Krombox\MainBundle\Entity\Category;
use Krombox\MainBundle\Model\PlaceSearch;
use Krombox\MainBundle\Form\Type\PlaceType;
use Krombox\MainBundle\Form\Type\Filter\PlaceFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{
    /**
     * @FW\Route("/category/list/{type}", name="category_list")          
     */
    public function categoriesListAction($type)
    {   
        switch ($type){
            case 'place':
                $categoryType = CategoryType::PLACE;
                break;
            case 'event':
                $categoryType = CategoryType::EVENT;
                break;
        }
        
        $em = $this->getDoctrine()->getManager();          
        $categories = $em->getRepository(Category::class)->getCategoriesByType($categoryType, true);
        
        return $this->render('KromboxMainBundle:Category:list.html.twig', array('categories' => $categories, 'type' => $type));
    }        
}
