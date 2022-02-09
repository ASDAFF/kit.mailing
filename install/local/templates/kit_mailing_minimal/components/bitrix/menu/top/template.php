<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $serverName;

?>

<td align="center">
    <!--[if gte mso 9]>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
        <tr>
            <td width="100%">
            <![endif]-->
                <a href="<?=$serverName.'/catalog/'?>" target="_blank" style="font: 13px 'Proxima Nova', Arial, sans-serif;color: #000000;font-weight: bold;text-transform:uppercase;text-decoration: none;line-height:30px;display: block; -webkit-text-size-adjust:none;"><font color="#000000" size="2" face="Proxima Nova, Arial, sans-serif">Каталог товаров</font></a>
            <!--[if gte mso 9]>
            </td>
        </tr>
    </table>
    <![endif]-->
</td>

<?
if(!empty($arResult))
{
    foreach($arResult as $arItem)
    {
        ?>
		<td align="center">
            <!--[if gte mso 9]>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                <tr>
                    <td width="100%">
                    <![endif]-->
                    	<a href="<?=$serverName.$arItem["LINK"]?>" target="_blank" style="font: 13px 'Proxima Nova', Arial, sans-serif;color: #000000;font-weight: bold;text-transform:uppercase;text-decoration: none;line-height:30px;display: block; -webkit-text-size-adjust:none;"><font color="#000000" size="2" face="Proxima Nova, Arial, sans-serif"><?=$arItem["TEXT"]?></font></a>
                    <!--[if gte mso 9]>
                    </td>
                </tr>
            </table>
            <![endif]-->
        </td>
        <?
	}
}
?>