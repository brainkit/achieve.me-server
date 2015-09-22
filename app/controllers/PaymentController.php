<?php
require_once(__DIR__.'/../config/payment.php');

class PaymentController extends \BaseController {

    /*
     *  GET /user_id=123&achievement_id=123
     */
    public function showForm() {
        global $shopId;
        global $scid;
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
            return Response::make('hello', 400);
        }

    }

    public function orderCheck(){

    }

    public function paymentAviso() {

    }

    public function processForm(){

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
