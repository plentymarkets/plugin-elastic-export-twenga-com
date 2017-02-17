<?php

namespace ElasticExportTwengaCOM\Generator;

use Plenty\Modules\Helper\Models\KeyValue;
use ElasticExportCore\Helper\ElasticExportCoreHelper;
use Plenty\Modules\DataExchange\Contracts\CSVGenerator;
use Plenty\Modules\Helper\Services\ArrayHelper;
use Plenty\Modules\Item\DataLayer\Models\Record;
use Plenty\Modules\Item\DataLayer\Models\RecordList;


/**
 * Class TwengaCOM
 * @package ElasticExportTwengaCOM\Generator
 */
class TwengaCOM extends CSVGenerator
{
    /**
     * @var ElasticExportCoreHelper
     */
    private $elasticExportCoreHelper;

    /**
     * @var ArrayHelper
     */
    private $arrayHelper;

    /**
     * @var array $idlVariations
     */
    private $idlVariations = [];


    /**
     * TwengaCOM constructor.
     *
     * @param ElasticExportCoreHelper $elasticExportCoreHelper
     * @param ArrayHelper $arrayHelper
     */
    public function __construct(ElasticExportCoreHelper $elasticExportCoreHelper, ArrayHelper $arrayHelper)
    {
        $this->elasticExportCoreHelper = $elasticExportCoreHelper;
        $this->arrayHelper = $arrayHelper;
    }

    /**
     * Generates and populates the data into the CSV file.
     *
     * @param array $resultData
     * @param array $formatSettings
     */
    protected function generateContent($resultData, array  $formatSettings = [])
    {
        if(is_array($resultData) && count($resultData['documents']) > 0)
        {
            $settings = $this->arrayHelper->buildMapFromObjectList($formatSettings, 'key', 'value');

            $this->setDelimiter(";");

            $this->addCSVContent([

                // Compulsory fields
                'product_url',
                'designation',
                'price',
                'category',
                'image_url',
                'description',
                'regular_price',
                'shipping_cost',

                // Optional fields
                'merchant_id',
                'manufacturer_id',
                'in_stock',
                'stock_detail',
                'condition',
                'upc_ean',
                'isbn',
                'brand',
            ]);

            // Generates a RecordList form the ItemDataLayer for the given variations
            $idlResultList = $this->generateIdlList($resultData, $settings);

            // Creates an array with the variationId as key to surpass the sorting problem
            if(isset($idlResultList) && $idlResultList instanceof RecordList)
            {
                $this->createIdlArray($idlResultList);
            }

            // Get though the returned elastic search data and create the rows
            foreach($resultData['documents'] as $variation)
            {
                // Get the price and retail price
                $rrp = $this->elasticExportCoreHelper->getRecommendedRetailPrice($this->idlVariations[$variation['id']]['variationRecommendedRetailPrice.price'], $settings);
                $price = $this->idlVariations[$variation['id']]['variationRetailPrice.price'];

                $rrp = $rrp > $price ? $rrp : '';

                $shippingCost = $this->elasticExportCoreHelper->getShippingCost($variation['data']['item']['id'], $settings);

                if(!is_null($shippingCost))
                {
                    $shippingCost = number_format((float)$shippingCost, 2, '.', '');
                }
                else
                {
                    $shippingCost = '';
                }

                $data = [
                    'product_url'       => $this->elasticExportCoreHelper->getUrl($variation, $settings, true, false),
                    'designation'       => $this->elasticExportCoreHelper->getName($variation, $settings),
                    'price'             => number_format((float)$price, 2, '.', ''),
                    'category'          => $this->elasticExportCoreHelper->getCategory((int)$variation['data']['defaultCategories'][0]['id'], $settings->get('lang'), $settings->get('plentyId')),
                    'image_url'         => $this->elasticExportCoreHelper->getMainImage($variation, $settings),
                    'description'       => $this->elasticExportCoreHelper->getDescription($variation, $settings, 256),
                    'regular_price'     => number_format((float)$rrp, 2, '.', ''),
                    'shipping_cost'     => $shippingCost,
                    'merchant_id'       => $this->idlVariations[$variation['id']]['variationBase.customNumber'],
                    'manufacturer_id'   => $variation['data']['variation']['model'],
                    'in_stock'          => $this->idlVariations[$variation['id']]['variationStock.stockNet'] > 0 ? 'Y' : 'N',
                    'stock_detail'      => $this->idlVariations[$variation['id']]['variationStock.stockNet'],
                    'condition'         => $this->getVariationCondition((int)$variation['data']['item']['condition']['id']),
                    'upc_ean'           => $this->elasticExportCoreHelper->getBarcodeByType($variation, $settings->get('barcode')),
                    'isbn'              => $this->elasticExportCoreHelper->getBarcodeByType($variation, ElasticExportCoreHelper::BARCODE_ISBN),
                    'brand'             => $this->elasticExportCoreHelper->getExternalManufacturerName((int)$variation['data']['item']['manufacturer']['id'])
                ];

                $this->addCSVContent(array_values($data));
            }
        }
    }

    /**
     * Get the condition of a variation from existing states.
     *
     * @param $key
     * @return mixed
     */
    private function getVariationCondition($key)
    {
        $variationCondition =    [
            0 => '0', 	// plenty condition: NEU
            1 => '1', 	// plenty condition: GEBRAUCHT
            2 => '0', 	// plenty condition: NEU & OVP
            3 => '0', 	// plenty condition: NEU mit Etikett
            4 => '1', 	// plenty condition: B-WARE
        ];

        if(array_key_exists($key, $variationCondition))
        {
            return $variationCondition[$key];
        }

        return $variationCondition[4];
    }

    /**
     * Creates a list of Records from the given variations.
     *
     * @param array     $resultData
     * @param KeyValue  $settings
     * @return RecordList|string
     */
    private function generateIdlList($resultData, $settings)
    {
        // Create a List of all VariationIds
        $variationIdList = array();
        foreach($resultData['documents'] as $variation)
        {
            $variationIdList[] = $variation['id'];
        }

        // Get the missing fields in ES from IDL(ItemDataLayer)
        if(is_array($variationIdList) && count($variationIdList) > 0)
        {
            /**
             * @var \ElasticExportTwengaCOM\IDL_ResultList\TwengaCOM $idlResultList
             */
            $idlResultList = pluginApp(\ElasticExportTwengaCOM\IDL_ResultList\TwengaCOM::class);

            // Return the list of results for the given variation ids
            return $idlResultList->getResultList($variationIdList, $settings);
        }

        return '';
    }

    /**
     * Creates an array with the rest of data needed from the IDL(ItemDataLayer).
     *
     * @param RecordList $idlResultList
     */
    private function createIdlArray($idlResultList)
    {
        if($idlResultList instanceof RecordList)
        {
            foreach($idlResultList as $idlVariation)
            {
                if($idlVariation instanceof Record)
                {
                    $this->idlVariations[$idlVariation->variationBase->id] = [
                        'itemBase.id' => $idlVariation->itemBase->id,
                        'variationBase.id' => $idlVariation->variationBase->id,
                        'variationBase.customNumber' => $idlVariation->variationBase->customNumber,
                        'variationStock.stockNet' => $idlVariation->variationStock->stockNet,
                        'variationRetailPrice.price' => $idlVariation->variationRetailPrice->price,
                        'variationRecommendedRetailPrice.price' => $idlVariation->variationRecommendedRetailPrice->price,
                    ];
                }
            }
        }
    }
}
