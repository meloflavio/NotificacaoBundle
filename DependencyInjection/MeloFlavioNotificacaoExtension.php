<?php


namespace MeloFlavio\NotificacaoBundle\DependencyInjection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;



class MeloFlavioNotificacaoExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('meloflavio_notificacao.class.user', $config['class']['user']);
        $container->setParameter('meloflavio_notificacao.persist', $config['persist']);
        $container->setParameter('meloflavio_notificacao.topic.default', $config['topic']['default']);
        $container->setParameter('meloflavio_notificacao.topic.parameter_id', $config['topic']['parameter_id']);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        if($config['persist']){
            $loader->load('listener.yml');
        }

    }



    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        // get all Bundles
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DoctrineBundle'])) {
            // Get configuration of our own bundle
            $configs = $container->getExtensionConfig($this->getAlias());
            $config = $this->processConfiguration(new Configuration(), $configs);

            // Prepare for insertion
            $forInsertion = [
                'orm' => [
                    'resolve_target_entities' => [
                        'Sonata\UserBundle\Model\UserInterface' => $config['class']['user']
                    ]
                ]
            ];
            foreach ($container->getExtensions() as $name => $extension) {
                switch ($name) {
                    case 'doctrine':
                        $container->prependExtensionConfig($name, $forInsertion);
                        break;
                }
            }
        }
    }
}