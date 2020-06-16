<?php


namespace MeloFlavio\NotificacaoBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Build configuration for multiple file upload
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('meloflavio_notificacao');

        $rootNode
            ->children()
            ->arrayNode('user')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('class')->defaultValue('App\UFT\UserBundle\Entity\Usuario')->end()
                ->end()
            ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}