# MP Costum Input Module for Omeka S

This Omeka S module is developed to aid the data input process for the Malaja Pereščepina project at the GWZO.

The module allows to define "subitems" for specific item classes. "Subitems" are basically linked resources (items) with a specific class/template and a spedified way of linking to the "parent item". It helps the item creation process by automatically generate items with the specified title and connecting property. 

## Usage

At the moment, subitems can be defined in the modules `Module.php`file.

```php
    if ($classLabel === "Funderfassung") {
        $config = [
            "sectionTitle" => "Materialanalysen",
            "resourceClass" => "MaterialAnalyse",
            "resourceTemplate" => "Materialanalyse",
            "connectingProperty" => "mp:hatFunderfassung",
            "labelProperty" => "dcterms:title",
            "codeProperty" => "mp:hatCode",
            "codeTemplate" => "<parent>_MA_<label>"
        ];
        $this->showSubitemList($view, $item, $config);
    }
```

In this example for the items of class "Funderfassung", in the item view, the linked items of the class "MaterialAnalyse" will be displayed as well as a form that creates "MaterialAnalyse" items and links them automatically to the respective "Funderfassung" item. The items title is defined by the "codeProperty" and "codeTemplate" config parameters.

### Config parameters

| parameter | description
| -- | -- | 
| sectionTitle | Header of the subitem listing / form area |
| resourceClass | Class of the subitem |
| resourceTemplate | Resource template of the subitem |
| connectionProperty | Property that connects the subitem to its "parent" |
| labelProperty | Property that is used as its laben in the subitem listing |
| codeProperty | Property that is used as the items display title in Omeka S |
| codeTemplate | Defines the automatically generated item title/code. `<parent>` will be replaced by the parent items title. `<label>` will be replaced by the subitems label. `<count>` will be replaced by the total numer of subitems for the items + 1 |


### API credentials

The module uses the Omeka S REST API for item creation and need to be provided with a valid API key. The API key can be created in the Admin user panel and needs to be provided in the `asset/add-subitem.js` file.


## Author

kompetenzwerkd@saw-leipzig.de

## Copyright

Sächsische Akademie der Wissenschaften zu Leipzig

## License

MIT
