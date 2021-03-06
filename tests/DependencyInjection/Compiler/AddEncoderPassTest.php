<?php
/*
 * This file is part of the brainbits transcoder bundle package.
 *
 * (c) brainbits GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brainbits\TranscoderBundle\Tests\DependencyInjection\Compiler;

use Brainbits\TranscoderBundle\DependencyInjection\Compiler\AddEncoderPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add encoder pass test
 */
class AddEncoderPassTest extends TestCase
{
    public function testProcessWithoutDefinition()
    {
        $pass = new AddEncoderPass();

        $definition = new Definition(null, array(null));

        $container = $this->prophesize(ContainerBuilder::class);
        $container->hasDefinition('brainbits.transcoder.encoder.resolver')->willReturn(false);
        $container->findTaggedServiceIds('transcoder.encoder')->shouldNotBeCalled();
        $container->getDefinition('brainbits.transcoder.encoder.resolver')->shouldNotBeCalled();

        $pass->process($container->reveal());

        $this->assertNull($definition->getArgument(0)[0]);
    }

    public function testProcess()
    {
        $pass = new AddEncoderPass();

        $definition = new Definition(null, array(null));

        $container = $this->prophesize(ContainerBuilder::class);
        $container->hasDefinition('brainbits.transcoder.encoder.resolver')->willReturn(true);
        $container->findTaggedServiceIds('transcoder.encoder')->willReturn(array('test_id' => 'arg'));
        $container->getDefinition('brainbits.transcoder.encoder.resolver')->willReturn($definition);

        $pass->process($container->reveal());

        $this->assertInstanceOf(Reference::class, $definition->getArgument(0)[0]);
    }
}
