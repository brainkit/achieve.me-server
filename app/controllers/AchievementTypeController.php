<?php
/**
 * Created by PhpStorm.
 * User: Julia
 * Date: 26.08.14
 * Time: 1:50
 */


class AchievementTypeController extends \BaseController
{
    /**
     * Display a listing of the resource.
     * GET /achievement-types?limit=%num_pages_to_show%&page=%age_number%&hash=%user_hash%
     *
     * @return Response
     */
    public function  index() {
        $per_page = 10; // limit
        $current_page = 1; // page
        if (Request::get('page')) {
            $current_page = Request::get('page');
        };
        if (Request::get('limit')) {
            $per_page = Request::get('limit');
        };
        $types = AchievementType::paginate($per_page);

        return Response::json(array($types->toJson()),
            200
        );
    }



    /**
     * Store a newly created resource in storage.
     * POST /achievement-types
     * @return Response
     */
    public function store() {
        $type = new AchievementType;

        if(Request::get('type_id')) {
            $type->type = Request::get('type_id');
        }
        if(Request::get('achievement_id')) {
            $type->type = Request::get('achievement_id');
        }
        $type->save();
        return Response::json(array(
                'error' => false,
                'type' =>  $type),
            200
        );
    }

    /**
     * Display the specified resource.
     * GET /achievement-types/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $achieve_type = AchievementType::find($id);

        if ($achieve_type) {
            return Response::json(array(
                    'error' => false,
                    'achievement_type' =>  $achieve_type),
                200
            );
        }else {
            return Response::json(array(
                    'error' => true,
                    'message' =>  "type not found"),
                200
            );
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /achievement-types/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $achieve_type = AchievementType::find($id);

        if($achieve_type) {
            $achieve_type->type_id = Request::get('type_id');
            $achieve_type->achievement_id = Request::get('achievement_id');
            $achieve_type->save();
            return Response::json(array(
                    'error' => false,
                    'message' => 'type updated'),
                200
            );
        } else {
            return Response::json(array(
                'error' => true,
                'message' => 'type not found'), 404);
        }
    }


    /**
     * Remove the specified resource from storage.
     * DELETE /achievement-types/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $type = AchievementType::find($id);
        if ($type) {
            $type->delete();

            return Response::json(array(
                    'error' => false,
                    'message' => 'achievement type deleted'), 200);
        } else {
            return Response::json(array(
                'error' => true,
                'message' => 'type not found'), 404);
        }
    }


    /**
     * Restore the specified resource from trash
     * @param int $id
     * @return Response
     */
    public function restore ($id) {
        $achieve_type = AchievementType::withTrashed()->find($id);
        if ($achieve_type) {
            $achieve_type->restore();
            return Response::json(array(
                    'error' => false,
                    'achievement' =>  $achieve_type),
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
     * GET /achievement-types/{id}/edit
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
     * GET /achievement-types/create
     *
     * @return Response
     */
    public function create()
    {

    }

}

