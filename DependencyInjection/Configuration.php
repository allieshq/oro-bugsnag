<?php

namespace Allies\Bundle\OroBugsnagBundle\DependencyInjection;
  
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('allies_oro_bugsnag');
                
        return $treeBuilder;
    }
}