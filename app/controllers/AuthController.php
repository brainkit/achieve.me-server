<?php

class AuthController extends \BaseController {
  /**
     * GET /auth
     *
     * @return Response
     */
    public function index() {
        $user = User::where('email', '=', Request::get('email'))->first();
        if ($user) {
            if (Hash::check(Request::get('password'), $user->password)) {
                return Response::json(array(
                            'hash' => $user->hash,
                            'message' => 'user autorized'), 200);
            } else {
                return Response::json(array('message' => 'Unauthorized'), 401);
            }
        } else {
            return Response::json(array('message' => 'Unauthorized'), 401);
        }
    }

}
