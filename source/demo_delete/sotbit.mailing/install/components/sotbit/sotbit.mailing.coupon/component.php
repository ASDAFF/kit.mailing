<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(!CModule::IncludeModule("sotbit.mailing"))
    return;

$protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
$arResult['SITE_URL'] = $protocol . "://" . $_SERVER['SERVER_NAME'];

$this->IncludeComponentTemplate();
?>