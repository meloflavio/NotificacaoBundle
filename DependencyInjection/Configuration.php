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
        $rootNode = $treeBuilder->root('melo_flavio_notificacao');

        $rootNode
            ->children()
            ->booleanNode('persist')->defaultTrue()->end()
            ->arrayNode('topic')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('default')->defaultValue('/global')->end()
                    ->scalarNode('parameter_id')->defaultValue('createdBy')->end()
                ->end()
            ->end()
            ->arrayNode('class')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('notificacao')->defaultValue('App\Entity\Notificacao')->end()
                    ->scalarNode('user')->defaultValue('App\UFT\UserBundle\Entity\Usuario')->end()
                ->end()
            ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}