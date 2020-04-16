<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamRequest;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\model\Team;
use \Validator;

class TeamController extends Controller
{
    /**	 
	 * //@ teamList() is listing the teams
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function teamList( $id = null ) {
		$user = Auth::user();
		if($user) {
			if($id) {
				$teamList = Team::with('sport')
							    ->where('sport_id', $id)
							    ->where('delete' , '=' , '0')
								->orderBy('id','DESC')	
								->get();
				$response = [
		            'status' => true,
		            'message' => 'Data Avialable',
		            'data' => $teamList
				];
				return response()->json($response); //@ sending response

			} else {
				$teamList = Team::with('sport')
								->where('delete' , '=' , '0')
								->orderBy('id','DESC')
								->get();
				$response = [
		            'status' => true,
		            'message' => 'Data Avialable',
		            'data' => $teamList
				];
				return response()->json($response); //@ sending response
			}	
		} else {
			$response = [
	            'status' => false,
	            'message' => 'You Dont Have Permission To do it!',
			];
			return response()->json($response); //@ sending response
		}
	}

    /**	 
	 * //@ addTeam() is adding the team
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function addTeam(TeamRequest $request) {
		$data = true;
		switch ($data) {
			case false:
				$response = [
	            'status' => false,
	            'message' => 'You Dont Have Permission To do it!'
				];
				return response()->json($response); //@ sending response
				break;
			case true:
				$teams = Team::where('selection_id' , $request->selection_id)
							   ->where('delete' , '=' , '0')	
							   ->get();
				switch (count($teams)) {
					case 0:
						switch ($request->image) {
							case null:
								$insert_array_team = [
									'selection_id' => $request->selection_id,
									'name' => $request->name,
									'sport_id' => $request->sport_id,
									'short_name' => $request->short_name,
									'sport_type' => $request->sport_type
								];
							    Team::create($insert_array_team);
								$response = [
					            'status' => true,
					            'message' => 'New Team Successfully Created',
								];
								return response()->json($response); //@ sending response
								break;
							
							default:
								$data = $this->fileUpload($request->image,$request->image_name);
								$insert_array_team = [
									'selection_id' => $request->selection_id,
									'name' => $request->name,
									'sport_id' => $request->sport_id,
									'short_name' => $request->short_name,
									'sport_type' => $request->sport_type,
									'image' => $data
								];
								Team::create($insert_array_team);
								$response = [
					            'status' => true,
					            'message' => 'New Team Successfully Created',
								];
								return response()->json($response); //@ sending response
								break;
						}
						break;
					default:
						$response = [
			            'status' => false,
			            'message' => 'This Team Already There!',
						];
						return response()->json($response); //@ sending response
						break;
				}
				break;	
			default:
				$response = [
			            'status' => false,
			            'message' => 'Bad Request',
					];
				return response()->json($response); //@ sending response
				break;
		}
	}

	/**	 
	 * //@ updateTeam() is updating the team
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function updateTeam(Request $request, $id) {
		$user = Auth::user();
		if($user) {
			if(json_decode($request->image)) {
				$data = Team::findOrFail($id);
				$image = $this->fileUpload($request->image ,$request->image_name);
				$update_array = [
					$data->name = $request->name,
					$data->short_name = $request->short_name,
					$data->sport_id = $request->sport_id,
					$data->image = $image
				];
				$data->update($update_array);
				$response = [
		            'status' => true,
		            'message' => 'Team Successfully Updated!',
		            'data' => $data
				];
				return response()->json($response); //@ sending response
			} else {
				$data = Team::findOrFail($id);
				$update_array = [
					$data->name = $request->name,
					$data->short_name = $request->short_name,
					$data->sport_id = $request->sport_id
				];
				$data->update($update_array);
				$response = [
		            'status' => true,
		            'message' => 'Team Successfully Updated!',
		            'data' => $data
				];
				return response()->json($response); //@ sending response
			}
		} else {
			$response = [
	            'status' => false,
	            'message' => 'You Dont Have Permission To do it!',
			];
			return response()->json($response); //@ sending response
		}
	}

	/**	 
	 * //@ advanceUpdateTeam() is updating the team in advance mode
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function advanceUpdateTeam(Request $request,$id){
		$user = Auth::user();
		if($user) {
			$data = Team::findOrFail($id);
			if(json_decode($request->image)) {
				$image = $this->fileUpload($request->image ,$request->image_name);
				$update_array = [
					$data->name = $request->name,
					$data->selection_id = $request->selection_id,
					$data->short_name = $request->short_name,
					$data->sport_id = $request->sport_id,
					$data->image = $image
				];
				$data->update($update_array);
				$response = [
		            'status' => true,
		            'message' => 'Team Successfully Updated!',
		            'data' => $data
				];
				return response()->json($response); //@ sending response

			} else {
				$update_array = [
					$data->name = $request->name,
					$data->short_name = $request->short_name,
					$data->sport_id = $request->sport_id,
					$data->selection_id = $request->selection_id,
				];
				$data->update($update_array);
				$response = [
		            'status' => true,
		            'message' => 'Team Successfully Updated!',
		            'data' => $data
				];
				return response()->json($response); //@ sending response
			} 
		} else {
			$response = [
	            'status' => false,
	            'message' => 'You Dont Have Permission To do it!',
			];
			return response()->json($response); //@ sending response
		}
	}


	/**	 
	 * //@ statusChangeTeam() is for changing the status for team
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function statusChangeTeam(Request $request ,$id){
		$user = Auth::user();
		if($user) {
			if($request->active == 1) {
				$data = Team::findOrFail($id);
				$data->update(['active' => $request->active]);
				$response = [
		            'status' => true,
		            'message' => 'Team Successfully Active',
				];
				return response()->json($response); //@ sending response
			} else {
				$data = Team::findOrFail($id);
				$data->update(['active' => $request->active]);
				$response = [
		            'status' => true,
		            'message' => 'Team Successfully Inactive',
				];
				return response()->json($response); //@ sending response
			}
		} else {

			$response = [
	            'status' => false,
	            'message' => 'You Dont Have Permission To do it!',
			];
			return response()->json($response); //@ sending response
		}
	}

	/**	 
	 * //@ deleteCompetetion() is deleting the competetion
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function deleteTeam($id){
		$user = Auth::user();
		if($user){
			$data = Team::findOrFail($id);
			$data->update(['delete' => 1]);
			$response = [
	            'status' => true,
	            'message' => 'Team Successfully Deleted',
			];
			return response()->json($response); //@ sending response
		} else {

			$response = [
	            'status' => false,
	            'message' => 'You Dont Have Permission To do it!',
			];
			return response()->json($response); //@ sending response
		}
	} 
}
