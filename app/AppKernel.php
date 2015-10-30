<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //new AppBundle\AppBundle(),
            new Krombox\MainBundle\KromboxMainBundle(),
            //new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Krombox\UserBundle\KromboxUserBundle(),
            //SONATA
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),            
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new Oneup\FlysystemBundle\OneupFlysystemBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\AopBundle\JMSAopBundle(),
            new Fresh\DoctrineEnumBundle\FreshDoctrineEnumBundle(),
            new Ivory\GoogleMapBundle\IvoryGoogleMapBundle(),
            new Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle(),
            new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),            
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),            
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new SC\DatetimepickerBundle\SCDatetimepickerBundle(),
            new Krombox\CommonBundle\KromboxCommonBundle(),
            new Craue\FormFlowBundle\CraueFormFlowBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new Avocode\FormExtensionsBundle\AvocodeFormExtensionsBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\CommentBundle\FOSCommentBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new Krombox\CommentBundle\KromboxCommentBundle(),
            new Ornicar\AkismetBundle\OrnicarAkismetBundle(),
            new Krombox\PaymentBundle\KromboxPaymentBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle()            
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();            
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
