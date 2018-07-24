<?php
/**
 * This file is part of Mail Bundle.
 *
 * (c) Eternal Apps
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EternalApps\Sculpin\Bundle\DlpMailBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 *
 * @author Krzysztof Janda <k.janda@eternalapps.pl>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder;
        $rootNode = $treeBuilder->root('dlp_mail');

        $rootNode
            ->children()
                ->scalarNode('subject')
                    ->defaultValue('New offer for domain %s')
                ->end()
                ->arrayNode('recipient')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->end()
                        ->scalarNode('email')->end()
                    ->end()
                ->end()
                ->arrayNode('recaptcha')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('secret')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
