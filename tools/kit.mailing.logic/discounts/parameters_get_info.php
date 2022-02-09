<?

if(\Bitrix\Main\Loader::includeModule('sale'))
{
    $arBasketRulesList = array('' => GetMessage('SELECT_CHANGE'));
    $arBasketRules = \Bitrix\Sale\Internals\DiscountTable::GetList(
        array(),
        array(),
        false,
        false,
        array('ID', 'NAME')
    );
    while($rule = $arBasketRules->Fetch())
    {
        $arBasketRulesList[$rule['ID']] = '['.$rule['ID'].'] '.$rule['NAME'];
    }

    $arCouponTypes = array(
        '1' => GetMessage('NEW_COUPON_ONE_TIME'),
        '2' => GetMessage('NEW_COUPON_ONE_ORDER'),
        '4' => GetMessage('NEW_COUPON_NO_LIMIT')
    );

    $arActionsAfterCouponLifetime = array(
        'DELETE' => GetMessage('NEW_COUPON_LIFETIME_ACTION_VALUE_DELETE'),
        'DEACTION' => GetMessage('NEW_COUPON_LIFETIME_ACTION_VALUE_DEACTIVATE')
    );
}

if(CModule::IncludeModule("catalog"))
{
    $arDiscount_list = array('' => GetMessage('SELECT_CHANGE'));
    $rsDiscount = CCatalogDiscount::GetList(
        array(), 
        Array(),
        false,
        false,
        array('ID', 'NAME', 'SITE_ID')
    );
    while($arrDiscount = $rsDiscount->Fetch())
    {
        $arDiscount_list[$arrDiscount["ID"]] = "[".$arrDiscount["ID"]."] ".$arrDiscount["NAME"];
    }
}

$arDiscount_Type = array(
    'Y' => GetMessage('COUPON_ONE_TIME_VALUE_Y'),
    'O' => GetMessage('COUPON_ONE_TIME_VALUE_O'),
    'N' => GetMessage('COUPON_ONE_TIME_VALUE_N')
);

$arDiscount_life_time_action = array(
    'DELETE' => GetMessage('COUPON_TIME_LIFE_ACTION_VALUE_DELETE'),
    'DEACTION' => GetMessage('COUPON_TIME_LIFE_ACTION_VALUE_DEACTION')
);

?>