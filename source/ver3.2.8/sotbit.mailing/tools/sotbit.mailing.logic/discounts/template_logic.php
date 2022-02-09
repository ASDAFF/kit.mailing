<?

if($arParams['NEW_COUPON_ADD'] == 'Y' && \Bitrix\Main\Loader::includeModule('sale'))
{
    $COUPON = \Bitrix\Sale\Internals\DiscountCouponTable::generateCoupon(true);

    $activeFrom = new \Bitrix\Main\Type\DateTime();
    $activeTo = new \Bitrix\Main\Type\DateTime();
    $activeTo = $activeTo->add($arParams['NEW_COUPON_LIFETIME'].' hours');

    $arCouponFields = array(
        "DISCOUNT_ID" => $arParams['NEW_COUPON_DISCOUNT_ID'],
        "ACTIVE" => "Y",
        "TYPE" => $arParams['NEW_COUPON_TYPE'],
        "COUPON" => $COUPON,
        'ACTIVE_FROM' => $activeFrom,
        'ACTIVE_TO' => $activeTo,
    );

    $result = \Bitrix\Sale\Internals\DiscountCouponTable::add($arCouponFields);
    if($result->isSuccess())
    {
        $ItemEmailSend['COUPON'] = $COUPON;
        $ItemEmailSend['PARAM_2'] = $COUPON;
        $ItemEmailSend['PARAM_MESSEGE']['COUPON'] = $COUPON;
    }
    else
    {
        $errors = $result->getErrorMessages();
        //file_put_contents(dirname(__FILE__).'/log_errors.log', print_r($errors, true));
    }

    return;
}

if($arParams['COUPON_ADD'] == 'Y' && CModule::IncludeModule("catalog"))
{
    //создадим купон
    //START
    $COUPON = CatalogGenerateCoupon();
    $arCouponFields = array(
        "DISCOUNT_ID" => $arParams['COUPON_DISCOUNT_ID'],
        "ACTIVE" => "Y",
        "ONE_TIME" => $arParams['COUPON_ONE_TIME'],
        "COUPON" => $COUPON,
        "DATE_APPLY" => false
    );
    if(CCatalogDiscountCoupon::Add($arCouponFields))
    {
        $ItemEmailSend['COUPON'] = $COUPON;
        $ItemEmailSend['PARAM_2'] = $COUPON;
        $ItemEmailSend['PARAM_MESSEGE']['COUPON'] = $COUPON;
    }
    //END
}

?>