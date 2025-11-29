<?php
$cartCount = "0";
if (isset($_SESSION['cart']))
{

    if(count($_SESSION['cart']) > 0) {
            $cartCount = count($_SESSION['cart']); //how many products
   }
}

$output = array(
      "numberOfItems" => $cartCount,
);

$smarty->assign("headerCart", $output, true); // No cache active
?>
