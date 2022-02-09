<?

if (! defined ( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true)
	die ();

if (! CModule::IncludeModule ( 'kit.mailing' ))
{
	return false;
}

if (! CModule::IncludeModule ( 'iblock' ))
{
	return false;
}

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams['CACHE_GROUPS'] = trim($arParams['CACHE_GROUPS']);
if ('N' != $arParams['CACHE_GROUPS'])
	$arParams['CACHE_GROUPS'] = 'Y';


if($this->startResultCache(false, array(($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))))
{
	
	if ($arParams["TYPE"] == 'SECTION_ID')
	{
		$arParams["PARAM_1"] = $arParams["TYPE"];
        $arParams["PARAM_2"] = (!empty($arParams["PARAM_2:SECTION_ID"]) ? $arParams["PARAM_2:SECTION_ID"] : $arParams["PARAM_2_SECTION_ID"]);
        $arParams["PARAM_3"] = (!empty($arParams["PARAM_3:SECTION_ID"]) ? $arParams["PARAM_3:SECTION_ID"] : $arParams["PARAM_3_SECTION_ID"]);
		
		if ($arParams["PARAM_2"])
		{
			$dbSectionInfo = CIBlockSection::GetByID ( $arParams["PARAM_2"] );
			$SectionInfo = $dbSectionInfo->Fetch ();
		}
		
		if ($SectionInfo['NAME'])
		{
			$arParams["CATEGORY_NAME"] = GetMessage ( 'CATEGORY_NAME_SECTION_INFO', array (
					'#SECTION_NAME#' => $SectionInfo['NAME'] 
			) );
		}
		else
		{
			$arParams["CATEGORY_NAME"] = GetMessage ( 'CATEGORY_NAME_SECTION_ID', array (
					'#ID#' => $arParams["PARAM_2"] 
			) );
		}
		
		$arParams["CATEGORY_DESCRIPTION"] = GetMessage ( 'CATEGORY_DESCRIPTION' );
	}
	
	if ($arParams["TYPE"] == 'PROPERTY')
	{
		$arParams["PARAM_1"] = $arParams["TYPE"];
        $arParams["PARAM_2"] = (!empty($arParams["PARAM_2:SECTION_ID"]) ? $arParams["PARAM_2:SECTION_ID"] : $arParams["PARAM_2_SECTION_ID"]);
        $arParams["PARAM_3"] = (!empty($arParams["PARAM_3:SECTION_ID"]) ? $arParams["PARAM_3:SECTION_ID"] : $arParams["PARAM_3_SECTION_ID"]);
		
		if ($arParams["PARAM_2"])
		{
			$properties = CIBlockProperty::GetList ( Array (
					"sort" => "asc",
					"name" => "asc" 
			), Array (
					"CODE" => $arParams["PARAM_2"] 
			) );
			$prop_fields = $properties->GetNext ();
			$PROP_NAME = $prop_fields['NAME'];
		}
		
		if ($prop_fields['PROPERTY_TYPE'] == 'E' && $arParams["PARAM_3"])
		{
			$db_enum_list = CIBlockElement::GetList ( Array (), Array (
					"ID" => $arParams["PARAM_3"] 
			), false, false, array (
					'ID',
					'NAME' 
			) );
			if ($ar_enum_list = $db_enum_list->GetNext ())
			{
				$VALUE_NAME = $ar_enum_list['NAME'];
			}
		}
		elseif ($prop_fields['PROPERTY_TYPE'] == 'L' && $arParams["PARAM_3"])
		{
			$db_enum_list = CIBlockProperty::GetPropertyEnum ( $arParams["PARAM_2"], Array (), Array (
					"ID" => $prop_fields['ID'] 
			) );
			if ($ar_enum_list = $db_enum_list->GetNext ())
			{
				$VALUE_NAME = $ar_enum_list['NAME'];
			}
		}
		
		if ($PROP_NAME && $VALUE_NAME)
		{
			$arParams["CATEGORY_NAME"] = GetMessage ( 'CATEGORY_NAME_PROPERTY_INFO', array (
					'#PROP_NAME#' => $PROP_NAME,
					'#VALUE_NAME#' => $VALUE_NAME 
			) );
		}
		else
		{
			$arParams["CATEGORY_NAME"] = GetMessage ( 'CATEGORY_NAME_PROPERTY', array (
					'#CODE#' => $arParams["PARAM_2"],
					'#VALUE#' => $arParams["PARAM_3"] 
			) );
		}
		
		$arParams["CATEGORY_DESCRIPTION"] = GetMessage ( 'CATEGORY_DESCRIPTION' );
	}
	
	if (CModule::IncludeModule ( 'subscribe' ))
	{
		$arRubric = array ();
		$rub = CRubric::GetList ( array (
				"ID" => "ASC" 
		), array (
				"ACTIVE" => "Y" 
		) );
		while ( $arrRub = $rub->Fetch () )
		{
			$arRubric[$arrRub['ID']] = '[' . $arrRub['ID'] . '] ' . $arrRub['NAME'];
		}
	}
	
	global $USER;
	
	if ($USER->IsAuthorized () && empty ( $_COOKIE['MAILING_USER_MAIL_GET'] ))
	{
		$EMAIL_USER = $USER->GetEmail ();
		$answer = CKitMailingSubscribers::checkSubscribers ( array (
				'EMAIL_TO' => $EMAIL_USER 
		) );
		// ���� ��� ��������
		if ($answer)
		{
			SetCookie ( "MAILING_USER_MAIL_GET", 'Y', time () + 300 * 24 * 60 * 60, '/', $_SERVER['SERVER_NAME'] );
		}
		// ���� ��������� ���������� ��������������
		elseif ($arParams["DISPLAY_NO_AUTH"] == 'Y')
		{
			SetCookie ( "MAILING_USER_MAIL_GET", 'Y', time () + 300 * 24 * 60 * 60, '/', $_SERVER['SERVER_NAME'] );
		}
	}
	
	if ($USER->IsAdmin () && $arParams['DISPLAY_IF_ADMIN'] == 'Y')
	{
		SetCookie ( "MAILING_USER_MAIL_GET", '', time () - 3600, '/', $_SERVER['SERVER_NAME'] );
	}
	
	// ���� ������������ �����������, �������� ���� �� �� � �����������, ������� ������
	// START
	if ($USER->IsAuthorized ())
	{
		
		$USER_ID = $USER->GetID ();
		
		$dbSubscrib = CKitMailingSubscribers::GetList ( Array (), Array (
				'USER_ID' => $USER_ID 
		), false, array () );
		if ($resSubscrib = $dbSubscrib->Fetch ())
		{
			$arResult['SUBSCRIBER_INFO'] = $resSubscrib;
			$arResult['SUBSCRIBER_INFO']['CATEGORIES_ID'] = CKitMailingSubscribers::GetCategoriesBySubscribers ( $resSubscrib['ID'] );
			$arParams['SUBSCRIBER_ID'] = $resSubscrib['ID'];
		}
		
		if (empty ( $arResult['SUBSCRIBER_INFO']['EMAIL_TO'] ))
		{
			$arResult['SUBSCRIBER_INFO']['EMAIL_TO'] = $USER->GetEmail ();
		}
	}
	// END
	
	// ������� ��� ���������
	$dbCategories = CKitMailingCategories::GetList ( Array (
			'ID' => "ASC" 
	), Array (
			'ACTIVE' => 'Y' 
	), false, array () );
	while ( $resCategories = $dbCategories->Fetch () )
	{
		
		if (empty ( $arResult['SUBSCRIBER_INFO']['CATEGORIES_ID'] ))
		{
			$arResult['SUBSCRIBER_INFO']['CATEGORIES_ID'] = array ();
		}
		
		if (in_array ( $resCategories['ID'], $arResult['SUBSCRIBER_INFO']['CATEGORIES_ID'] ))
		{
			$resCategories['CHECKED'] = 'Y';
		}
		
		$arResult['CATEGORIES'][$resCategories['ID']] = $resCategories;
	}
	
	$arParams["COLOR_PANEL"] = Trim ( $arParams["COLOR_PANEL"] );
	$arParams["COLOR_BORDER_PANEL"] = Trim ( $arParams["COLOR_BORDER_PANEL"] );
	$arParams["COLOR_PANEL_OPEN"] = Trim ( $arParams["COLOR_PANEL_OPEN"] );
	$arParams["COLOR_MODAL_BORDER"] = Trim ( $arParams["COLOR_MODAL_BORDER"] );
	$arParams["COLOR_MODAL_BG"] = Trim ( $arParams["COLOR_MODAL_BG"] );
	if (strlen ( $arParams["COLOR_PANEL"] ) <= 0)
		$arParams["COLOR_PANEL"] = "#2B5779";
	
	if (strlen ( $arParams["COLOR_BORDER_PANEL"] ) <= 0)
		$arParams["COLOR_BORDER_PANEL"] = "#FFFFFF";
	
	if (strlen ( $arParams["COLOR_PANEL_OPEN"] ) <= 0)
		$arParams["COLOR_PANEL"] = "#FFFFFF";
	
	if (strlen ( $arParams["COLOR_MODAL_BORDER"] ) <= 0)
		$arParams["COLOR_BORDER_PANEL"] = "#2B5779";
	
	if (strlen ( $arParams["COLOR_MODAL_BG"] ) <= 0)
		$arParams["COLOR_MODAL_BG"] = "#FFFFFF";
	
	if (strlen ( $arParams["MODAL_TIME_SECOND_OPEN"] ) <= 0)
		$arParams["MODAL_TIME_SECOND_OPEN"] = "180";
	
	if (strlen ( $arParams["MODAL_TIME_DAY_NOW"] ) <= 0)
		$arParams["MODAL_TIME_DAY_NOW"] = "5";
	
	if ($_SERVER['SCRIPT_URI'])
	{
		$arParams["STATIC_PAGE_SIGNED"] = $_SERVER['SCRIPT_URI'];
	}
	elseif ($_SERVER['REQUEST_URI'])
	{
		$arParams["STATIC_PAGE_SIGNED"] = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	}
	$arParams["STATIC_PAGE_CAME"] = $_COOKIE['MAILING_USER_CAME'];
	
	$arResult['STR_PARAMS'] = http_build_query ( $arParams );
	
	if ($arParams["JQUERY"] == 'Y')
	{
		CJSCore::Init ( array (
				"jquery" 
		) );
	}
	
	$APPLICATION->AddHeadScript ( $this->GetPath () . '/script.js' );
	CUtil::InitJSCore ( array (
			'ajax' 
	) );
	
	$this->IncludeComponentTemplate ();
}
