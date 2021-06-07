<?php
namespace MPCustomInput\Controller;

use Omeka\Form\ConfirmForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    protected function getItemSetLink($label) {
        $response = $this->api()->searchOne('item_sets', ["search" => $label]);
        $id = $response->getContent()->id();
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
}