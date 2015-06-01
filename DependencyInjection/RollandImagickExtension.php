<?php

namespace Rolland\ImagickBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RollandImagickExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('twig.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $filters = isset($config['filters']) && $config['filters'] ? $config['filters'] : array();
        $cacheDir = substr($config['cache_dir'], -1) === '/' ?
            substr($config['cache_dir'], 0, strlen($config['cache_dir']) - 1) :
            $config['cache_dir']
        ;
        $webDir = substr($config['web_dir'], -1) === '/' ?
            substr($config['web_dir'], 0, strlen($config['web_dir']) - 1) :
            $config['web_dir']
        ;

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        $container->getParameterBag()->set('rolland_imagick.cache_dir', $cacheDir);
        $container->getParameterBag()->set('rolland_imagick.web_dir', $webDir);
        $container->getParameterBag()->set('rolland_imagick.filters', $filters);
    }
}
