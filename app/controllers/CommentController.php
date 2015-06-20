<?php
/**
 * Created by PhpStorm.
 * User: Julia
 * Date: 26.08.14
 * Time: 1:49
 */

class CommentController extends \BaseController
{


    /**
     * Display a listing of the resource.
     * GET /comments?limit=%%&page=%%&hash=%%
     *
     * @return Response
     */
    public function index()
    {
        $per_page = 10; // limit
        $current_page = 1; // page
        if (Request::get('page')) {
            $current_page = Request::get('page');
        };
        if (Request::get('limit')) {
            $per_page = Request::get('limit');
        };
        $comments = Comment::paginate($per_page);

        return Response::json(array($comments->toJson()),
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     * GET /comments/create
     *
     * @return Response
     */
    public function create()
    {
        $hash = Input::get('hash');
        $users = DB::table('users')->select('id', 'email')->get();
        $achievements = DB::table('achievements')->select('id','title')->get();
        return View::make('new_comment', array(
            'hash'=>$hash,
            'users' => $users,
            'achievements' => $achievements));
    }

    /**
     * Store a newly created resource in storage.
     * POST /comments
     *
     * @return Response
     */
    public function store()
    {
        $comment = new Comment();
        if (Request::get('user_id') && Request::get('achievement_id')) {
            $comment->user_id = Request::get('user_id');
            $comment->achievement_id = Request::get('achievement_id');
            $comment->text = Request::get('text');
            $comment->save();
            return Response::json(array(
                'error' =>false,
                'comment' => $comment), 200);
        } else {
            return Response::json(array(
                'error' =>true,
                'message' => "parameters missing"), 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /comments/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $comment = Comment::find($id);

        if($comment) {
            return Response::json(array(
                    'error' => false,
                    'comment' =>  $comment),
                200
            );
        } else {
            return Response::json(array(
                    'error' => true,
                    'message' => 'comment not found'),
                404
            );

        }
    }

    /**
     * Show the form for editing the specified resource.
     * GET /comments/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $hash = Input::get('hash');
        $comment = Comment::find($id);
        $users = DB::table('users')->select('id', 'email')->get();
        $achievements = DB::table('achievements')->select('id','title')->get();
        return View::make('comment', array(
            'hash'=>$hash,
            'id' => $id,
            'comment' => $comment,
            'users' => $users,
            'achievements' => $achievements));
    }

    /**
     * Update the specified resource in storage.
     * PUT /comments/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {

        //todo refactor
        $comment = Comment::find($id);
        if ($comment) {
            if(Request::get('text')) {
                $comment->text = Request::get('text');
            }
            if (Request::get('user_id')) {
                $comment->user_id = Request::get('user_id');
            }
            if (Request::get('achievement_id')) {
                $comment->achievement_id = Request::get('achievement_id');
            }
            $comment->save();
            return Response::json(array(
                'error' => false,
                'message' => 'updated'), 200);
        } else {
            return Response::json(array(
                    'error' => true,
                    'message' => 'comment not found'), 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Remove to trash with soft deletion
     * DELETE /comments/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->delete();
            return Response::json(array(
                'error' => false,
                'message' => 'deleted'), 200);
        } else {
            return Response::json(array(
                'error' => true,
                'message' => 'comment not found'), 404);
        }
    }

    /**
     * Restore the specified resource from trash
     * @param int $comment_id
     * @return Response
     */
    public function restore($comment_id) {
        $comment = Type::withTrashed()->find($comment_id);
        if($comment) {
            $comment->restore();
            return Response::json(array(
                    'error' => false,
                    'type' => $comment),
                200);
        } else {
            return Response::json(array(
                'error' => true,
                'message' => 'comment not found'), 404);
        }
    }
}