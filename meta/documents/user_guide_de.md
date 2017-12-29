
# User Guide für das ElasticExportTwengaCOM Plugin

<div class="container-toc"></div>

## 1 Bei Twenga.com registrieren

Twenga.com ist eine internationale Shopping-Plattform und bietet einen umfassenden Preisvergleich.

## 2 Das Format TwengaCOM-Plugin in plentymarkets einrichten

Um dieses Format nutzen zu können, benötigen Sie das Plugin Elastic Export.

Auf der Handbuchseite [Daten exportieren](https://www.plentymarkets.eu/handbuch/datenaustausch/daten-exportieren/#4) werden die einzelnen Formateinstellungen beschrieben.

In der folgenden Tabelle finden Sie Hinweise zu den Einstellungen, Formateinstellungen und empfohlenen Artikelfiltern für das Format **TwengaCOM-Plugin**.
<table>
    <tr>
        <th>
            Einstellung
        </th>
        <th>
            Erläuterung
        </th>
    </tr>
    <tr>
        <td class="th" colspan="2">
            Einstellungen
        </td>
    </tr>
    <tr>
        <td>
            Format
        </td>
        <td>
            <b>TwengaCOM-Plugin</b> wählen.
        </td>        
    </tr>
    <tr>
        <td>
            Bereitstellung
        </td>
        <td>
            <b>URL</b> wählen.
        </td>        
    </tr>
    <tr>
        <td>
            Dateiname
        </td>
        <td>
            Der Dateiname muss auf <b>.csv</b> oder <b>.txt</b> enden, damit Twenga.com die Datei erfolgreich importieren kann.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Artikelfilter
        </td>
    </tr>
    <tr>
        <td>
            Aktiv
        </td>
        <td>
            <b>Aktiv</b> wählen.
        </td>        
    </tr>
    <tr>
        <td>
            Märkte
        </td>
        <td>
            Eine oder mehrere Auftragsherkünfte wählen. Die gewählten Auftragsherkünfte müssen an der Variante aktiviert sein, damit der Artikel exportiert wird.
        </td>        
    </tr>
    <tr>
        <td class="th" colspan="2">
            Formateinstellungen
        </td>
    </tr>
    <tr>
        <td>
            Auftragsherkunft
        </td>
        <td>
            Die Auftragsherkunft wählen, die beim Auftragsimport zugeordnet werden soll.
        </td>        
    </tr>
    <tr>
    	<td>
    		Bestandspuffer
    	</td>
    	<td>
    		Der Bestandspuffer für Varianten mit der Beschränkung auf den Netto Warenbestand.
    	</td>        
    </tr>
    <tr>
    	<td>
    		Bestand für Varianten ohne Bestandsbeschränkung
    	</td>
    	<td>
    		Der Bestand für Varianten ohne Bestandsbeschränkung.
    	</td>        
    </tr>
    <tr>
    	<td>
    		Bestand für Varianten ohne Bestandsführung
    	</td>
    	<td>
    		Der Bestand für Varianten ohne Bestandsführung.
    	</td>        
    </tr>
    <tr>
        <td>
            Vorschautext
        </td>
        <td>
            Diese Option ist für dieses Format nicht relevant.
        </td>        
    </tr>
    <tr>
        <td>
            Angebotspreis
        </td>
        <td>
            Diese Option ist für dieses Format nicht relevant.
        </td>        
    </tr>
    <tr>
        <td>
            MwSt.-Hinweis
        </td>
        <td>
            Diese Option ist für dieses Format nicht relevant.
        </td>        
    </tr>
</table>


## 3 Übersicht der verfügbaren Spalten

<table>
    <tr>
        <th>
            Spaltenbezeichnung
        </th>
        <th>
            Erläuterung
        </th>
    </tr>
    <tr>
		<td>
			product_url
		</td>
		<td>
			<b>Inhalt:</b> Der <b>URL-Pfad</b> des Artikels abhängig vom gewählten <b>Mandanten</b> in den Formateinstellungen.
		</td>        
	</tr>
	<tr>
		<td>
			designation
		</td>
		<td>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Artikelname</b>.
		</td>        
	</tr>
	<tr>
		<td>
			price
		</td>
		<td>
			<b>Ausgabe:</b> Hier steht der <b>Verkaufspreis</b>.
		</td>        
	</tr>
	<tr>
		<td>
			category
		</td>
		<td>
			<b>Inhalt:</b> Der Name der Kategorie.
		</td>        
	</tr>
	<tr>
		<td>
			image_url
		</td>
		<td>
			<b>Inhalt:</b> URL des Bildes. Variantenbiler werden vor Artikelbildern priorisiert.
		</td>        
	</tr>
	<tr>
		<td>
			description
		</td>
		<td>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Beschreibung</b>.
		</td>        
	</tr>
	<tr>
		<td>
			regular_price
		</td>
		<td>
			<b>Ausgabe:</b> Der <b>Verkaufspreis</b> der Variante. Wenn der <b>UVP</b> in den Formateinstellungen aktiviert wurde und höher ist als der Verkaufspreis, wird dieser hier eingetragen.
		</td>        
	</tr>
	<tr>
		<td>
			shipping_cost
		</td>
		<td>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Versandkosten</b>.
		</td>        
	</tr>
    <tr>
        <td>
            merchant_id
        </td>
        <td>
            <b>Inhalt:</b> Die <b>Variantennummer</b> der Variante.
        </td>        
    </tr>
    <tr>
		<td>
			manufacturer_id
		</td>
		<td>
			<b>Inhalt:</b> Das <b>Modell</b> der Variante.
		</td>        
	</tr>
	<tr>
		<td>
			in_stock
		</td>
		<td>
			<b>Inhalt:</b> Gibt an, ob die Variante <b>Bestand</b> abhängig von <b>stock_detail</b> hat.
		</td>        
	</tr>
	<tr>
		<td>
			stock_detail
		</td>
		<td>
			<b>Ausgabe:</b> Der <b>Nettowarenbestand der Variante</b>. Bei Artikeln, die nicht auf den Nettowarenbestand beschränkt sind, wird <b>999</b> übertragen.
		</td>        
	</tr>
	<tr>
		<td>
			condition
		</td>
		<td>
			<b>Inhalt:</b> Gibt den <b>Zustand</b> der Variante an.
		</td>        
	</tr>
	<tr>
		<td>
			upc_ean
		</td>
		<td>
			<b>Inhalt:</b> Entsprechend der Formateinstellung <b>Barcode</b>.
		</td>        
	</tr>
	<tr>
		<td>
			isbn
		</td>
		<td>
			<b>Inhalt:</b> Die <b>ISBN</b> der Variante.
		</td>        
	</tr>
	<tr>
		<td>
			brand
		</td>
		<td>
			<b>Inhalt:</b> Der <b>Name des Herstellers</b> des Artikels. Der <b>Externe Name</b> unter <b>Einstellungen » Artikel » Hersteller</b> wird bevorzugt, wenn vorhanden.
		</td>        
	</tr>
</table>

## 4 Lizenz

Das gesamte Projekt unterliegt der GNU AFFERO GENERAL PUBLIC LICENSE – weitere Informationen finden Sie in der [LICENSE.md](https://github.com/plentymarkets/plugin-elastic-export-twenga-com/blob/master/LICENSE.md).
