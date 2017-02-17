<?php

namespace ElasticExportTwengaCOM\ResultField;

use Plenty\Modules\Cloud\ElasticSearch\Lib\Source\Mutator\BuiltIn\LanguageMutator;
use Plenty\Modules\DataExchange\Contracts\ResultFields;
use Plenty\Modules\Helper\Services\ArrayHelper;
use Plenty\Modules\Item\Search\Mutators\ImageMutator;
use Plenty\Modules\Item\Search\Mutators\SkuMutator;


/**
 * Class TwengaCOM
 * @package ElasticExportTwengaCOM\ResultField
 */
class TwengaCOM extends ResultFields
{
    const TWENGA_COM = 133.00;

    /**
     * @var ArrayHelper
     */
    private $arrayHelper;


    /**
     * TwengaCOM constructor.
     *
     * @param ArrayHelper $arrayHelper
     */
    public function __construct(ArrayHelper $arrayHelper)
    {
        $this->arrayHelper = $arrayHelper;
    }

    /**
     * Creates the fields set to be retrieved from ElasticSearch.
     *
     * @param array $formatSettings
     * @return array
     */
    public function generateResultFields(array $formatSettings = []):array
    {
        $settings = $this->arrayHelper->buildMapFromObjectList($formatSettings, 'key', 'value');

        $reference = $settings->get('referrerId') ? $settings->get('referrerId') : self::TWENGA_COM;

        $this->setOrderByList(['variation.itemId', 'ASC']);

        $itemDescriptionFields = ['texts.urlPath'];

        $itemDescriptionFields[] = ($settings->get('nameId')) ? 'texts.name' . $settings->get('nameId') : 'texts.name1';

        if($settings->get('descriptionType') == 'itemShortDescription'
            || $settings->get('previewTextType') == 'itemShortDescription')
        {
            $itemDescriptionFields[] = 'texts.shortDescription';
        }

        if($settings->get('descriptionType') == 'itemDescription'
            || $settings->get('descriptionType') == 'itemDescriptionAndTechnicalData'
            || $settings->get('previewTextType') == 'itemDescription'
            || $settings->get('previewTextType') == 'itemDescriptionAndTechnicalData')
        {
            $itemDescriptionFields[] = 'texts.description';
        }

        if($settings->get('descriptionType') == 'technicalData'
            || $settings->get('descriptionType') == 'itemDescriptionAndTechnicalData'
            || $settings->get('previewTextType') == 'technicalData'
            || $settings->get('previewTextType') == 'itemDescriptionAndTechnicalData')
        {
            $itemDescriptionFields[] = 'texts.technicalData';
        }

        // Mutators
        /**
         * @var ImageMutator $imageMutator
         */
        $imageMutator = pluginApp(ImageMutator::class);
        $imageMutator->addMarket($reference);

        /**
         * @var LanguageMutator $languageMutator
         */
        $languageMutator = pluginApp(LanguageMutator::class, [[$settings->get('lang')]]);

        /**
         * @var SkuMutator $skuMutator
         */
        $skuMutator = pluginApp(SkuMutator::class);
        $skuMutator->setMarket($reference);

        // Fields
        $fields = [
            [
                //item
                'item.id',
                'item.manufacturer.id',
                'item.condition.id',

                //variation
                'id',
                'variation.model',

                //images
                'images.item.type',
                'images.item.path',
                'images.item.position',
                'images.variation.type',
                'images.variation.path',
                'images.variation.position',

                //sku
                'skus.sku',

                //defaultCategories
                'defaultCategories.id',

                //barcodes
                'barcodes.id',
                'barcodes.code',
                'barcodes.type',
                'barcodes.name',
            ],

            [
                $imageMutator,
                $languageMutator,
                $skuMutator,
            ],
        ];

        foreach($itemDescriptionFields as $itemDescriptionField)
        {
            //texts
            $fields[0][] = $itemDescriptionField;
        }

        return $fields;
    }
}
