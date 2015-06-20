<?php
/**
 * Created by PhpStorm.
 * User: Julia
 * Date: 05.09.14
 * Time: 23:33
 */

class TypeController extends \BaseController {

    /**
     * Display a listing of the resource.
     * GET /types
     *
     * @return Response
     */
    public function  index() {
        $types = Type::all();

        return Response::json(array(
                'error' => false,
                'types' => $types->toArray()),
            200
        );
    }

    /**
     * Show the form for editing the specified resource.
     * GET /types/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
       $hash = Request::get('hash');
        return View::make('type', array('hash' => $hash, 'id' => $id));
    }

    /**
     * Show the form for creating a new resource.
     * GET /types/create
     *
     * @return Response
     */
    public function create()
    {
        $hash = Input::get('hash');
        return View::make('type', array('hash' => $hash));
    }

    /**
     * Store a newly created resource in storage.
     * POST /types
     * @return Response
     */
    public function store() {
        $type = new Type;

        if(Request::get('name')) {
            $type->name = Request::get('name');
        }
        $type->save();
        return Response::json(array(
                'error' => false,
                'type' =>  $type->toArray()),
            200
        );

    }

    /**
     * Display the specified resource.
     * GET /types/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($type_id) {
        $type = Type::find($type_id);

        if ($type) {
            return Response::json(array(
                    'error' => false,
                    'type' =>  $type),
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
     * PUT /types/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($type_id) {
        $type = Type::find($type_id);

        if($type) {
            $type->name = Request::get('name');
            $type->save();
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
     * DELETE /types/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $type = Type::find($id);
        if ($type) {
            $type->delete();

            return Response::json(array(
                'error' => false,
                'message' => 'type deleted'), 200);
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
    public function restore($type_id) {
        $type = Type::withTrashed()->find($type_id);
        if($type) {
            $type->restore();
            return Response::json(array(
                'error' => false,
                'type' => $type->toArray()),
                200);
        } else {
            return Response::json(array(
                    'error' => false,
                    'type' => $type->toArray()),
                200);
        }

    }

    /**
     * Show achievements the specified type.
     * GET /types/{id}/achievements
     *
     * @param  int  $id
     * @return Response
     */
    public function getAchievements($type_id) {
        $type = Type::find($type_id);
        $result = array();

        if ($type) {
            $achievements = $type -> achievements;

            return Response::json(array(
                    'error' => false,
                    'achievements' =>   $achievements->toArray()),
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

}