<?php


namespace MeloFlavio\NotificacaoBundle\DependencyInjection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;



class MeloFlavioNotificacaoExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('meloflavio_notificacao.user.class', $config['user']['class']);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

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
            $forInsertion = array(
                'orm' => array(
                    'resolve_target_entities' => array(
                        'FOS\UserBundle\Model\UserInterface' => $config['user']['class']
                    )
                )
            );
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