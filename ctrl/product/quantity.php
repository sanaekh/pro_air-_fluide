<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/ctrl/ctrl.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/log.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/product/product.php');

use Monolog\Logger;

class AddCart extends Ctrl
{
    function log(): Logger
    {
        return Log::getLog(__CLASS__);
    }

    function getPageTitle()
    {
        return null;
    }

    function do()
    {
        // Vérifie si l'ID du produit est présent dans l'URL
        if (isset($_GET['add'])) {
            $idProduct = $_GET['add'];
            $product = LibProduct::get($idProduct);

            // Vérifie si une session panier existe
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Vérifie si la clé du produit existe dans le panier
            if (!isset($_SESSION['cart'][$idProduct])) {
                $_SESSION['cart'][$idProduct] = 0;
            }

            if ($product) {
                // Augmente la quantité du produit dans le panier
                $_SESSION['cart'][$idProduct]++;
            }
        }

        // Vérifie si l'ID du produit est présent dans l'URL pour la suppression
        if (isset($_GET['del'])) {
            $idProduct = $_GET['del'];
            $product = LibProduct::get($idProduct);
            
            // Vérifie si la clé du produit existe dans le panier
            if (isset($_SESSION['cart'][$idProduct])) {
                if ($_SESSION['cart'][$idProduct] > 1) {
                    $_SESSION['cart'][$idProduct]--;
                } else {
                    unset($_SESSION['cart'][$idProduct]);   
                }
            }
        }

        // Calculer la quantité totale dans le panier
        $quantity = $_GET['quantity'];

        if ($product && is_numeric($quantity) && $quantity >= 1) {
            // Ajoute la quantité saisie du produit dans le panier
            $_SESSION['cart'][$idProduct] = $quantity;
        }

        $this->addViewArg('addedProducts', $_SESSION['cart']);
        $this->addViewArg('quantity', $quantity);
        $this->addViewArg('product', $product);
    }

    function getView()
    {
        return '/view/product/product.php';
    }
}

$ctrl = new AddCart();
$ctrl->execute();

