<?php
define('YM_NEW_STATUS', 0);
define('YM_WAIT_STATUS', 1);
define('YM_CHECK_STATUS', 3);
define('YM_PAYMENT_STATUS', 4);
define('YM_REFUND_STATUS', 5);

class PaymentController extends \BaseController {

    public function showError() {
        /*var_dump($_REQUEST);
        die();*/
        return Response::make('Wrong request', 400);
    }

    /*
     *  GET /user_id=123&achievement_id=123
     */
    public function showForm() {
        $shopId = Config::get('payment.shopId');
        $scid = Config::get('payment.scid');
        if (Request::get('user_id') && Request::get('achievement_id')){
            $user = User::find(Request::get('user_id'));
            $achievement = Achievement::find(Request::get('achievement_id'));
            return View::make('paymentform',
                array('customerNumber' =>$user->id,
                    'orderNumber' => $achievement->id,
                    'sum' => $achievement->rate,
                    'shopId' =>$shopId,
                    'scid' => $scid));
        } else {
            return Response::make('user & achievement not found', 400);
        }

    }

    /*
     * POST /payment/orderCheck
     * */
    public function orderCheck(){

        //var_dump($_REQUEST); die();
        $shopId = Config::get('payment.shopId');
        $scid = Config::get('payment.scid');
        $ShopPassword = Config::get('payment.ShopPassword');

        $rezult='';

        $error=1; // Взводим код ошибки

        // Контрольные данные о заказе:
        // Идентификатор запроса
        $order_invoice=( isset($_REQUEST['invoiceId']) ? $_REQUEST['invoiceId'] : 0 );
        // Сумма заказа
        $order_amount=( isset($_REQUEST['orderSumAmount']) ? $_REQUEST['orderSumAmount'] : 0.0 );
        // Код валюты для суммы заказа
        $order_currency=( isset($_REQUEST['orderSumCurrencyPaycash']) ? intval($_REQUEST['orderSumCurrencyPaycash']) : 0 );
        // Код процессингового центра Оператора для суммы заказа
        $order_bank=( isset($_REQUEST['orderSumBankPaycash']) ? intval($_REQUEST['orderSumBankPaycash']) : 0 );
        // Идентификатор плательщика (присланный в платежной форме)
        $order_customer=( isset($_REQUEST['customerNumber']) ? $_REQUEST['customerNumber'] : 0 ); //user_id
        // Контрольный MD5-хеш
        $md5=( isset($_REQUEST['md5']) ? $_REQUEST['md5'] : md5("Yandex.Money demo mode") );
        //Date
        $orderCreatedDatetime  = ( isset($_REQUEST['orderCreatedDatetime']) ? $_REQUEST['orderCreatedDatetime'] : time() );
        // Номер заказа в БД магазина (присланный в платежной форме)
        $order_id=( isset($_REQUEST['orderNumber']) ? $_REQUEST['orderNumber'] : 0); //achievement_id

        $sum=floatval($order_amount);
        $query = "";
        $query_achievement = "";

        //Ответ на запрос checkOrder от Яндекс.Денег (проверка параметров заказа в базе данных)
        if($_REQUEST['action']=='checkOrder' && $order_id )
        {
            //Проверка кода и переводимой за него суммы
            $query = "select *
            from `user_achievements`
            where `user_id` = '".$order_customer."' AND `achievement_id`='".$order_id."'";

            $query_achievement = "select *
            from `achievements`
            where `id` = '".$order_id."' AND `rate`='".intval($order_amount)."'";

            $query_payment = "	SELECT `invoice_id`,
                        `achievement_id`,
						`sum`,
						`status`
				FROM payments
				WHERE
						`invoice_id` = '".$order_invoice."'
						AND `achievement_id`='".$order_id."'
						AND `sum`>='".$sum."'
						AND `status` in ('".YM_WAIT_STATUS."','".YM_PAYMENT_STATUS."','".YM_CHECK_STATUS."')";
            $error=0;
        }

        if( $error )
        {
            //Отвечаем серверу Яндекс.Денег, кодом 200 - ИС Контрагента не в состоянии разобрать запрос. Оператор считает ошибку окончательной и не будет осуществлять перевод.
            $rezult=$this->answer('checkOrder',$shopId,$order_invoice,200);
            $error=1;
        } elseif ( $query && $query_achievement ) {
            //Запрос в к базе данных. Ищем достижение с id = $order_id и пользователем с id = $order_customer
            $results = DB::select($query);
            //  проверяем, верная ли сумма
            $results_ach = DB::select($query_achievement);
            // repeat request check
            $results_payments= DB::select($query_payment);

            //проверка, существует ли достижение с этой ставкой и пользователем
            if(count($results) == 1 && count($results_ach>1)) {
                $record = $results[0];
                //echo(md5("checkOrder;$order_amount;$order_currency;$order_bank;$shopId;$order_invoice;$order_customer;$ShopPassword"));die();
                if ( strcasecmp(md5("checkOrder;$order_amount;$order_currency;$order_bank;$shopId;$order_invoice;$order_customer;$ShopPassword"), $md5) === 0 )
                {
                    $time=time();
                    $d = array();
                    $d['order_id'] = $order_id;	//Идентификатор записи заказа
                    $is_repeat_request = false;	//Повторный запрос от Яндекс.Денег с тем же invoiceId

                    // Ответ на первый запрос checkOrder от Яндекс.Денег (подтверждение заказа на оплату)
                    //Если такой заказ уже есть, обновляем
                    if (count($results_payments)>1) {
                        //var_dump($results_payments); die();
                        $record = $results_payments[0];
                        if ( $record->status == YM_WAIT_STATUS ){
                            //Изменяем статус заказа на ПОЛЬЗОВАТЕЛЬ ПОДТВЕРДИЛ ЧЕК
                            $qv = "update payments
								set status = ".YM_CHECK_STATUS."
								where invoice_id = ".$record->invoice_id."";
                            $ans = DB::update($qv);
                            if($ans) $rezult= $this->answer('checkOrder',$shopId,$order_invoice,0,  $order_amount);
                        } elseif ( $record->status== YM_CHECK_STATUS ) {
                                //Отвечаем серверу Яндекс.Денег, что все хорошо, такой заказ существует и в нужном статусе
                                $rezult= $this->answer('checkOrder',$shopId,$order_invoice,0, $order_amount);
                        } else {
                            //Отвечаем серверу Яндекс.Денег, кодом 100 - Отказ в приеме перевода с заданными параметрами. Оператор считает ошибку окончательной и не будет осуществлять перевод.
                            $rezult= $this->answer('checkOrder',$shopId,$order_invoice,100);
                        }
                        $is_repeat_request = 1;
                    } else {
                        //создание нового заказа
                        $qv = "insert into payments(user_id, invoice_id, achievement_id, status, sum, time)
                        values('".$order_customer."','".$order_invoice."', '".$order_id."', '".YM_CHECK_STATUS."','".$order_amount."','".$orderCreatedDatetime."')";
                        $ans = DB::insert($qv);
                        if($ans) $rezult= $this->answer('checkOrder',$shopId,$order_invoice,0, $order_amount);
                    }
                }else {
                    //Отвечаем серверу Яндекс.Денег, кодом 1 - Несовпадение подписи (или хеша), неверный ключ подписи. Оператор считает ошибку окончательной и не будет осуществлять перевод.
                    $rezult=$this->answer('checkOrder',$shopId,$order_invoice,1);
                    $error=1;
                }

            } else {
                //Отвечаем серверу Яндекс.Денег, кодом 666 - Да, такой ошибки скорее всего нет, ну и ладно. Платеж то все-равно не прошел.
                $rezult=$this->answer('checkOrder',$shopId,$order_invoice,666);
                $error=1;
            }

        }

        return $rezult;
    }

    public function paymentAviso() {
        $shopId = Config::get('payment.shopId');
        $scid = Config::get('payment.scid');
        $ShopPassword = Config::get('payment.ShopPassword');

        $rezult='';

        $error=1; // Взводим код ошибки

        // Контрольные данные о заказе:
        // Идентификатор запроса
        $order_invoice=( isset($_REQUEST['invoiceId']) ? $_REQUEST['invoiceId'] : 0 );
        // Сумма заказа
        $order_amount=( isset($_REQUEST['orderSumAmount']) ? $_REQUEST['orderSumAmount'] : 0.0 );
        // Код валюты для суммы заказа
        $order_currency=( isset($_REQUEST['orderSumCurrencyPaycash']) ? intval($_REQUEST['orderSumCurrencyPaycash']) : 0 );
        // Код процессингового центра Оператора для суммы заказа
        $order_bank=( isset($_REQUEST['orderSumBankPaycash']) ? intval($_REQUEST['orderSumBankPaycash']) : 0 );
        // Идентификатор плательщика (присланный в платежной форме)
        $order_customer=( isset($_REQUEST['customerNumber']) ? $_REQUEST['customerNumber'] : 0 );
        // Контрольный MD5-хеш
        $md5=( isset($_REQUEST['md5']) ? $_REQUEST['md5'] : md5("Yandex.Money demo mode") );
        //Date
        $orderCreatedDatetime  = ( isset($_REQUEST['orderCreatedDatetime']) ? $_REQUEST['orderCreatedDatetime'] : time() );
        // Номер заказа в БД магазина (присланный в платежной форме)
        $order_id=( isset($_REQUEST['orderNumber']) ? $_REQUEST['orderNumber'] : 0); //achievement_id

        $sum=floatval($order_amount);
        $qv = "";

        //Ответ на запрос checkOrder от Яндекс.Денег (проверка параметров заказа в базе данных)
        if($_REQUEST['action']=='checkOrder' && $order_id )
        {
            //Проверка кода и переводимой за него суммы
            $qv = "	SELECT `invoice_id`,
                        `achievement_id`,
						`sum`,
						`status`
				FROM payments
				WHERE
						`invoice_id` = '".$order_invoice."'
						AND `achievement_id`='".$order_id."'
						AND `sum`>='".$sum."'
						AND `status` in ('".YM_WAIT_STATUS."','".YM_PAYMENT_STATUS."')";
            $error=0;
        }

        if( $error )
        {
            //Отвечаем серверу Яндекс.Денег, кодом 200 - ИС Контрагента не в состоянии разобрать запрос. Оператор считает ошибку окончательной и не будет осуществлять перевод.
            $rezult=$this->answer('paymentAviso',$shopId,$order_invoice,200);
            $error=1;
        } elseif ( $qv ) {
            //Запрос в к базе данных о заказе
            $results = DB::select($qv);

            if(count($results) == 1) {
                $record = $results[0];
                if ( strcasecmp(md5("paymentAviso;$order_amount;$order_currency;$order_bank;$shopId;$order_invoice;$order_customer;$ShopPassword"), $md5) === 0 )
                {
                    $time=time();
                    $d = array();
                    $d['order_id'] = $order_id;	//Идентификатор записи заказа
                    $is_repeat_request = false;	//Повторный запрос от Яндекс.Денег с тем же invoiceId

                    //Ответ на первый запрос paymentAviso от Яндекс.Денег (прием оплаты)
                    if ($record['order_status'] != YM_CHECK_STATUS || $record['order_status'] != YM_WAIT_STATUS) {
                        $is_repeat_request = true;
                    }

                    if ( !$is_repeat_request )
                    {
                        // Обновление состояния заказа
                        //Создаем запись о заказе с указаным invoiceId или обновляем статус заказа
                        $qv = "update payments
								set status = ".YM_PAYMENT_STATUS."
								where id = ".$record['id']."";
                        $ans = DB::update($qv);
                        //Отвечаем серверу Яндекс.Денег, что все хорошо, можно принимать деньги
                        if($ans) $rezult= $this->answer('paymentAviso',$shopId,$order_invoice,0);

                    } else {

                        if ( $record['order_status'] == YM_PAYMENT_STATUS )
                        {
                            //Отвечаем серверу Яндекс.Денег, что все хорошо, такой заказ существует и в нужном статусе
                            $rezult= $this->answer('paymentAviso',$shopId,$order_invoice,0);
                        } else {
                            //Отвечаем серверу Яндекс.Денег, кодом 100 - Отказ в приеме перевода с заданными параметрами. Оператор считает ошибку окончательной и не будет осуществлять перевод.
                            $rezult= $this->answer('paymentAviso',$shopId,$order_invoice,1);
                        }
                    }

                }else {
                    //Отвечаем серверу Яндекс.Денег, кодом 1 - Несовпадение подписи (или хеша), неверный ключ подписи. Оператор считает ошибку окончательной и не будет осуществлять перевод.
                    $rezult=$this->answer('paymentAviso',$shopId,$order_invoice,1);
                    $error=1;
                }

            } else {
                //Отвечаем серверу Яндекс.Денег, кодом 666 - Да, такой ошибки скорее всего нет, ну и ладно. Платеж то все-равно не прошел.
                $rezult=$this->answer('paymentAviso',$shopId,$order_invoice,666);
                $error=1;
            }

        }

        return $rezult;

    }

    public function processForm(){

    }

    //Функция выдает ответ для платежной системы Яндекс.Деньги в формате XML
    function answer($action,$shopID,$invoiceId,$code, $sum =0)
    {
        switch ($action)
        {
            case 'checkOrder':
                $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".'<checkOrderResponse performedDatetime ="'.date(DATE_ATOM).'" code="'.(int)$code.'" invoiceId="'.$invoiceId.'" shopId="'.(int)$shopID.'" orderSumAmount="'.$sum.'"/>';
                break;
            case 'paymentAviso':
                $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".'<paymentAvisoResponse performedDatetime ="'.date(DATE_ATOM).'" code="'.(int)$code.'" invoiceId="'.$invoiceId.'" shopId="'.(int)$shopID.'"/>';
                break;
            default:
                $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".'<'.$action.'Response performedDatetime ="'.date(DATE_ATOM).'" code="'.(int)$code.'" invoiceId="'.$invoiceId.'" shopId="'.(int)$shopID.'"/>';
        }
        return Response::make($content, '200')->header('Content-Type', 'text/xml');
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
