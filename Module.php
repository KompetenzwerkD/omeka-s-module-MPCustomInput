<?php declare(strict_types=1);
namespace MPCustomInput;

use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Omeka\Module\AbstractModule;

/**
 * MPCustomInput Plugin
 */
class Module extends AbstractModule
{
    public function attachListeners(SharedEventManagerInterface $sharedEventManager): void
    {
        $sharedEventManager->attach(
            'Omeka\Controller\Admin\Item',
            'view.show.after',
            [$this, 'addForms']
            );
    }


    public function addForms($event)
    {
        $view = $event->getTarget();
        $view->headLink()->appendStylesheet($view->assetUrl('mp.css', 'MPCustomInput'));
        $view->headScript()->appendFile($view->assetUrl('add-subitem.js', 'MPCustomInput'), 'text/javascript', ['defer' => 'defer']);
        $view->headScript()->appendFile($view->assetUrl('delete-subitem.js', 'MPCustomInput'), 'text/javascript', ['defer' => 'defer']);

        $item = $event->getTarget()->vars()->resource;
        $classLabel = $item->displayResourceClassLabel();
        if ($classLabel === "Funderfassung") {

            $config = [
                "sectionTitle" => "Herstellungsprozesse",
                "resourceClass" => "Herstellungsprozess",
                "resourceTemplate" => "Herstellungsprozess",
                "connectingProperty" => "mpo:partOfFunderfassung",
                "labelProperty" => "dcterms:title",
                "codeProperty" => "mpo:hatCode",
                "codeTemplate" => "<parent>__H_<label>"
            ];

            $this->showSubitemList($view, $item, $config);

            /*$config = [
                "sectionTitle" => "Materialanalysen",
                "resourceClass" => "Objektanalyse",
                "resourceTemplate" => "Materialanalyse",
                "connectingProperty" => "mpo:partOfFunderfassung",
                "labelProperty" => "dcterms:title",
                "codeProperty" => "mpo:hatCode",
                "codeTemplate" => "<parent>_MA_<label>"
            ];

	    $this->showSubitemList($view, $item, $config);*/
        }
        elseif ($classLabel === "Herstellungsprozess") {

            $config = [
                "sectionTitle" => "Herstellungsdetails",
                "resourceClass" => "Herstellungsdetail",
                "resourceTemplate" => "Herstellungsdetail",
                "connectingProperty" => "mpo:partOfProductionProcess",
                "labelProperty" => "dcterms:title",
                "codeProperty" => "mpo:hatCode",
                "codeTemplate" => "<parent>__D_<label>"
            ];
            $this->showSubitemList($view, $item, $config);
        }
        elseif ($classLabel === "Objekt") {
            $config = [
                "sectionTitle" => "Funderfassung",
                "resourceClass" => "Funderfassung",
                "resourceTemplate" => "Funderfassung",
                "connectingProperty" => "mpo:hatObjekt",
                "labelProperty" => "dcterms:title",
                "codeProperty" => "mpo:hatCode",
                "codeTemplate" => "<parent>_F<count>"
            ];
	    $this->showSubitemList($view, $item, $config);

	    $config = [
		"sectionTitle" => "Objektanalyse",
		"resourceClass" => "Objektanalyse",
		"resourceTemplate" => "Objektanalyse",
		"connectingProperty" => "mpo:hatObjekt",
		"labelProperty" => "dcterms:title",
		"codeProperty" => "mpo:hatCode",
		"codeTemplate" => "<parent>__A_<label>"
	    ];
	    $this->showSubitemList($view, $item, $config);
            
	}
	elseif ($classLabel == "Archivalie") {
		$config = [
	    	"sectionTitle" => "Schriftdokumente",
	    	"resourceClass" => "Schriftdokument",
	    	"resourceTemplate" => "Schriftdokument",
	    	"connectingProperty" => "mpo:hatArchivalie",
	    	"labelProperty" => "dcterms:title",
	    	"codeProperty" => "mpo:hatCode",
	    	"codeTemplate" => "<parent>  [SD] <label>"
    		];
		$this->showSubitemList($view, $item, $config);

		$config = [
			"sectionTitle" => "Bilddokumente",
			"resourceClass" => "Bilddokument",
			"resourceTemplate" => "Bilddokument",
			"connectingProperty" => "mpo:hatArchivalie",
			"labelProperty" => "dcterms:title",
			"codeProperty" => "mpo:hatCode",
			"codeTemplate" => "<parent> [BD] <label>"
		];
		$this->showSubitemList($view, $item, $config);
	}
    }


    private function getPropertyId($view, $propertyName) {
        $prop = $view->api()->search('properties', [
            "term" => $propertyName,
        ])->getContent();
        return $prop[0]->id();
    }

    private function getResourceClassId($view, $propertyName) {
        $class = $view->api()->search('resource_classes', [
            "local_name" => $propertyName,
        ])->getContent();
        return $class[0]->id();
    }

    private function getTemplateId($view, $templateName) {
        $templates = $view->api()->search('resource_templates', [
            "term" => $templateName,
        ])->getContent();
        foreach ($templates as $tmp) {
            $title = $tmp->label();
            if ($title == $templateName)
                return $tmp->id();
        }
    } 

    public function showSubitemList($view, $item, $config) {

        $sectionTitle = $config["sectionTitle"];
        $resourceClass = $config["resourceClass"];
        $resourceTemplate = $config["resourceTemplate"];
        $connectingProperty = $config["connectingProperty"];
        $labelProperty = $config["labelProperty"];
        $codeProperty = $config["codeProperty"];
        $codeTemplate = $config["codeTemplate"];


        $connectingPropertyId = $this->getPropertyId($view, $connectingProperty);
        $resourceClassId = $this->getResourceClassId($view, $resourceClass);
        $resourceTemplateId = $this->getTemplateId($view, $resourceTemplate);
        $labelPropertyId = $this->getPropertyId($view, $labelProperty);
        $codePropertyId = $this->getPropertyId($view, $codeProperty);

        // print section title
        echo("<div class='add-container'><h3>$sectionTitle</h3>");

        // get and display subitems
        //$query = "&property[0][text]=" . $label;
        $label = $item->title();
        $items = $view->api()->search('items', [
            "search" => $label,
            "resource_class_label" => $resourceClass
        ]
        )->getContent();

        $subItemCount = 0;
        foreach($items as $i) {
            // check again if item belongs in list
            if (strpos((string) $i->value($connectingProperty), $item->url()) != false) {
                $title = $i->title();
                $urlEdit = $i->url('edit');
                $url = $i->url('');
                $code = $i->displayTitle();
                $element = $i->value("$labelProperty");

                echo "<li>";
                echo "<a class='o-icon-delete mp-delete' href='$url' aria-label='Delete' name='$element'></a>";
                echo "<a class='o-icon-edit mp-edit-link' href='$urlEdit' >";
                echo "<a href='$url'>$element</a>";
                echo "<span class='mp-code'>[$code]</span>";
                echo ("</li>");

                $subItemCount++;
            }
        }
        
        $codePropertyValue = str_replace("<parent>", $item->displayTitle(), $codeTemplate);
        $codePropertyValue = str_replace("<count>", $subItemCount + 1, $codePropertyValue);

        // add subitem form
        echo("<div><form>");
        echo("<input type='text' id='".$resourceClass."_label'/>");
        echo("<input type='hidden' id='".$resourceClass."_connectingProperty' value='$connectingProperty'/>");
        echo("<input type='hidden' id='".$resourceClass."_connectingPropertyId' value='$connectingPropertyId'/>");
        echo("<input type='hidden' id='".$resourceClass."_resourceClassId' value='$resourceClassId'/>");
        echo("<input type='hidden' id='".$resourceClass."_resourceTemplateId' value='$resourceTemplateId'/>");
        echo("<input type='hidden' id='".$resourceClass."_labelProperty' value='$labelProperty'/>");
        echo("<input type='hidden' id='".$resourceClass."_labelPropertyId' value='$labelPropertyId'/>");
        echo("<input type='hidden' id='".$resourceClass."_codeProperty' value='$codeProperty'/>");
        echo("<input type='hidden' id='".$resourceClass."_codePropertyId' value='$codePropertyId'/>");
        echo("<input type='hidden' id='".$resourceClass."_codePropertyValue' value='$codePropertyValue' />");
        echo("<a class='o-icon-add ' id='".$resourceClass."_add-button' value='Element hinzufügen' href='#'></a>");
        echo("</form></div></div>");

        echo("<script>");
        echo("$( document ).ready(function() { setupSubItemCreation('$resourceClass'); }); ");
        echo("</script>");
    }

}
