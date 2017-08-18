<?php

namespace ElasticExportTwengaCOM\Generator;

use ElasticExport\Helper\ElasticExportCoreHelper;
use ElasticExport\Helper\ElasticExportPriceHelper;
use ElasticExport\Helper\ElasticExportStockHelper;
use Plenty\Modules\DataExchange\Contracts\CSVPluginGenerator;
use Plenty\Modules\Helper\Models\KeyValue;
use Plenty\Modules\Helper\Services\ArrayHelper;
use Plenty\Modules\Item\DataLayer\Models\Record;
use Plenty\Modules\Item\DataLayer\Models\RecordList;
use Plenty\Modules\Item\Search\Contracts\VariationElasticSearchScrollRepositoryContract;
use Plenty\Plugin\Log\Loggable;


/**
 * Class TwengaCOM
 * @package ElasticExportTwengaCOM\Generator
 */
class TwengaCOM extends CSVPluginGenerator
{
	use Loggable;

	const DELIMITER = ";";

    /**
     * @var ElasticExportCoreHelper
     */
    private $elasticExportHelper;

	/**
	 * @var ElasticExportStockHelper
	 */
    private $elasticExportStockHelper;

	/**
	 * @var ElasticExportPriceHelper
	 */
    private $elasticExportPriceHelper;

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
     * Generates and populates the data into the CSV file.
     *
     * @param VariationElasticSearchScrollRepositoryContract $elasticSearch
     * @param array $formatSettings
     * @param array $filter
     */
    protected function generatePluginContent($elasticSearch, array  $formatSettings = [], array $filter = [])
    {
		$this->elasticExportPriceHelper = pluginApp(ElasticExportPriceHelper::class);
		$this->elasticExportStockHelper = pluginApp(ElasticExportStockHelper::class);
		$this->elasticExportHelper = pluginApp(ElasticExportCoreHelper::class);

		$settings = $this->arrayHelper->buildMapFromObjectList($formatSettings, 'key', 'value');

		$this->setDelimiter(self::DELIMITER);

		$this->setHeader();

		if($elasticSearch instanceof VariationElasticSearchScrollRepositoryContract)
		{
			$limitReached = false;
			$lines = 0;
			do
			{
				if($limitReached === true)
				{
					break;
				}

				$resultList = $elasticSearch->execute();

				if(!is_null($resultList['error']))
				{
					$this->getLogger(__METHOD__)->error('ElasticExportTwengaCOM::logs.esError', [
						'Error message ' => $resultList['error'],
					]);
				}

				foreach($resultList['documents'] as $variation)
				{
					if($lines == $filter['limit'])
					{
						$limitReached = true;
						break;
					}

					if(is_array($resultList['documents']) && count($resultList['documents']) > 0)
					{
						if($this->elasticExportStockHelper->isFilteredByStock($variation, $filter) === true)
						{
							continue;
						}

						try
						{
							$this->buildRow($variation, $settings);
							$lines = $lines +1;
						}
						catch(\Throwable $throwable)
						{
							$this->getLogger(__METHOD__)->error('ElasticExportTwengaCOM::logs.fillRowError', [
								'Error message ' => $throwable->getMessage(),
								'Error line'    => $throwable->getLine(),
								'VariationId'   => $variation['id']
							]);
						}
					}
				}
			}while ($elasticSearch->hasNext());
		}
    }

    private function setHeader()
	{
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
	}

    private function buildRow($variation, $settings)
	{
		// Get the price and retail price
		$priceList = $this->elasticExportPriceHelper->getPriceList($variation, $settings, 2, '.');
		$price = $priceList['price'];
		$rrp = '';

		if((float)$price > 0 && (float)$priceList['recommendedRetailPrice'] > (float)$price)
		{
			$rrp = $priceList['recommendedRetailPrice'];
		}

		$stock = $this->elasticExportStockHelper->getStock($variation);

		$image = $image = $this->elasticExportHelper->getImageListInOrder($variation, $settings, 1, $this->elasticExportHelper::VARIATION_IMAGES);

		if(count($image) > 0)
		{
			$image = $image[0];
		}
		else
		{
			$image = '';
		}

		$rrp = $rrp > $price ? $rrp : '';

		$shippingCost = $this->elasticExportHelper->getShippingCost($variation['data']['item']['id'], $settings);

		if(!is_null($shippingCost))
		{
			$shippingCost = number_format((float)$shippingCost, 2, '.', '');
		}
		else
		{
			$shippingCost = '';
		}

		$data = [
			'product_url'       => $this->elasticExportHelper->getMutatedUrl($variation, $settings, true, false),
			'designation'       => $this->elasticExportHelper->getMutatedName($variation, $settings),
			'price'             => $price,
			'category'          => $this->elasticExportHelper->getCategory((int)$variation['data']['defaultCategories'][0]['id'], $settings->get('lang'), $settings->get('plentyId')),
			'image_url'         => $image,
			'description'       => $this->elasticExportHelper->getMutatedDescription($variation, $settings, 256),
			'regular_price'     => $rrp,
			'shipping_cost'     => $shippingCost,
			'merchant_id'       => $variation['data']['variation']['number'],
			'manufacturer_id'   => $variation['data']['variation']['model'],
			'in_stock'          => $stock > 0 ? 'Y' : 'N',
			'stock_detail'      => $stock,
			'condition'         => $this->getVariationCondition((int)$variation['data']['item']['condition']['id']),
			'upc_ean'           => $this->elasticExportHelper->getBarcodeByType($variation, $settings->get('barcode')),
			'isbn'              => $this->elasticExportHelper->getBarcodeByType($variation, ElasticExportCoreHelper::BARCODE_ISBN),
			'brand'             => $this->elasticExportHelper->getExternalManufacturerName((int)$variation['data']['item']['manufacturer']['id'])
		];

		$this->addCSVContent(array_values($data));
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
}
