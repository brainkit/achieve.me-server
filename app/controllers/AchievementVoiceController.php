<?php
/**
 * Created by PhpStorm.
 * User: Julia
 * Date: 11.07.15
 * Time: 11:15
 */

class AchievementVoiceController extends \BaseController
{

    /**
     * Display a listing of the voices for achievement.
     * GET /achievement-voices?achievement=%achievement_id%&hash=%user_hash%
     *
     * @return Response
     */
    public function index(){
        $per_page = 10; // limit
        $current_page = 1; // page
        if (Request::get('page')) {
            $current_page = Request::get('page');
        };
        if (Request::get('limit')) {
            $per_page = Request::get('limit');
        };

        if (Request::get('achievement')) {
            $achievement_id = Request::get('achievement');
            $voices = AchievementVoice::where("achievement_id", $achievement_id)->paginate($per_page);
            return Response::json(array($voices->toJson()), 200);
        } else {
            return Response::json(array(
                'error' => true,
                'message' => 'achievement_id not specified'), 404);
        }
    }

    /*
     * Display voice for specified achievement and user
     * GET /achievement-voices/voices?user=%user id%&achievemnt=%achievement_id%&hash=%user_hash%
     * @Return Response
     */
    public function getVoice() {
        //$achievement = Achievement::find($achievement_id);
         if(Request::get('user') && Request::get('achievement')) {
                $voice =  AchievementVoice::where("achievement_id", Request::get('achievement'))
                    ->where('user_id', Request::get('user'))->first();
            if ($voice) {
                return Response::json(array(
                        'error' => false,
                        'achievement_voice' =>  $voice),
                    200
                );
            }

        }
        return Response::json(array(
                    'error' => true,
                    'message' =>  "voice not found"),
                200
            );

    }

    /**
     * Store a newly created resource in storage.
     * POST /achievement-voices
     * @return Response
     */
    public function store() {
        $voice = new AchievementVoice;
        if(Request::get('user_id')) {
            $voice->user_id = Request::get('user_id');
        }
        if(Request::get('achievement_id')) {
            $voice->achievement_id = Request::get('achievement_id');
        }
        if(Request::get('voice')) {
            $voice->voice = Request::get('voice');
        }
        $voice->save();
        return Response::json(array(
                'error' => false,
                'voice' =>  $voice),
            200
        );
    }

    /**
     * Display the specified resource.
     * GET /achievement-voices/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $achieve_voice = AchievementVoice::find($id);

        if ($achieve_voice) {
            return Response::json(array(
                    'error' => false,
                    'achievement_voice' =>  $achieve_voice),
                200
            );
        }else {
            return Response::json(array(
                    'error' => true,
                    'message' =>  "voice not found"),
                200
            );
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /achievement-voices/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $voice = AchievementVoice::find($id);

        if($voice) {
            if(Request::get('user_id')) {
                $voice->user_id = Request::get('user_id');
            }
            if(Request::get('achievement_id')) {
                $voice->achievement_id = Request::get('achievement_id');
            }
            if(Request::get('voice')) {
                $voice->voice = Request::get('voice');
            }
            $voice->save();
            return Response::json(array(
                    'error' => false,
                    'message' => 'voice updated'),
                200
            );
        } else {
            return Response::json(array(
                'error' => true,
                'message' => 'voice not found'), 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /achievement-voices/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $voice = AchievementVoice::find($id);
        if ($voice) {
            $voice->delete();

            return Response::json(array(
                'error' => false,
                'message' => 'achievement voice deleted'), 200);
        } else {
            return Response::json(array(
                'error' => true,
                'message' => 'voice not found'), 404);
        }
    }

    /**
     * Restore the specified resource from trash
     * @param int $id
     * @return Response
     */
    public function restore ($id) {
        $voice = AchievementVoice::withTrashed()->find($id);
        if ($voice) {
            $voice->restore();
            return Response::json(array(
                    'error' => false,
                    'voice' =>  $voice),
                200
            );
        } else {
            return Response::json(array(
                    'error' => true,
                    'message' => 'achievement not found'),
                200
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     * GET /achievement-voices/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * GET /achievement-voices/create
     *
     * @return Response
     */
    public function create()
    {

    }

}