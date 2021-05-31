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
        $link = "http://localhost/admin/item?item_set_id=" . $id . "&sort_by=title&sort_order=asc";

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
        return "http://localhost/admin/item?resource_class_label[]=" . $resource_class_label;
    }

//
// http://localhost/admin/item?fulltext_search=&property%5B0%5D%5Bjoiner%5D=and&property%5B0%5D%5Bproperty%5D=&property%5B0%5D%5Btype%5D=eq&property%5B0%5D%5Btext%5D=&resource_class_id%5B%5D=126&resource_template_id%5B%5D=&item_set_id%5B%5D=&site_id=&owner_id=&numeric%5Bts%5D%5Bgt%5D%5Bpid%5D=&numeric%5Bts%5D%5Bgt%5D%5Bval%5D=&year=&month=&day=&hour=&minute=&second=&offset=&numeric%5Bts%5D%5Blt%5D%5Bpid%5D=&numeric%5Bts%5D%5Blt%5D%5Bval%5D=&year=&month=&day=&hour=&minute=&second=&offset=&numeric%5Bdur%5D%5Bgt%5D%5Bpid%5D=&numeric%5Bdur%5D%5Bgt%5D%5Bval%5D=&years=&months=&days=&hours=&minutes=&seconds=&numeric%5Bdur%5D%5Blt%5D%5Bpid%5D=&numeric%5Bdur%5D%5Blt%5D%5Bval%5D=&years=&months=&days=&hours=&minutes=&seconds=&numeric%5Bivl%5D%5Bpid%5D=&numeric%5Bivl%5D%5Bval%5D=&year=&month=&day=&hour=&minute=&second=&offset=&numeric%5Bint%5D%5Bgt%5D%5Bpid%5D=&numeric%5Bint%5D%5Bgt%5D%5Bval%5D=&integer=&numeric%5Bint%5D%5Blt%5D%5Bpid%5D=&numeric%5Bint%5D%5Blt%5D%5Bval%5D=&integer=&submit=Search

    public function indexAction() {

        $thesauri = [];
        $thesauriSets = $this->getItemSets("Collection");
        foreach ($thesauriSets as $ts) {
            array_push($thesauri, [
                "label" => $ts->displayTitle(),
                "count" => $this->countItemSetItems($ts->id()),
                "link" => "http://localhost/admin/item?item_set_id=" . $ts->id() . "&sort_by=title&sort_order=asc"
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