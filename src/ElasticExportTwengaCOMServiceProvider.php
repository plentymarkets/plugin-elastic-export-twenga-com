<?php

namespace ElasticExportTwengaCOM;

use Plenty\Modules\DataExchange\Services\ExportPresetContainer;
use Plenty\Plugin\DataExchangeServiceProvider;


/**
 * Class ElasticExportTwengaCOMServiceProvider
 * @package ElasticExportTwengaCOM
 */
class ElasticExportTwengaCOMServiceProvider extends DataExchangeServiceProvider
{
    /**
     * Abstract function for registering the service provider.
     */
    public function register()
    {

    }

    /**
     * Adds the export format to the export container.
     *
     * @param ExportPresetContainer $container
     */
    public function exports(ExportPresetContainer $container)
    {
        $container->add(
            'TwengaCOM-Plugin',
            'ElasticExportTwengaCOM\ResultField\TwengaCOM',
            'ElasticExportTwengaCOM\Generator\TwengaCOM',
            'ElasticExportTwengaCOM\Filter\TwengaCOM',
            true
        );
    }
}