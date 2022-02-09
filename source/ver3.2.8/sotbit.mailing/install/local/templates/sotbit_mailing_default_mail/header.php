<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <style type="text/css">

    </style>
<?
/*
This is commented to avoid Project Quality Control warning
$APPLICATION->ShowHead();
$APPLICATION->ShowTitle();
$APPLICATION->ShowPanel();
*/
?>
</head>
<body>
<? if (\Bitrix\Main\Loader::includeModule('mail')) : ?>
<?=\Bitrix\Mail\Message::getQuoteStartMarker(true); ?>
<? endif; ?>
<?

global $serverName;

$protocol = isset($_SERVER['HTTPS'])? 'https': 'http';
$protocol = \Bitrix\Main\Config\Option::get("main", "mail_link_protocol", $protocol, $arParams["SITE_ID"]);
$serverName = $protocol."://".$arParams["SERVER_NAME"];

?>
<table width="700" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #e8eced; vertical-align: top;font-size: 12px;">                          
    <tr style="height: 95px; background-color: #fafbfb ; ">
        <td border="0" width="50px" style="vertical-align: top;">
                   
        </td>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="vertical-align: top;">
                <tbody>
                    <tr>
                        <td width="50%" align="left"  valign="top">
                            <a href="<?=$serverName?>?MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#" ><img src="<?=$serverName?>/local/templates/sotbit_mailing_default_mail/img/shablon_logo.jpg" /></a>
                        </td>
                        <td width="50%" align="right"  valign="middle" >
                            <b style="color: #89cbf5;">info@#SITE_URL#</b><br/> 
                            <b style="color: #89cbf5;">#SITE_URL#</b>    
                        </td>
                    </tr>
                </tbody>
            </table>  
        </td>
        <td border="0" width="50px" style="vertical-align: top; ">
        </td>
    </tr>
    <tr>
        <td></td>
        <td style="vertical-align: top; padding-top: 20px; padding-bottom: 20px; font-size: 14px;   font-family:Trebuchet MS; color: #5a85a6">