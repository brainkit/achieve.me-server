<?php

class AchievementProofController extends \BaseController {

	/**
	 * Display a listing of the proofs for specified achievement.
	 * GET /achievement-proofs?achievement=%achievement_id%&hash=%user_hash%
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

		if (Request::get('achievement')) {
			$achievement_id = Request::get('achievement');
			$proofs = AchievementProof::where("achievement_id", $achievement_id)->paginate($per_page);
			return Response::json(array($proofs->toJson()), 200);
		} else {
			return Response::json(array(
				'error' => true,
				'message' => 'achievement_id not specified'), 404);
		}
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
	 * POST /achievement-proofs
	 * @return Response
	 */
	public function store()
	{
		$proof = new AchievementProof;
		if(Request::get('achievement_id')) {
			$proof->achievement_id = Request::get('achievement_id');

			if (Request::get('image')) {
				$proof->image = $this->upload_image($proof->achievement_id, $proof->id);
			}
			if(Request::get('description')) {
				$proof->description = Request::get('description');
			}
			$proof->save();
			return Response::json(array(
				'error' => false,
				'proof' => $proof),
				200
			);
		} else {
			return Response::json(array(
				'error' => true,
				'message' => "achievement id not specified"),
				200
			);
		}

	}


	/**
	 * Display the specified resource.
	 *GET /achievement-proofs/{id}
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$achieve_proof = AchievementProof::find($id);

		if ($achieve_proof) {
			return Response::json(array(
				'error' => false,
				'achievement_proof' =>  $achieve_proof),
				200
			);
		}else {
			return Response::json(array(
				'error' => true,
				'message' =>  "proof not found"),
				200
			);
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

	}


	/**
	 * Update the specified resource in storage.
	 * PUT/PATCH /achievement-proofs/{id}
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$proof = AchievementProof::find($id);
		if($proof) {
			if(Request::get('achievement_id')) {
				$proof->achievement_id = Request::get('achievement_id');
			}
			if (Request::get('image')) {
				$proof->image = $this->upload_image($proof->achievement_id, $proof->id);
			}
			if(Request::get('description')) {
				$proof->description = Request::get('description');
			}
			$proof->save();
			return Response::json(array(
				'error' => false,
				'message' => 'proof updated'),
				200
			);
		} else {
			return Response::json(array(
				'error' => true,
				'message' => 'proof not found'), 404);
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$achieve_proof = AchievementProof::find($id);

		if ($achieve_proof) {
			$achieve_proof->delete();
			return Response::json(array(
				'error' => false,
				'achievement_proof' =>  $achieve_proof),
				200
			);
		}else {
			return Response::json(array(
				'error' => true,
				'message' =>  "proof not found"),
				200
			);
		}
	}

	/**
	 * Restore the specified resource from trash
	 * @param int $id
	 * @return Response
	 */
	public function restore ($id) {
		$proof = AchievementVoice::withTrashed()->find($id);
		if ($proof) {
			$proof->restore();
			return Response::json(array(
				'error' => false,
				'proof' =>  $proof),
				200
			);
		} else {
			return Response::json(array(
				'error' => true,
				'message' => 'proof not found'),
				200
			);
		}
	}

	private function upload_image($achievement_id, $id) {
		$file = Input::file('image'); // your file upload input field in the form should be named 'file'
		$destinationPath = 'uploads/achievements/'.$achievement_id. '/proofs/';
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
