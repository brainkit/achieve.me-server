<?php

class UserAchievementsController extends \BaseController {

    /**
     * Display a listing of the resource.
     * GET /user-achievements?page=1&limit=5&hash=$2y$10$MPO7P1iQjxtHewmnOO.GK.XJcwvGI7cLDkKaACISc8yI.Sfi9np1O
     *
     * @return Response
     */
    public function index() {
        $limit = 100;
        if (Request::get('limit')) {
            $limit = Request::get('limit');
        };
        $UserAchievements = User::find(Session::get('user_id'))->achievements()->paginate($limit);
        // $UserAchievements = UserAchievements::where('user_id', '=', $user->id)->get();
        return $UserAchievements->toJson();
    }

     /**
     * Display a count resource.
     * GET /user-achievements-count
     *
     * @return Response
     */
    public function count() {
        $count = User::find(Session::get('user_id'))->achievements()->count();
        return $count;
    }

    /**
     * Show the form for creating a new resource.
     * GET /user-achievments/create/{achievement_id}
     *
     * @return Response
     */
    public function create($achievement_id) {
        $user = User::find(Session::get('user_id'));
        $UserAchievements = new UserAchievements;
        $UserAchievements->user_id = $user->id;
        $UserAchievements->achievement_id = $achievement_id;
        $UserAchievements->save();
    }

    /**
     * Store a newly created resource in storage.
     * POST /user-achievements
     *
     * @return Response
     */
    public function store() {
        $user_id = Session::get('user_id');
        $achievement_id = Request::get('achievement_id');
        $CheckAchievement = UserAchievements::whereRaw('user_id = ? and achievement_id = ?', array($user_id, $achievement_id))->first();
        if (!$CheckAchievement) {
            $user = User::find($user_id);
            $UserAchievement = new UserAchievements;
            $UserAchievement->user_id = $user->id;
            $UserAchievement->achievement_id = $achievement_id;
            $UserAchievement->save();
        }
        /* */
    }

    /**
     * Display the specified resource.
     * GET /user-achievments/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * GET /user-achievments/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /user-achievments/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /user-achievments/{achievement_id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($achievement_id) {
        $user_id = Session::get('user_id');
        $UserAchievement = UserAchievements::whereRaw('user_id = ? and achievement_id = ?', array($user_id, $achievement_id))->first();
        if ($UserAchievement) {
            $UserAchievement->delete();

            return Response::json(array(
                        'error' => false,
                        'message' => 'user achievement deleted'), 200);
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => 'user achievement not found'), 404);
        }
    }

    /**
     * GET /user-achievments/{achievement_id}/restore
     *
     * @param  int  $id
     * @return Response
     */
    public function restore($achievement_id) {
        $user_id = Session::get('user_id');
        $UserAchievement = UserAchievements::withTrashed()->whereRaw('user_id = ? and achievement_id = ?', array($user_id, $achievement_id))->first();
        $UserAchievement->restore();
    }


    /**
     * show user subs achievements
     * GET /user/subs/achievements/{user_id}
     *
     * @param  int  $id
     * @return Response
     */
    public function subs_achieve($user_id) {
        $subs = UserSubs::where('user_id', 'like',$user_id)->get();
        $achievements = array();
        foreach ($subs as $sub) {
            $sub_achievements = User::find($sub->user_id_sub)->achievements()->get();
            foreach ($sub_achievements as $achieve) {
                if (!in_array($achieve, $achievements)) {
                    $achievements[] = $achieve;
                }
            }
        }
        return Response::json(array(
            'error' => false,
            'achievements' => $achievements), 200);
    }
}
