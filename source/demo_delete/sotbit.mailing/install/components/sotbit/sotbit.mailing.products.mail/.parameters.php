<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$arComponentParameters = array();
//группы товаров
//START
$arComponentParameters["GROUPS"]["TYPE"] = array(
    "NAME" => GetMessage("GROUPS_TYPE"),
);
$arComponentParameters["GROUPS"]["PRODUCT"] = array(
    "NAME" => GetMessage("GROUPS_PRODUCT"),
);
$arComponentParameters["GROUPS"]["ORDER"] = array(
    "NAME" => GetMessage("GROUPS_ORDER"),
);
$arComponentParameters["GROUPS"]["DOPOLN"] = array(
    "NAME" => GetMessage("GROUPS_DOPOLN"),
);
//END


//свойства товаров
//START
$arComponentParameters["PARAMETERS"]["TYPE_WORK"] = Array(
    "NAME" => GetMessage("TYPE_WORK"),
    "PARENT"  => "TYPE",
    "TYPE" => "LIST",
    "MULTIPLE" => "N",
    "VALUES" => Array(
        "SHOW" => GetMessage("TYPE_WORK_SHOW"), 
        "BUYER" => GetMessage("TYPE_WORK_BUYER"),             
    ),
);
$arComponentParameters["PARAMETERS"]["LIST_ITEM_ID_OUR"] = Array(
    "NAME" => GetMessage("LIST_ITEM_ID_OUR"),
    "PARENT"  => "PRODUCT",
    "TYPE" => "LIST",
    "MULTIPLE" => "N",
    "ADDITIONAL_VALUES" => "Y",
    "VALUES" => Array(
        "{#FORGET_BASKET_ID#}" => GetMessage("LIST_ITEM_ID_OUR_FORGET_BASKET_ID"),
        "{#RECOMMEND_PRODUCT_ID#}" => GetMessage("LIST_ITEM_ID_OUR_RECOMMEND_PRODUCT_ID"),
        "{#VIEWED_PRODUCT_ID#}" => GetMessage("LIST_ITEM_ID_OUR_VIEWED_PRODUCT_ID"),                
    ),
);
$arComponentParameters["PARAMETERS"]["LIST_ITEM_ID"] = Array(
    "NAME" => GetMessage("LIST_ITEM_ID"),
    "PARENT"  => "PRODUCT",
    "TYPE" => "STRING",
    "MULTIPLE" => "Y",
    "DEFAULT" => "",
);
$arComponentParameters["PARAMETERS"]["ORDER_ID"] = Array(
    "NAME" => GetMessage("ORDER_ID"),
    "PARENT"  => "ORDER",
    "TYPE" => "LIST",
    "MULTIPLE" => "N",
    "ADDITIONAL_VALUES" => "Y",
    "VALUES" => Array(
        "{#ORDER_ID#}" => "{#ORDER_ID#}",
        "{#ID#}" => "{#ID#}",
    ),
);
$arComponentParameters["PARAMETERS"]["COUNT_PRODUCT"] = Array(    
    "NAME" => GetMessage("COUNT_PRODUCT"),
    "PARENT"  => "DOPOLN",
    "TYPE" => "STRING",
    "DEFAULT" => "10",                       
);
$arComponentParameters["PARAMETERS"]["BLOCK_TITLE"] = Array(    
    "NAME" => GetMessage("BLOCK_TITLE"),
    "PARENT"  => "DOPOLN",
    "TYPE" => "STRING",
    "DEFAULT" => "",                       
);
//END
