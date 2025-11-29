<?php
/*
* wcioShop
* Version 1.0.0
* Author: Kim Vinberg <support@websitecare.io>
* Source: https://github.com/websitecareio/wcioShop
* License: https://github.com/websitecareio/wcioShop/blob/master/LICENSE
 */

$smartyTemplateFile = "settings.tpl";

// Load index for smarty functions and login valitation
include_once dirname(__FILE__) . '/index.php';

// Load functions for this file...
// IF we are saving data... This should really be a middleware...
if (isset($_POST["save"]) && $_POST["save"] == "1") {

        // Since this will be admin, we do a normal sanitize as text.
        // improve later with the field type. Textarea may allow more than a textarea. BUt for now all is sanitized the same.
        foreach ($_POST as $id => $value) {

                $sanitizedValue = $value; // No sanitize

                // Update in database
                $stmt = $dbh->prepare("UPDATE {$dbprefix}settings SET columnValue = :sanitizedValue WHERE id = :id");
                $result = $stmt->execute(array(
                        "sanitizedValue" => $sanitizedValue,
                        "id" => $id,
                ));

                $data = $stmt->execute();
        }
}

// Load template functions
include_once dirname(__FILE__) . '/inc/templateFunctions.php';


// Display the page and all its functions
$smarty->display($smartyTemplateFile);
