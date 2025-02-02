<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ctrl/ctrl.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/log.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/product/product.php');

use GuzzleHttp\Psr7\UploadedFile;
use Monolog\Logger;

/** Détail d'un produit. */
class updateProduct extends Ctrl
{
    /** @Override */
    function log(): Logger
    {
        return Log::getLog(__CLASS__);
    }

    /** @Override */
    function getPageTitle()
    {
        return null;
    }

    /** @Override */
    function do()
{
    // Obtient le détail d'un produit et l'expose à la vue
    $iduser = $_SESSION['user']['id'];
    $newlabel = isset($this->inputs['label']) ? $this->inputs['label'] : null;
    $newdescription = isset($this->inputs['description']) ? $this->inputs['description'] : null;
    $newprix = isset($this->inputs['prix']) ? $this->inputs['prix'] : null;
    $newpicture = isset($this->inputs['picture']) ? $this->inputs['picture'] : null;
    $newref = isset($this->inputs['ref']) ? $this->inputs['ref'] : null;

    $updateproduct = LibProduct::update($iduser, $newlabel, $newdescription, $newprix, $newpicture, $newref);
    $listProduct = LibProduct::readAll();
    $this->addViewArg('updateproduct', $updateproduct);  
   
    $this->addViewArg('listProduct', $listProduct);  
}

    /** @Override */
    function getView()
    {
        return '/view/product/list.php';    }
}

$ctrl = new UpdateProduct();
$ctrl->execute();
?>