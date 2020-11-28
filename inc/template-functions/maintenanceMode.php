<?php
$output = "<div style=\"text-align: center;
    font-weight: bold;
    margin: 0px;
    font-size: 27px;
    position: fixed;
    width: 100%;
    background: #000;
    color: #fff;
    z-index: 1;\">Dette er en demoshop - ingen produkter kan købes</div><style>header.section-header {
    padding-top: 40px;
}</style>";
$smarty->assign("maintenanceMode", $output);
?>
