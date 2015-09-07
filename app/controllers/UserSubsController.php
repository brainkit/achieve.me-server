<?php

class UserSubsController extends \BaseController {

    /**
     * Display a listing of the resource.
     * GET /user-subs
     *
     * @return Response
     */
    public function index() {
        $limit = 10;
        if (Request::get('limit')) {
            $limit = Request::get('limit');
        };
        $UserSubs = User::find(Session::get('user_id'))->subs()->paginate($limit);
        // $UserAchievements = UserAchievements::where('user_id', '=', $user->id)->get();
        //Return $UserSubs->toJson();
        return Response::json(array($UserSubs->toJson()), 200);
    }

    /**
     * Show the form for creating a new resource.
     * GET /user-subs/create
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /user-subs
     *
     * @return Response
     */
    public function store() {
        $user_subs = new UserSubs;
        $user_subs->user_id = Session::get('user_id');
        if (Request::get('user_id_sub')) {
            $user_subs->user_id_sub = Request::get('user_id_sub');
        }

        $user_subs->save();
        return Response::json(array(
                    'error' => false,
                    'message' => "user sub added"), 200
        );
    }

    /**
     * Display the specified resource.
     * GET /user-subs/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * GET /user-subs/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /user-subs/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /user-subs/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $user_sub = UserSubs::where('user_id_sub', '=', $id)->first();
        if ($user_sub) {
            $user_sub->delete();

            return Response::json(array(
                        'error' => false,
                        'message' => 'sub deleted'), 200);
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => 'sub not found'), 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /user-subs/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function restore($id) {
        $user_sub = UserSubs::withTrashed()->where('user_id_sub', '=', $id)->first();
        if ($user_sub) {
            $user_sub->delete();

            return Response::json(array(
                        'error' => false,
                        'message' => 'sub deleted'), 200);
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => 'sub not found'), 404);
        }
    }

}
