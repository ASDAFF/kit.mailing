<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $serverName;

if(!empty($arResult))
{
	$cntCol = 0;
	foreach($arResult as $i => $arItem)
	{
		if($arItem['DEPTH_LEVEL'] == 1)
		{
			if($i != 0)
			{
				?>
                            </td>
                        </tr>
                    </table>
				</td>
				<?
            }
            if($cntCol > 1) break;
			?>
            <td width="36%" style="vertical-align: top;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0; padding:0">
                    <tr>
                        <td width="100%">
                            <!--[if gte mso 9]>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                                <tr>
                                    <td width="100%">
                                        <![endif]-->
                                        <span style="font: 15px 'Proxima Nova', Arial, sans-serif;color: #000000;font-weight: bold;text-transform:uppercase;text-decoration: none;line-height:51px;display: block; -webkit-text-size-adjust:none;"><font color="#000000" size="3" face="Proxima Nova, Arial, sans-serif"><?=$arItem['TEXT']?></font></span>
                                        <!--[if gte mso 9]>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
			<?
			++$cntCol;
		}
		if($arItem['DEPTH_LEVEL'] == 2)
		{
			?>
            <!--[if gte mso 9]>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0; padding:0">
                <tr>
                    <td width="100%">
                        <![endif]-->
                        <a href="<?=$serverName.$arItem["LINK"]?>" style="font: 13px 'Proxima Nova', Arial, sans-serif;color: #616161;text-decoration: none;line-height:24px;display: block; -webkit-text-size-adjust:none;" target="_blank"><font color="#616161" size="2" face="Proxima Nova, Arial, sans-serif"><?=$arItem['TEXT']?></font></a>
                        <!--[if gte mso 9]>
                    </td>
                </tr>
            </table>
            <![endif]-->
			<?
        }
        if($arItem == end($arResult))
        {
            ?>
                        </td>
                    </tr>
                </table>
            </td>
            <?
        }
	}
}
?>