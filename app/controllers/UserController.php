<?php

class UserController extends \BaseController {

    /**
     * Display a listing of the resource.
     * GET /user
     *
     * @return Response
     */
    public function index() {
        $user = User::find(Session::get('user_id'));
        return $user->toJson();
    }

    public function all() {
        $limit = 5;
        if (Request::get('limit')) {
            $limit = Request::get('limit');
        };
        $users = User::paginate($limit);
        // $UserAchievements = UserAchievements::where('user_id', '=', $user->id)->get();
        return Response::json(array($users->toJson()), 200);
    }

    /**
     * Show the form for creating a new resource.
     * GET /user/create
     *
     * @return Response
     */
    public function create() {
        /*   $user = new User;
          $user->email = "syrex88@gmail.com";
          $user->password = Hash::make("password");
          $hash = Hash::make("id");
          $hash = str_replace("/", "1", $hash);
          $hash = str_replace("/", "1", $hash);
          $user->hash = $hash;
          $user->save();
          //print "<pre>"; print_R($user->id); print "</pre>";
          $this->create_user_settings($user->id); */
    }

    /**
     * Store a newly created resource in storage.
     * POST /user
     *
     * @return Response
     */
    public function store() {
        $user = new User;
        if (Request::get('email')) {
            $user->email = Request::get('email');
        }
        if (Request::get('password')) {
            $user->password = Hash::make(Request::get('password'));
        }
        if (Request::get('deviceId') and Request::get('email')) {
            $hash = Hash::make(Request::get('deviceId') . Request::get('email'));
            $hash = str_replace("/", "1", $hash);
            $hash = str_replace("&", "0", $hash);
            $user->hash = $hash;
            $user->save();
            $this->create_user_settings($user->id);
            return Response::json(array(
                        'hash' => $hash,
                        'message' => 'user created'), 200);
        } else {
            return Response::json(array('message' => 'no deviceId'), 500);
        }
    }

    /**
     * Get users by name
     * GET /user-search/string
     *
     * @return Response
     */
    public function search($string) {
        $users = UserSettings::where('name', 'like', "%" . $string . "%")->get();
        return Response::json(array($users), 200);
    }

    private function create_user_settings($id) {
        $userSettings = new UserSettings;
        $userSettings->user_id = $id;
        /* $userSettings->social_integration = 1;
          $userSettings->name = "";
          $userSettings->photo = "";
          $userSettings->rating = "";
          $userSettings->interests = ""; */
        $userSettings->save(); /* */
    }

    /**
     * Display the specified resource.
     * GET /user/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($hash) {
        $user = User::find(Session::get('user_id'));
        return $user->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     * GET /user/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($hash) {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /user/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($hash) {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /user/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $user = User::find(Session::get('user_id'));
        if ($user) {
            $user->delete();
            return Response::json(array(
                        'error' => false,
                        'message' => 'user deleted'), 200);
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => 'user not found'), 404);
        }
    }

    /**
     * GET /user/{id}/restore
     *
     * @param  int  $id
     * @return Response
     */
    public function restore($id) {
        $user = User::withTrashed()->find($id);
        if ($user) {
            $user->restore();
            return Response::json(array(
                        'error' => false,
                        'user' => $user->toJson()), 200
            );
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => 'user not found'), 200
            );
        }
    }

}
