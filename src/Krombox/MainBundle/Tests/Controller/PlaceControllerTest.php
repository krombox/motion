<?php

namespace Krombox\MainBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Krombox\MainBundle\Tests\UserProcessor;

class PlaceControllerTest extends WebTestCase
{
    private static $manager;      
    
    private static $fixtures;
    
    public function setUp()
    {
        ini_set('xdebug.max_nesting_level', 400);
        $client = static::createClient();        
        static::$manager = $client->getContainer()->get('h4cc_alice_fixtures.manager');
        static::$manager->addProcessor(new UserProcessor($client->getContainer()));
        static::$fixtures = static::$manager->loadFiles(
            [
                dirname(__FILE__) . '/../Fixtures/UsersSet.yml',
                dirname(__FILE__) . '/../Fixtures/PlaceAddressSet.yml',
                dirname(__FILE__) . '/../Fixtures/CategorySet.yml',
                dirname(__FILE__) . '/../Fixtures/PlaceSet.yml'                
            ]);

        static::$manager->persist(static::$fixtures, true);
    }
    
    protected function logInAsUser($username, $password)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('_submit')->form(array(
            '_username'  => $username,
            '_password'  => $password,
        ));

        $client->submit($form);
        $client->followRedirect();

        return $client;
    }
    
    public function qtestEditAsNotOwner()
    {        
        $client = $this->logInAsUser('user', 'user');
        
        $repository = $client->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(\Krombox\MainBundle\Entity\Place::class);

        $place_first  = $repository->findOneBy(array('name' => 'place_first'));                
        $crawler = $client->request('GET', '/place/' . $place_first->getSlug() . '/edit');
        
        //Must see denied access in case of user is not an owner of place
        $this->assertContains(
            'denied access',
            $client->getResponse()->getContent()
        );                        
    }
    
    public function qtestEditAsOwner()
    {        
        $client = $this->logInAsUser('admin', 'admin');
        
        $repository = $client->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(\Krombox\MainBundle\Entity\Place::class);

        $place_first  = $repository->findOneBy(array('name' => 'place_first'));                
        $crawler = $client->request('GET', '/place/' . $place_first->getSlug() . '/edit');
        
        $form = $crawler->filter('[name="krombox_mainbundle_place[save]"]')->form();        
        $form['krombox_mainbundle_place[name]'] = 'new-changed_name';
        $form['krombox_mainbundle_place[description]'] = 'new-changed_description';
        
        $crawler = $client->submit($form);
        $client->followRedirect();
        //edited place name must apper on screen
        $this->assertContains(
            'new-changed_name',
            $client->getResponse()->getContent()
        );                
    }
    
    public function testList()
    {
        $client = $this->createClient();
        
        $repository = $client->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(\Krombox\MainBundle\Entity\Place::class);
        
        $place_first = $repository->findOneBy(array('name' => 'place_first'));
        
        $repository = $client->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(\Krombox\MainBundle\Entity\Category::class);
        
        $category1 = $repository->findOneBy(array('name' => 'cafe'));                
        $crawler = $client->request('GET', '/places/' . $category1->getSlug());
        
        //must be 1 because second place has pending status
        $this->assertEquals(
            1,
            $crawler->filter('.places-wrapper .place')->count()
        );
        //place_first name must match with place in the list
        $this->assertEquals(
            $place_first->getname(),
            $crawler->filter('.places-wrapper .place h2 a')->text()
        );                
    }
}
