<?php
/**
 * This file is part of DLP Mail Bundle.
 *
 * (c) Eternal Apps
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EternalApps\Sculpin\Bundle\DlpMailBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Mail Extension.
 *
 * @author Krzysztof Janda <k.janda@eternalapps.pl>
 */
class DlpMailExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

        $loader->load('dlp_mail.xml');

        $container->setParameter('dlp_mail', $config);

        $container->findDefinition('dlp_mail')
            ->addArgument($config);
    }
}
