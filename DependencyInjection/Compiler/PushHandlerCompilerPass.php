<?php

namespace Allies\Bundle\OroBugsnagBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class PushHandlerCompilerPass implements CompilerPassInterface
{
    const LOGGER_SERVICE_KEY = 'monolog.logger';
    const BUGSNAG_HANDLER_SERVICE_KEY = 'allies_orobugsnag.handler';
    
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(self::LOGGER_SERVICE_KEY) || !$container->has(self::BUGSNAG_HANDLER_SERVICE_KEY)) {
            return;
        }
        
        $loggerService = $container->getDefinition(self::LOGGER_SERVICE_KEY);
        $loggerService->addMethodCall('pushHandler', [new Reference(self::BUGSNAG_HANDLER_SERVICE_KEY)]);
    }
}
        
