<?php

namespace Dtc\GridBundle\Grid\Renderer;

use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class RendererFactory
{
    protected Environment $twig;
    protected RouterInterface $router;
    protected TranslatorInterface $translator;
    protected $themeCss;
    protected $themeJs;
    protected $pageDivStyle;
    protected $jqGridJs;
    protected $jqGridCss;
    protected $jqGridLocalJs;
    protected $jqGridLocalCss;
    protected $jqGridOptions;
    protected $tableOptions;
    protected $dataTablesCss;
    protected $dataTablesJs;
    protected $dataTablesLocalCss;
    protected $dataTablesLocalJs;
    protected $dataTablesClass;
    protected $dataTablesOptions;
    protected $jQuery;
    protected $purl;

    public function __construct(
        RouterInterface $router,
        TranslatorInterface $translator,
        ContainerInterface $container
    ) {
        $this->router = $router;
        $this->translator = $translator;
        $this->themeCss = $container->getParameter('dtc_grid.theme.css');
        $this->themeJs = $container->getParameter('dtc_grid.theme.js');
        $this->pageDivStyle = $container->getParameter('dtc_grid.page_div_style');
        $this->jQuery = $container->getParameter('dtc_grid.jquery');
        $this->purl = $container->getParameter('dtc_grid.purl');
        $this->dataTablesCss = $container->getParameter('dtc_grid.datatables.css');
        $this->dataTablesJs = $container->getParameter('dtc_grid.datatables.js');
        $this->dataTablesLocalCss = $container->getParameter('dtc_grid.datatables.local.css');
        $this->dataTablesLocalJs = $container->getParameter('dtc_grid.datatables.local.js');
        $this->dataTablesClass = $container->getParameter('dtc_grid.datatables.class');
        $this->dataTablesOptions = $container->getParameter('dtc_grid.datatables.options');
        $this->jqGridCss = $container->getParameter('dtc_grid.jq_grid.css');
        $this->jqGridJs = $container->getParameter('dtc_grid.jq_grid.js');
        $this->jqGridLocalCss = $container->getParameter('dtc_grid.jq_grid.local.css');
        $this->jqGridLocalJs = $container->getParameter('dtc_grid.jq_grid.local.js');
        $this->jqGridOptions = $container->getParameter('dtc_grid.jq_grid.options');
        $this->tableOptions = $container->getParameter('dtc_grid.table_options');
    }

    public function setTwigEnvironment(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getTwigEnvironment()
    {
        return $this->twig;
    }

    /**
     * Creates a new renderer of type $type, throws an exception if it's not known how to create a renderer of type $type.
     *
     * @param $type
     *
     * @return AbstractRenderer
     *
     * @throws \Exception
     */
    public function create($type)
    {
        $twig = $this->getTwigEnvironment();
        if (!$twig) {
            throw new \Exception('Twig Engine not found.  Please see https://github.com/mmucklo/DtcGridBundle/README.md for instructions.');
        }
        switch ($type) {
            case 'datatables':
                $renderer = new DataTablesRenderer($this->twig, $this->router, $this->translator, $this->dataTablesOptions);
                break;
            case 'jq_grid':
                $renderer = new JQGridRenderer($this->twig, $this->router, $this->translator, $this->jqGridOptions);
                break;
            case 'table':
                $renderer = new TableGridRenderer($this->twig, $this->router, $this->translator, $this->tableOptions);
                break;
            default:
                throw new \Exception("No renderer for type '$type''");
        }

        if (method_exists($renderer, 'setThemeCss')) {
            $renderer->setThemeCss($this->themeCss);
        }

        if (method_exists($renderer, 'setThemeJs')) {
            $renderer->setThemeJs($this->themeJs);
        }

        if (method_exists($renderer, 'setJQuery')) {
            $renderer->setJQuery($this->jQuery);
        }

        if (method_exists($renderer, 'setPurl')) {
            $renderer->setPurl($this->purl);
        }

        if (method_exists($renderer, 'setPageDivStyle')) {
            $renderer->setPageDivStyle($this->pageDivStyle);
        }

        if (method_exists($renderer, 'setJqGridCss')) {
            $renderer->setJqGridCss($this->jqGridCss);
        }

        if (method_exists($renderer, 'setJqGridJs')) {
            $renderer->setJqGridJs($this->jqGridJs);
        }

        if (method_exists($renderer, 'setJqGridLocalCss')) {
            $renderer->setJqGridLocalCss($this->jqGridLocalCss);
        }

        if (method_exists($renderer, 'setJqGridLocalJs')) {
            $renderer->setJqGridLocalJs($this->jqGridLocalJs);
        }

        if (method_exists($renderer, 'setDataTablesCss')) {
            $renderer->setDataTablesCss($this->dataTablesCss);
        }

        if (method_exists($renderer, 'setDataTablesJs')) {
            $renderer->setDataTablesJs($this->dataTablesJs);
        }

        if (method_exists($renderer, 'setDataTablesLocalCss')) {
            $renderer->setDataTablesLocalCss($this->dataTablesLocalCss);
        }

        if (method_exists($renderer, 'setDataTablesLocalJs')) {
            $renderer->setDataTablesLocalJs($this->dataTablesLocalJs);
        }

        if (method_exists($renderer, 'setDatatablesClass') && $this->dataTablesClass) {
            $renderer->setDatatablesClass($this->dataTablesClass);
        }

        return $renderer;
    }
}
