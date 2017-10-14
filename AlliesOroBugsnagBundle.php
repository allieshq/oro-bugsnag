<?php

namespace Allies\Bundle\OroBugsnagBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Allies\Bundle\OroBugsnagBundle\DependencyInjection\Compiler\PushHandlerCompilerPass;

class AlliesOroBugsnagBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function  build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $container->addCompilerPass(new PushHandlerCompilerPass());
    }
}