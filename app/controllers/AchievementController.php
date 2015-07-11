<?php

/**
 * Created by PhpStorm.
 * User: Julia
 * Date: 26.08.14
 * Time: 1:50
 */
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Contracts\JsonableInterface;

class AchievementController extends \BaseController {

    /**
     * public route
     * Display a listing of the resource.
     * GET /achievements?limit=%num_pages_to_show%&page=%age_number%&hash=%user_hash%
     *
     * @return Response
     */
    public function index() {
        $per_page = 100; // limit
        $current_page = 1; // page
        if (Request::get('page')) {
            $current_page = Request::get('page');
        };
        if (Request::get('limit')) {
            $per_page = Request::get('limit');
        };
        $achievements = Achievement::paginate($per_page);

        /* $response = [
          'achievements'   => $achievements->getItems(),
          'pagination' => [
          'total'        => $achievements->getTotal(),
          'per_page'     => $achievements->getPerPage(),
          'current_page' => $achievements->getCurrentPage(),
          'last_page'    => $achievements->getLastPage(),
          'from'         => $achievements->getFrom(),
          'to'           => $achievements->getTo()
          ]
          ];

          return Response::json(array(
          'error' => false,
          'data' => $response,
          200
          )); */

        //return Response::json(array($achievements->toJson()), 200);
        return $achievements->toJson();
    }

    /**
     * Store a newly created resource in storage.
     * POST /achievements
     * @return Response
     */
    public function store() {
        $default_type = 1;
        $default_rate = 0;
        $default_time_limit = time() + (24 * 36000); // Сейчас + 24 часа

        $achievement = new Achievement;
        //$achievement->type_id = $default_type;
        $achievement->rate = $default_rate;
        $achievement->time_limit = $default_time_limit;
        //if( isset($_POST['parent_id'])) {
        if (Request::get('parent_id')) {
            $achievement->parent_id = Request::get('parent_id');
        }
        /* if (Request::get('type_id')){
          $achievement->type_id = Request::get('type_id');
          } */
        if (Request::get('rate')) {
            $achievement->rate = Request::get('rate');
        }
        if (Request::get('image')) {
            $achievement->image = $this->upload_image($achievement->id);
        }
        if (Request::get('time_limit')) {
            $achievement->time_limit = Request::get('time_limit');
        }
        $achievement->title = Request::get('title');
        $achievement->description = Request::get('description');

        // Validation and Filtering is sorely needed!!
        // Seriously, I'm a bad person for leaving that out.

        $achievement->save();

        return Response::json(array(
                    'error' => false,
                    'achievement' => $achievement), 200
        );
    }

    /**
     * public route
     * Display the specified resource.
     * GET /achievements/{achievement_id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($achievement_id) {
        $achievement = Achievement::find($achievement_id);

        if ($achievement) {
            return Response::json(array(
                        'error' => false,
                        'achievement' => $achievement), 200
            );
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => 'achievement not found'), 200
            );
        }
    }

    /**
     * Update the specified resource in storage.
     * POST /achievements/{achievement_id}/update
     *
     * @param  int  $id
     * @return Response
     */
    public function update($achievement_id) {
        $achievement = Achievement::find($achievement_id);

        if ($achievement) {
            if (Request::get('parent_id')) {
                $achievement->parent_id = Request::get('parent_id');
            }
            /* if (Request::get('type_id')){
              $achievement->type = Request::get('type_id');
              } */
            if (Request::get('rate')) {
                $achievement->rate = Request::get('rate');
            }
            if (Request::get('image')) {
                $achievement->image = $this->upload_image($achievement->id);
            }
            if (Request::get('title')) {
                $achievement->title = Request::get('title');
            }
            if (Request::get('description')) {
                $achievement->description = Request::get('description');
            }
            if (Request::get('time_limit')) {
                $achievement->time_limit = Request::get('time_limit');
            }
            $achievement->save();

            return Response::json(array(
                        'error' => false,
                        'message' => 'achievement updated'), 200
            );
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => 'achievement not found'), 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /achievement/{achievement_id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($achievement_id) {
        $achievement = Achievement::find($achievement_id);
        if ($achievement) {
            $achievement->delete();

            return Response::json(array(
                        'error' => false,
                        'message' => 'achievement deleted'), 200);
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => 'achievement not found'), 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * GET /achievements/{achievement_id}/edit?hash=hash_value
     *
     * @param  int  $hash
     * @return Response
     */
    public function edit($achievement_id) {
        $hash = Input::get('hash');
        return View::make('achievement', array('hash' => $hash, 'id' => $achievement_id));
    }

    /**
     * Show the form for creating a new resource.
     * GET /achievements/create?hash=hash_value
     *
     * @return Response
     */
    public function create() {
        $hash = Input::get('hash');
        return View::make('achievement', array('hash' => $hash));
    }

    /**
     * Show comments for this achievement
     * GET /achievements/{achievement_id}/comments?limit=%num_pages_to_show%&page=%age_number%&hash=%user_hash%
     *
     * @return Response
     */
    public function getComments($achievement_id) {
        //$id = Request::get('id');
        $per_page = 10; // limit
        $current_page = 1; // page
        if (Request::get('page')) {
            $current_page = Request::get('page');
        };
        if (Request::get('limit')) {
            $per_page = Request::get('limit');
        };
        $achievement = Achievement::find($achievement_id);
        if ($achievement) {

            $comments = Comment::where("achievement_id", $achievement_id)->paginate($per_page);
            return Response::json(array($comments->toJson()), 200);
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => 'achievement not found'), 404);
        }
    }

    /**
     * Show child achievements for this achievement
     * GET /achievements/{achievement_id}/nodes?limit=%num_pages_to_show%&page=%age_number%&hash=%user_hash%
     *
     * @return Response
     */
    public function getNodes($achievement_id) {
        //$id = Request::get('id');
        $per_page = 10; // limit
        $current_page = 1; // page
        if (Request::get('page')) {
            $current_page = Request::get('page');
        };
        if (Request::get('limit')) {
            $per_page = Request::get('limit');
        };
        $achievement = Achievement::find($achievement_id);
        if ($achievement) {
            $nodes = Achievement::where("parent_id", $achievement_id)->paginate($per_page);
            return Response::json(array($nodes->toJson()), 200);
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => 'achievement not found'), 404);
        }
    }

    /**
     * Show parent achievement for specified achievement
     * GET /achievements/{achievement_id}/parent
     * @return Response
     */
    public function getParent($achievement_id) {
        //$id = Request::get('id');
        $achievement = Achievement::find($achievement_id);
        if ($achievement) {
            $parent = Achievement::where("id", $achievement->parent_id)->get();
            return Response::json(array(
                        'error' => false,
                        'achievement' => $parent), 200);
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => 'achievement not found'), 404);
        }
    }

    /**
     * Show achievement voices
     * GET /achievements/{achievement_id}/voices?limit=%num_pages_to_show%&page=%age_number%&hash=%user_hash%
     *
     * @return Response
     */
    public function getVoices($achievement_id) {
        $per_page = 10; // limit
        $current_page = 1; // page
        if (Request::get('page')) {
            $current_page = Request::get('page');
        };
        if (Request::get('limit')) {
            $per_page = Request::get('limit');
        };
        $achievement = Achievement::find($achievement_id);
        if ($achievement) {

            $voices = Voice::where("achievement_id", $achievement_id)->paginate($per_page);
            return Response::json(array($voices->toJson()), 200);
        } else {
            return Response::json(array(
                'error' => true,
                'message' => 'achievement not found'), 404);
        }
    }

    /**
     * Restore the specified resource from trash
     * @param int $id
     * @return Response
     */
    public function restore($achievement_id) {
        $achievement = Achievement::withTrashed()->find($achievement_id);
        if ($achievement) {
            $achievement->restore();
            return Response::json(array(
                        'error' => false,
                        'achievement' => $achievement), 200
            );
        } else {
            return Response::json(array(
                        'error' => true,
                        'message' => 'achievement not found'), 200
            );
        }
    }

    /**
     * Get achievements by name
     * GET /achievements/search/{value}
     *
     * @return Response
     */
    public function search($value) {
        $achievements = Achievement::where('title', 'like', "%".$value."%")->get();
        return Response::json(array($achievements), 200);
    }

    private function upload_image($id) {
        $file = Input::file('image'); // your file upload input field in the form should be named 'file'
        $destinationPath = 'uploads/achievements/';
        $filename = $id . ".jpg";
        $uploadSuccess = Input::file('image')->move($destinationPath, $filename);
        //print_r($uploadSuccess);
        if ($uploadSuccess) {
            return "/" . $destinationPath . $filename; // or do a redirect with some message that file was uploaded
        } else {
            return Response::json('error', 400);
        } /* */
    }

}
