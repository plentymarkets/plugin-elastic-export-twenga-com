
# ElasticExportTwengaCOM plugin user guide

<div class="container-toc"></div>

## 1 Registering with Twenga.com

Twenga.com is an international shopping platform that offers comprehensive price comparisons.

## 2 Setting up the data format TwengaCOM-Plugin in plentymarkets

The plugin Elastic Export is required to use this format.

Refer to the [Exporting data formats for price search engines](https://knowledge.plentymarkets.com/en/basics/data-exchange/exporting-data#30) page of the manual for further details about the individual format settings.

The following table lists details for settings, format settings and recommended item filters for the format **TwengaCOM-Plugin**.
<table>
    <tr>
        <th>
            Settings
        </th>
        <th>
            Explanation
        </th>
    </tr>
    <tr>
        <td class="th" colspan="2">
            Settings
        </td>
    </tr>
    <tr>
        <td>
            Format
        </td>
        <td>
            Choose <b>TwengaCOM-Plugin</b>.
        </td>        
    </tr>
    <tr>
        <td>
            Provisioning
        </td>
        <td>
            Choose <b>URL</b>.
        </td>        
    </tr>
    <tr>
        <td>
            File name
        </td>
        <td>
            The file name must have the ending <b>.csv</b> or <b>.txt</b> for Twenga.com to be able to import the file successfully.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Item filter
        </td>
    </tr>
    <tr>
        <td>
            Active
        </td>
        <td>
            Choose <b>active</b>.
        </td>        
    </tr>
    <tr>
        <td>
            Markets
        </td>
        <td>
            Choose one or multiple order referrers. The chosen order referrer has to be active at the variation for the item to be exported.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Format settings
        </td>
    </tr>
    <tr>
        <td>
            Order referrer
        </td>
        <td>
            Choose the order referrer that should be assigned during the order import.
        </td>        
    </tr>
    <tr>
    	<td>
    		Stockbuffer
    	</td>
    	<td>
    		The stock buffer for variations with the limitation to the netto stock.
    	</td>        
    </tr>
    <tr>
    	<td>
    		Stock for Variations without stock limitation
    	</td>
    	<td>
    		The stock for variations without stock limitation.
    	</td>        
    </tr>
    <tr>
    	<td>
    		The stock for variations with not stock administration
    	</td>
    	<td>
    		The stock for variations without stock administration.
    	</td>        
    </tr>
    <tr>
        <td>
            Preview text
        </td>
        <td>
            This option is not relevant for this format.
        </td>        
    </tr>
    <tr>
        <td>
            Offer price
        </td>
        <td>
            This option is not relevant for this format.
        </td>        
    </tr>
    <tr>
        <td>
            VAT note
        </td>
        <td>
            This option is not relevant for this format.
        </td>        
    </tr>
</table>

## 3 Overview of available columns

<table>
    <tr>
        <th>
            Column name
        </th>
        <th>
            Explanation
        </th>
    </tr>
    <tr>
		<td>
			product_url
		</td>
		<td>
			<b>Content:</b> The <b>URL path</b> of the item depending on the chosen <b>client</b> in the format. settings.
		</td>        
	</tr>
	<tr>
		<td>
			designation
		</td>
		<td>
			<b>Content:</b> According to the format setting <b>item name</b>.
		</td>        
	</tr>
	<tr>
		<td>
			price
		</td>
		<td>
			<b>Content:</b> The <b>sales price</b> of the variation.
		</td>        
	</tr>
	<tr>
		<td>
			category
		</td>
		<td>
			<b>Content:</b> The name of the <b>category</b>.
		</td>        
	</tr>
	<tr>
		<td>
			image_url
		</td>
		<td>
			<b>Content:</b> The image url. Variation images are prioritizied over item images.
		</td>        
	</tr>
	<tr>
		<td>
			description
		</td>
		<td>
			<b>Content:</b> According to the format setting <b>Description</b>.
		</td>        
	</tr>
	<tr>
		<td>
			regular_price
		</td>
		<td>
			<b>Content:</b> If the <b>RRP</b> is activated in the format setting and is higher than the <b>sales price</b>, the <b>RRP</b> will be exported.
		</td>        
	</tr>
	<tr>
		<td>
			shipping_cost
		</td>
		<td>
			<b>Content:</b> According to the format setting <b>shipping costs</b>.
		</td>        
	</tr>
    <tr>
        <td>
            merchant_id
        </td>
        <td>
            <b>Content:</b> The <b>variation number</b> of the variation.
        </td>        
    </tr>
    <tr>
		<td>
			manufacturer_id
		</td>
		<td>
			<b>Content:</b> The <b>Model</b> of the variation.
		</td>        
	</tr>
	<tr>
		<td>
			in_stock
		</td>
		<td>
			<b>Content:</b> Indicates if the variation has <b>stock</b>, depending on <b>stock_detail</b>.
		</td>        
	</tr>
	<tr>
		<td>
			stock_detail
		</td>
		<td>
			<b>Content:</b> The <b>net stock</b> of the variation. If a variation is not limited to its net stock, the stock will be set to b>999</b>.
		</td>        
	</tr>
	<tr>
		<td>
			condition
		</td>
		<td>
			<b>Content:</b> Indicates the <b>condition</b> of the variation.
		</td>        
	</tr>
	<tr>
		<td>
			upc_ean
		</td>
		<td>
			<b>Content:</b> According to the format setting <b>Barcode</b>.
		</td>        
	</tr>
	<tr>
		<td>
			isbn
		</td>
		<td>
			<b>Content:</b> The <b>ISBN</b> of the variation.
		</td>        
	</tr>
	<tr>
		<td>
			brand
		</td>
		<td>
			<b>Content:</b> The <b>name of the manufacturer</b> of the item. The <b>external name</b> from the menu <b>Settings » Items » Manufacturer</b> will be preferred if existing.
		</td>        
	</tr>
</table>

## License

This project is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE.- find further information in the [LICENSE.md](https://github.com/plentymarkets/plugin-elastic-export-twenga-com/blob/master/LICENSE.md).
