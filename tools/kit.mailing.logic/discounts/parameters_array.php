<?

if(\Bitrix\Main\Loader::includeModule('sale'))
{
    $arTemplateParameters["NEW_COUPON_ADD"] = array(
        "TABS" => 'TABS_DISCOUNT',
        "TABS_NAME" => GetMessage("TABS_DISCOUNT_NAME"),
        "PARENT" => "PARAM_NEW_COUPON_ADD",
        "PARENT_NAME" => GetMessage("GROUP_PARAM_NEW_COUPON_ADD"),
        "NAME" => GetMessage("NEW_COUPON_ADD_TITLE"),
        "TYPE" => "CHECKBOX",
        "SORT" => "10",
        "REFRESH" => "Y",
        "NOTES" => GetMessage("NEW_COUPON_ADD_NOTES")
    );

    if($arCurrentValues["NEW_COUPON_ADD"] == 'Y')
    {
        $arTemplateParameters["NEW_COUPON_DISCOUNT_ID"] = array(
            "TABS" => 'TABS_DISCOUNT',
            "TABS_NAME" => GetMessage("TABS_DISCOUNT_NAME"),
            "PARENT" => "PARAM_NEW_COUPON_ADD",
            "PARENT_NAME" => GetMessage("GROUP_PARAM_NEW_COUPON_ADD"),
            "NAME" => GetMessage("NEW_COUPON_DISCOUNT_ID_TITLE"),
            "TYPE" => "LIST",
            "VALUES" => $arBasketRulesList,
            "SORT" => "20",
        );
        $arTemplateParameters["NEW_COUPON_TYPE"] = array(
            "TABS" => 'TABS_DISCOUNT',
            "TABS_NAME" => GetMessage("TABS_DISCOUNT_NAME"),
            "PARENT" => "PARAM_NEW_COUPON_ADD",
            "PARENT_NAME" => GetMessage("GROUP_PARAM_NEW_COUPON_ADD"),
            "NAME" => GetMessage("NEW_COUPON_TYPE_TITLE"),
            "TYPE" => "LIST",
            "VALUES" => $arCouponTypes,
            "SORT" => "30",
        );
        $arTemplateParameters["NEW_COUPON_LIFETIME"] = array(
            "TABS" => 'TABS_DISCOUNT',
            "TABS_NAME" => GetMessage("TABS_DISCOUNT_NAME"),
            "PARENT" => "PARAM_NEW_COUPON_ADD",
            "PARENT_NAME" => GetMessage("GROUP_PARAM_NEW_COUPON_ADD"),
            "NAME" => GetMessage("NEW_COUPON_LIFETIME_TITLE"),
            "TYPE" => "INT",
            "DEFAULT" => "48",
            "SORT" => "40",
        );
        $arTemplateParameters["NEW_COUPON_LIFETIME_ACTION"] = array(
            "TABS" => 'TABS_DISCOUNT',
            "TABS_NAME" => GetMessage("TABS_DISCOUNT_NAME"),
            "PARENT" => "PARAM_NEW_COUPON_ADD",
            "PARENT_NAME" => GetMessage("GROUP_PARAM_NEW_COUPON_ADD"),
            "NAME" => GetMessage("NEW_COUPON_LIFETIME_ACTION_TITLE"),
            "TYPE" => "LIST",
            "VALUES" => $arActionsAfterCouponLifetime,
            "SORT" => "50",
        );
    }
}

if(CModule::IncludeModule("catalog"))
{
    $arTemplateParameters["COUPON_ADD"] = array(
        "TABS" => 'TABS_DISCOUNT',
        "TABS_NAME" => GetMessage("TABS_DISCOUNT_NAME"),
        "PARENT" => "PARAM_COUPON_ADD",
        "PARENT_NAME" => GetMessage("GROUP_PARAM_COUPON_ADD"),
        "NAME" => GetMessage("COUPON_ADD_TITLE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "4",
        "SORT" => "10",
        "REFRESH" => "Y",
    );

    if($arCurrentValues["COUPON_ADD"] == 'Y')
    {
        $arTemplateParameters["COUPON_DISCOUNT_ID"] = array(
            "TABS" => 'TABS_DISCOUNT',
            "TABS_NAME" => GetMessage("TABS_DISCOUNT_NAME"),
            "PARENT" => "PARAM_COUPON_ADD",
            "PARENT_NAME" => GetMessage("GROUP_PARAM_COUPON_ADD"),
            "NAME" => GetMessage("COUPON_DISCOUNT_ID_TITLE"),
            "TYPE" => "LIST",
            "VALUES" => $arDiscount_list,
            "SORT" => "20",
        );
        $arTemplateParameters["COUPON_ONE_TIME"] = array(
            "TABS" => 'TABS_DISCOUNT',
            "TABS_NAME" => GetMessage("TABS_DISCOUNT_NAME"),
            "PARENT" => "PARAM_COUPON_ADD",
            "PARENT_NAME" => GetMessage("GROUP_PARAM_COUPON_ADD"),
            "NAME" => GetMessage("COUPON_ONE_TIME_TITLE"),
            "TYPE" => "LIST",
            "VALUES" => $arDiscount_Type,
            "SORT" => "30",
        );
        $arTemplateParameters["COUPON_TIME_LIFE"] = array(
            "TABS" => 'TABS_DISCOUNT',
            "TABS_NAME" => GetMessage("TABS_DISCOUNT_NAME"),
            "PARENT" => "PARAM_COUPON_ADD",
            "PARENT_NAME" => GetMessage("GROUP_PARAM_COUPON_ADD"),
            "NAME" => GetMessage("COUPON_TIME_LIFE_TITLE"),
            "TYPE" => "INT",
            "DEFAULT" => "48",
            "SORT" => "40",
        );
        $arTemplateParameters["COUPON_TIME_LIFE_ACTION"] = array(
            "TABS" => 'TABS_DISCOUNT',
            "TABS_NAME" => GetMessage("TABS_DISCOUNT_NAME"),
            "PARENT" => "PARAM_COUPON_ADD",
            "PARENT_NAME" => GetMessage("GROUP_PARAM_COUPON_ADD"),
            "NAME" => GetMessage("COUPON_TIME_LIFE_ACTION_TITLE"),
            "TYPE" => "LIST",
            "VALUES" => $arDiscount_life_time_action,
            "SORT" => "50",
        );
    }
}

?>