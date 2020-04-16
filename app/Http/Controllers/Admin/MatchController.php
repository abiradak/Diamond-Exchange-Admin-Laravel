<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MatchRequest;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\model\Match;
use App\model\Team;
use App\model\MatchTeam;
use App\model\SportType;
use \Validator;

class MatchController extends Controller
{
    /**	 
	 * //@ listMatch() is listing the match 
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
    public function ClientListMatch($id = null) {
    	if($id) {
    		$matchList = Match::with(['sport','teams','sporttype','competetion'])
						  ->where('delete' , '=' , '0')	
						  ->where('sport_id', $id)
				          ->orderBy('id', 'DESC')
				          ->get();
		    $response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $matchList,
			];
			return response()->json($response); //@ sending response		          
    	} else {
    		$matchList = Match::with(['sport','teams','sporttype','competetion'])
						  ->where('delete' , '=' , '0')	
				          ->orderBy('id', 'DESC')
				          ->get();
			$response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $matchList,
			];
			return response()->json($response); //@ sending response
    	}
    }

    /**	 
	 * //@ listMatch() is listing the match
	 * @access 	 public
	 * @method    $_GET 
	 * @param   Request $request
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
   //  public function listMatch(Request $request) {
   //      $querystring = trim($request->qstring,'"');
	  //   if($querystring == '') {
	  //   	$matchList = Match::with('sporttype')
	  //   					   ->get();
   //  		$response = [
	  //           'status' => true,
	  //           'message' => 'Data Avialable',
	  //           'data' => $matchList,
			// ];
			// return response()->json($response); //@ sending response
	  //   } else {
	  //   	$matchList = Match::with('sporttype')
			// 				   ->where('teams', 'LIKE', '%' . $querystring . '%')
			// 				   ->orWhereHas('sporttype',function($query) use ($querystring){
			// 				   		$query->where('name', 'LIKE', '%' . $querystring . '%');
			// 				   })
			// 				   ->get();
			// if(count($matchList) >0) {
			// 	$response = [
	  //           'status' => true,
	  //           'message' => 'Data Avialable',
	  //           'data' => $matchList,
			// 	];
			// 	return response()->json($response); //@ sending response
			// } else {
			// 	$response = [
	  //           'status' => false,
	  //           'message' => 'No Match Found!',
			// 	];
			// 	return response()->json($response); //@ sending response
			// }				   	
	  //   }
   //  }

    public function listMatch(Request $request , $id = null){
    	// $currentAction = \Route::currentRouteAction();
     //    list($controller, $method) = explode('@', $currentAction);
     //    $controller = preg_replace('/.*\\\/', '', $controller);
     //    $method = preg_replace('/.*\\\/', '', $method);
     //    $data = $this->checkPermission($controller, $method);
   		$data = true;
   		switch ($data) {
   			case false:
   				$response = [
	            'status' => false,
	            'message' => 'You Dont Have Permission To do it!',
				];
				return response()->json($response); //@ sending response
   				break;
   			case true:
   				switch ($id) {
   					case null:
   						$matchList = Match::with(['sport','teams','sporttype','competetion'])
   										  ->where('delete' , '=' , '0')	
   			    				          ->orderBy('id', 'DESC')
   			    				          ->get();
   			    	    $response = [
			            'status' => true,
			            'message' => 'Data Avialable',
			            'data' => $matchList,
						];
						return response()->json($response); //@ sending response			          
   						break;
   					default:
   						$matchList = Match::with(['sport','teams','sporttype','competetion'])
   										  ->where('sport_id', $id)
   										  ->where('delete' , '=' , '0')
   			    				          ->orderBy('id', 'DESC')
   			    				          ->get();
   			    	    $response = [
			            'status' => true,
			            'message' => 'Data Avialable',
			            'data' => $matchList,
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
	 * //@ listMatchForCricket() is listing the match For cricket
	 * @access 	 public
	 * @param    Request $request
	 * @method  $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
    public function listMatchForCricket(Request $request) {

		$querystring = 	trim($request->qstring,'"');
		if($querystring == '') {
			$matchList = Match::with('sporttype')
						->where('sport_id' , '=' , '4')
						->get();
    		$response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $matchList,
			];
		    return response()->json($response); //@ sending response
		} else {
			$matchList = Match::with('sporttype')
							   ->where('sport_id' , '=' , '4')
							   ->where('teams', 'LIKE', '%' . $querystring . '%')
							   ->orWhereHas('sporttype',function($query) use ($querystring){
							   		$query->where('name', 'LIKE', '%' . $querystring . '%');
							   })
							   ->get();

			if(count($matchList) >0) {
				$response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $matchList,
				];
				return response()->json($response); //@ sending response
			} else {
				$response = [
	            'status' => false,
	            'message' => 'No Match Found!',
				];
				return response()->json($response); //@ sending response
			}							   
		}
    }

    /**	 
	 * //@ listMatchForFootball() is listing the match For Football
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
    public function listMatchForFootball(Request $request){
 
    	$querystring = 	trim($request->qstring,'"');
    	
		if($querystring == '') {
    		$matchList = Match::with('sporttype')
    							->where('sport_id' , '=' , '1')
    							->get();
    		$response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $matchList,
			];
			return response()->json($response); //@ sending response
		} else {
			$matchList = Match::with('sporttype')
							   ->where('sport_id' , '=' , '1')	
							   ->where('teams', 'LIKE', '%' . $querystring . '%')
							   ->orWhereHas('sporttype',function($query) use ($querystring){
							   		$query->where('name', 'LIKE', '%' . $querystring . '%');
							   })
							   ->get();
			if(count($matchList) >0) {
				$response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $matchList,
				];
				return response()->json($response); //@ sending response
			} else {
				$response = [
	            'status' => false,
	            'message' => 'No Match Found!',
				];
				return response()->json($response); //@ sending response
			}							   
		}	
    }

    /**	 
	 * //@ listMatchForTennis() is listing the match For Tennis
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
    public function listMatchForTennis(Request $request){
    	$querystring = 	trim($request->qstring,'"');
    	
		if($querystring == '') {
    		$matchList = Match::with('sporttype')
    							->where('sport_id' , '=' , '2')
    							->get();
    		$response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $matchList,
			];
			return response()->json($response); //@ sending response
		} else {
			$matchList = Match::with('sporttype')
							   ->where('sport_id' , '=' , '2')	
							   ->where('teams', 'LIKE', '%' . $querystring . '%')
							   ->orWhereHas('sporttype',function($query) use ($querystring){
							   		$query->where('name', 'LIKE', '%' . $querystring . '%');
							   })
							   ->get();
			if(count($matchList) >0) {
				$response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $matchList,
				];
				return response()->json($response); //@ sending response
			} else {
				$response = [
	            'status' => false,
	            'message' => 'No Match Found!',
				];
				return response()->json($response); //@ sending response
			}
		}	
    }

    /**	 
	 * //@ addMatch() is for adding the match manually
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function addMatch(MatchRequest $request) {
		// $currentAction = \Route::currentRouteAction();
  //       list($controller, $method) = explode('@', $currentAction);
  //       $controller = preg_replace('/.*\\\/', '', $controller);
  //       $method = preg_replace('/.*\\\/', '', $method);
  //       $data = $this->checkPermission($controller, $method);
		$data = true;
		switch ($data) {
			case false:
				$response = [
	            'status' => false,
	            'message' => 'You Dont Have Permission To do it!',
				];
				return response()->json($response); //@ sending response
				break;
			case true:
				$match = Match::where('event_id', $request->event_id)
							  ->where('market_id', $request->market_id)
							  ->get();
				switch (count($match)) {
					case 0:
						$insert_array_match = [
		    			'market_id' => $request->market_id,
		    			'event_id' => $request->event_id,
		    			'sport_id' => $request->sport_id,
		    			'sport_type' => $request->sport_type,
		    			'competition_id' => $request->competition_id,
		    			'name' => $request->name,
		    			'shortname' => $request->shortname,
		    			'date' => $request->date
		    		];
		    		$data = Match::create($insert_array_match);
		    		$insert_array_pivot = [];
		    		foreach ($request->teams as $key => $value) {
		    			array_push($insert_array_pivot, [
		    			'event_id' => $data->event_id,
		    			'match_id' => $data->id,
		    			'team_id'  => $value,
		    			'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),
		    		    ]);
		    		}
		    		MatchTeam::insert($insert_array_pivot);
		    		$response = [
			            'status' => true,
			            'message' => 'Match Created Successfully',
					];
					return response()->json($response); //@ sending response
					break;
					default:
					$response = [
			            'status' => false,
			            'message' => 'This Match is Already There!',
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
	 * //@ listMatchForTennis() is listing the match For Tennis
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
    public function matchDetails($id){
    	$user = Auth::user();
    	if($user){
    		$matchList = Match::with('sporttype')
    							->where('id' , $id)
    							->get();
    		$response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $matchList,
			];
			return response()->json($response); //@ sending response
     	}
    }

    /**	 
	 * //@ updateMatch() to update the match
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function updateMatch(Request $request, $id) {
		$user = Auth::user();
		if($user) {
			$data = Match::findOrFail($id);
			$update_array = [
				$data->name = $request->name,
				$data->date = $request->date,
				$data->shortname = $request->shortname
			];
			$data->update($update_array);
			$insert_array_pivot = [];
				foreach ($request->teams as $key => $value) {
					array_push($insert_array_pivot, [
		    			'event_id' => $data->event_id,
		    			'match_id' => $data->id,
		    			'team_id'  => $value,
		    			'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),
		    		]);
				}
				MatchTeam::where('match_id', $id)->delete();
				MatchTeam::insert($insert_array_pivot);
			$response = [
	            'status' => true,
	            'message' => 'Match Successfully Updated!'
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

	/**	 
	 * //@ advanceUpdateMatch() to update the match in advance mode
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function advanceUpdateMatch(Request $request, $id) {
		$user = Auth::user();
		if($user) {
			$exist = Match::where('delete', '=' , 0)
						  ->where('market_id',$request->market_id)
						  ->where('event_id', $request->event_id)
						  ->get();
			if(count($exist) > 0) {
				$response = [
	            'status' => false,
	            'message' => 'This Match is already There!'
				];
				return response()->json($response); //@ sending response
			} else {
				$data = Match::findOrFail($id);
				$update_array = [
					$data->market_id = $request->market_id,
					$data->competition_id = $request->competition_id,
					$data->event_id = $request->event_id,
					$data->sport_id = $request->sport_id,
					$data->sport_type = $request->sport_type,
					$data->name = $request->name,
					$data->shortname = $request->shortname,
					$data->date = $request->date,
				];
				$data->update($update_array);
				$insert_array_pivot = [];
				foreach ($request->teams as $key => $value) {
					array_push($insert_array_pivot, [
		    			'event_id' => $data->event_id,
		    			'match_id' => $data->id,
		    			'team_id'  => $value,
		    			'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),
		    		]);
				}
				MatchTeam::where('match_id', $id)->delete();
				MatchTeam::insert($insert_array_pivot);
				$response = [
		            'status' => true,
		            'message' => 'Match Successfully Updated!'
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
	 * //@ changeStatusForAll() is for changing the status
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function changeStatusForAll(Request $request , $id) {
		// $currentAction = \Route::currentRouteAction();
  //       list($controller, $method) = explode('@', $currentAction);
  //       $controller = preg_replace('/.*\\\/', '', $controller);
  //       $method = preg_replace('/.*\\\/', '', $method);
  //       $data = $this->checkPermission($controller, $method);
	    $data = true;
		switch ($data) {
			case false:
				$response = [
	            'status' => false,
	            'message' => 'You Dont Have Permission To do it!',
				];
				return response()->json($response); //@ sending response
				break;
			case true:
				$data = Match::findOrFail($id);
				switch ($request->type) {
					case 'active':
						switch ($data->complete) {
							case 1:
								$response = [
					            'status' => false,
					            'message' => 'The Match is already Completed',
								];
								return response()->json($response); //@ sending response
								break;
							case 0:
								switch ($request->value) {
									case 0:
										$data->update(['active' => $request->value]);
										$response = [
							            'status' => true,
							            'message' => 'The Match is Inactive Now!',
										];
										return response()->json($response); //@ sending response
										break;
									case 1:
										$data->update(['active' => $request->value]);
										$response = [
							            'status' => true,
							            'message' => 'The Match is Active Now',
										];
										return response()->json($response); //@ sending response
										break;
								}
								break;
						}
						break;
					case 'inplay':
						switch ($data->complete) {
							case 1:
								$response = [
					            'status' => false,
					            'message' => 'The Match is already Completed',
								];
								return response()->json($response); //@ sending response
								break;
							case 0:
								switch ($request->value) {
									case 0:
										$data->update(['inplay' => $request->value]);
										$response = [
							            'status' => true,
							            'message' => 'The Match is End Now',
										];
										return response()->json($response); //@ sending response
										break;
									case 1:
										$data->update(['inplay' => $request->value]);
										$response = [
							            'status' => true,
							            'message' => 'The Match is Playing Now',
										];
										return response()->json($response); //@ sending response	
									    break;
								}
								break;
						}
						break;
					case 'complete':
						$data->update(['complete' => $request->value]);
								$response = [
					            'status' => true,
					            'message' => 'The Match Has Been Completed',
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
	 * //@ deleteMatch() is deleting the match
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function deleteMatch($id) {
		$data = Auth::user();
		if($data){
			$match = Match::findOrFail($id);
			$match->update(['delete' => 1]);
			$response = [
	            'status' => true,
	            'message' => 'Match Successfully Deleted',
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
