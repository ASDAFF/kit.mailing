<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
    "GROUPS" => array(
        "ADD_SETTINGS" => array(
           "NAME" => GetMessage("GROUP_ADD_SETTINGS")
        ),
    ),
    "PARAMETERS" => array(
        "PARAM_TITLE" => array(
            "PARENT" => "ADD_SETTINGS",
            "NAME" => GetMessage("PARAM_TITLE"),
            "TYPE" => "STRING",
            "DEFAULT" => GetMessage("PARAM_TITLE_DEFAULT")
        ),
        "PARAM_PERCENTS" => array(
            "PARENT" => "ADD_SETTINGS",
            "NAME" => GetMessage("PARAM_PERCENTS"),
            "TYPE" => "STRING",
            "DEFAULT" => "5"
        ),
        "PARAM_DAYS" => array(
            "PARENT" => "ADD_SETTINGS",
            "NAME" => GetMessage("PARAM_DAYS"),
            "TYPE" => "STRING",
            "DEFAULT" => "2"
        ),
    ),
);
