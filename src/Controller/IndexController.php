<?php
namespace MPCustomInput\Controller;

use Omeka\Form\ConfirmForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    private function getPropertyId($propertyName) {
        $prop = $this->api()->search('properties', [
            "term" => $propertyName,
        ])->getContent();
        return $prop[0]->id();
    }

    private function getResourceClassId($propertyName) {
        $class = $this->api()->search('resource_classes', [
            "local_name" => $propertyName,
        ])->getContent();
        return $class[0]->id();
    }

    private function getTemplateId($templateName) {
        $templates = $this->api()->search('resource_templates', [
            "term" => $templateName,
        ])->getContent();
        foreach ($templates as $tmp) {
            $title = $tmp->label();
            if ($title == $templateName)
                return $tmp->id();
        }
    } 

    protected function getItemSetId($label) {
        $response = $this->api()->searchOne('item_sets', ["search" => $label]);
        $id = $response->getContent()->id();
        return $id;        
    }

    protected function getItemSetLink($label) {
        $id = $this->getItemSetId($label);
        $link = "item?item_set_id=" . $id . "&sort_by=title&sort_order=asc";

        return $link;
    }

    protected function countItemSetItems($item_set_id) {
        $response = $this->api()->search('items', [ "item_set_id" => $item_set_id]); 
        return count($response->getContent());
    }

    protected function getItemCount($resource_class_label) {
        $response = $this->api()->search('items', [ "resource_class_label" => $resource_class_label]);   
        return count($response->getContent());
    }

    protected function getItemSets($resource_class_label) {
        $response = $this->api()->search('item_sets', ["resource_class_label" => $resource_class_label]);
        return $response->getContent();
    }

    protected function getSearchLinkByResourceClass($resource_class_label) {
        return "item?resource_class_label[]=" . $resource_class_label;
    }

    protected function buildBaseItem($resourceClass, $template, $itemSet, $codeProperty, $codePrefix) {
        $item = [];

        $itemSetId = $this->getItemSetId("Objekte");
        $size = $this->countItemSetItems($itemSetId);


        $item['o:item_set'] = [
            'o:id' => $itemSetId,
        ];
        $item['o:resource_class'] = [
            'o:id' => $this->getResourceClassId($resourceClass),
        ];
        $item['o:resource_template'] = [
            'o:id' => $this->getTemplateId($template),
        ];

        if ($codePrefix != "") {
            $item[$codeProperty][0] = [
                'type' => "literal",
                'property_id' => $this->getPropertyId($codeProperty),
                '@value' => $codePrefix . sprintf("%04d", $size+1) . " _ ",
                'is_private' => false,
            ];         
        }
        else {
            $item[$codeProperty][0] = [
                'type' => "literal",
                'property_id' => $this->getPropertyId($codeProperty),
                '@value' => "[Code]",
                'is_private' => false,
            ];         

        }
        return $item;
    }

    public function indexAction() {

        $thesauri = [];
        $thesauriSets = $this->getItemSets("Collection");
        foreach ($thesauriSets as $ts) {
            array_push($thesauri, [
                "label" => $ts->displayTitle(),
                "count" => $this->countItemSetItems($ts->id()),
                "link" => "item?item_set_id=" . $ts->id() . "&sort_by=title&sort_order=asc"
            ]);
        }

        $view = new ViewModel();
        $view->setVariable("objectCount", $this->getItemCount("Objekt"));
        $view->setVariable("objectsLink", $this->getItemSetLink("Objekte"));
        $view->setVariable("imagesCount", $this->getItemCount("Bilddokument"));
        $view->setVariable("imagesLink", $this->getSearchLinkByResourceClass("Bilddokument"));
        $view->setVariable("textsCount", $this->getItemCount("Schriftdokument"));
        $view->setVariable("textsLink", $this->getSearchLinkByResourceClass("Schriftdokument"));
        $view->setVariable("literatureCount", $this->getItemCount("Literatur"));
        $view->setVariable("literatureLink", $this->getItemSetLink("Literatur"));
        $view->setVariable("thesauri", $thesauri);
        return $view;
    }

    public function addObjectAction() {
        $item = $this->buildBaseItem(
            "Objekt",
            "Objekt",
            "Objekte",
            "mp:hatCode",
            "F"
        );
   
        $new = $this->api()->create('items', $item)->getContent();
        return  $this->redirect()->toURL('http://localhost/admin/item/'.$new->id().'/edit');
    }

    public function addImageDocumentAction() {
        $item = $this->buildBaseItem(
            "Bilddokument",
            "Bilddokument",
            "Bilddokumente",
            "mpo:hatCode",
            "B"
        );
   
        $new = $this->api()->create('items', $item)->getContent();
        return  $this->redirect()->toURL('http://localhost/admin/item/'.$new->id().'/edit');
    }

    public function addTextDocumentAction() {
        $item = $this->buildBaseItem(
            "Schriftdokument",
            "Schriftdokument",
            "Schriftdokumente",
            "mpo:hatCode",
            "S"
        );
   
        $new = $this->api()->create('items', $item)->getContent();
        return  $this->redirect()->toURL('http://localhost/admin/item/'.$new->id().'/edit');
    }    

    public function addBibliographicRecordAction() {
        $item = $this->buildBaseItem(
            "Literatur",
            "Literatur",
            "Literatur",
            "mpo:hatCode",
            ""
        );
   
        $new = $this->api()->create('items', $item)->getContent();
        return  $this->redirect()->toURL('http://localhost/admin/item/'.$new->id().'/edit');
    }    
}