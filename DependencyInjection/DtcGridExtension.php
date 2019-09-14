<?php

namespace Dtc\GridBundle\DependencyInjection;

use Dtc\GridBundle\Grid\Renderer\DataTablesRenderer;
use Dtc\GridBundle\Grid\Renderer\JQGridRenderer;
use Dtc\GridBundle\Grid\Renderer\TableGridRenderer;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class DtcGridExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('grid.yml');

        $configuration = new Configuration();
        $processor = new Processor();

        $config = $processor->processConfiguration($configuration, $configs);
        $this->setReflection($config, $container);
        $this->setJqGrid($config, $container);
        $this->setTheme($config, $container);
        $this->setDatatables($config, $container);
        $this->setJquery($config, $container);
        $this->setPurl($config, $container);
        $container->setParameter('dtc_grid.page_div_style', isset($config['page_div_style']) ? $config['page_div_style'] : null);
        $container->setParameter('dtc_grid.table_options', isset($config['table']['options']) ? $config['table']['options'] : TableGridRenderer::$defaultOptions);
    }

    public function setReflection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('dtc_grid.reflection.allowed_entities', isset($config['reflection']['allowed_entities']) ? $config['reflection']['allowed_entities'] : null);
    }

    public function setPurl(array $config, ContainerBuilder $container)
    {
        $purl = isset($config['purl']) ? $config['purl'] : [];
        $container->setParameter('dtc_grid.purl', $purl);
    }

    public function setJquery(array $config, ContainerBuilder $container)
    {
        $jquery = isset($config['jquery']) ? $config['jquery'] : [];
        $container->setParameter('dtc_grid.jquery', $jquery);
    }

    public function setJqGrid(array $config, ContainerBuilder $container)
    {
        $css = isset($config['jq_grid']['css']) ? $config['jq_grid']['css'] : [];
        $js = isset($config['jq_grid']['js']) ? $config['jq_grid']['js'] : [];
        $options = isset($config['jq_grid']['options']) ? $config['jq_grid']['options'] : JQGridRenderer::$defaultOptions;

        $container->setParameter('dtc_grid.jq_grid.css', $css);
        $container->setParameter('dtc_grid.jq_grid.js', $js);
        $container->setParameter('dtc_grid.jq_grid.options', $options);
    }

    public function setDataTables(array $config, ContainerBuilder $container)
    {
        $class = isset($config['datatables']['class']) ? $config['datatables']['class'] : [];
        $css = isset($config['datatables']['css']) ? $config['datatables']['css'] : [];
        $js = isset($config['datatables']['js']) ? $config['datatables']['js'] : [];
        $options = isset($config['datatables']['options']) ? $config['datatables']['options'] : DataTablesRenderer::$defaultOptions;

        $container->setParameter('dtc_grid.datatables.class', $class);
        $container->setParameter('dtc_grid.datatables.css', $css);
        $container->setParameter('dtc_grid.datatables.js', $js);
        $container->setParameter('dtc_grid.datatables.options', $options);
    }

    public function setTheme(array $config, ContainerBuilder $container)
    {
        $css = isset($config['theme']['css']) ? $config['theme']['css'] : [];
        $js = isset($config['theme']['js']) ? $config['theme']['js'] : [];

        $container->setParameter('dtc_grid.theme.css', $css);
        $container->setParameter('dtc_grid.theme.js', $js);
    }

    public function getAlias()
    {
        return 'dtc_grid';
    }
}
