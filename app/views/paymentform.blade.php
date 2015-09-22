<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Создание достижения</title>
</head>
<body>
<!-- Значения всех полей условны и приведены исключительно для примера -->
<form action="https://money.yandex.ru/eshop.xml" method="post">

    <!-- Обязательные поля -->
    <input name="shopId" value="{{{ $shopId }}}" type="hidden"/>
    <input name="scid" value="{{{ $scid }}}" type="hidden"/>
    <input name="sum" value="{{{ $sum or '0.00' }}}" type="text">
    <input name="customerNumber" value="{{{ $customerNumber or 'undefined' }}}" type="hidden"/>

    <!-- Необязательные поля -->
    <input name="paymentType" value="AC" type="hidden"/>
    <input name="orderNumber" value="{{{ $orderNumber or 'undefined' }}}" type="hidden"/>

    <input type="submit" value="Заплатить"/>
</form>
</body>
</html>