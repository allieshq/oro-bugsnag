<?php

namespace Allies\Bundle\OroBugsnagBundle\DependencyInjection;
  
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Monolog\Logger;

use Oro\Bundle\ConfigBundle\DependencyInjection\SettingsBuilder;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('allies_oro_bugsnag');
        
        SettingsBuilder::append(
            $rootNode,
            [
                'reporting_level' => ['value' => [
                    Logger::EMERGENCY,
                    Logger::ALERT,
                    Logger::CRITICAL,
                    Logger::ERROR,
                    Logger::WARNING,
                ]],
            ]
        );
        
        return $treeBuilder;
    }
}