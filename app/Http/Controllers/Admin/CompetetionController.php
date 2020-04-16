<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompetetionRequest;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\model\Competetion;
use \Validator;
use Storage;

class CompetetionController extends Controller
{
    /**	 
	 * //@ competetionList() is listing the competetion 
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	
	public function competetionList($id = null , $sport_type = null) {
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
							switch ($sport_type) {
								case null:
									$competetionList = Competetion::with(['sports','sporttype'])
																   ->where('delete' , '=' , 0)		
																   ->orderBy('name')
																   ->get();	
									$response = [
							            'status' => true,
							            'message' => 'Data Avialable',
							            'data' => $competetionList
									];
									return response()->json($response); //@ sending response
									break;
							}
							break;
						default:
							switch ($sport_type) {
								case null:
									$competetion = Competetion::with(['sports','sporttype'])
																->where('delete' , '=' , 0)
																->where('sport_id' , $id)
															 	->orderBy('name')
											                    ->get();
									$response = [
							            'status' => true,
							            'message' => 'Data Avialable',
							            'data'  => $competetion
									];
									return response()->json($response); //@ sending response
									break;
								
								default:
									$competetion = Competetion::with(['sports','sporttype'])
															  ->where('delete' , '=' , 0)	
															  ->where('sport_id' , $id)
											                  ->where('sport_type',$sport_type)
											                  ->orderBy('name')
											                  ->get();
									$response = [
							            'status' => true,
							            'message' => 'Data Avialable',
							            'data'  => $competetion
									];
									return response()->json($response); //@ sending response
									break;
							}				
							break;
					}	
			
			default:
				$response = [
			            'status' => false,
			            'message' => 'Bad Request'
					];
				return response()->json($response); //@ sending response
				break;
		}
	}

	/**  
     * //@ competetionDetails() to getting the cpmpetetion details
     * @access   public
     * @param    $_GET
     * @return   JSON
     * @version  v0.1
     * @author Abir Adak <abir10.wis@gmail.com>
    */
    public function competetionDetails($id){
        $user = Auth::user();
        if($user){
            $sportData = Competetion::where('id',$id)->get();
            $response = [
                'status' => true,
                'message' => 'Data Avialable',
                'data' => $sportData,
            ];
            return response()->json($response); //@ sending response
            
        }else{

            $response = [
                    'status' => false,
                    'message' => 'You Dont Have Permission To do it!'
                ];
            return response()->json($response); //@ sending response
        }
    }

    /**	 
	 * //@ addCompetetion() is adding the competetion (Not Using)
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function addCompetetion(CompetetionRequest $request){
		$user = Auth::user();
		if($user) {
			$exist = Competetion::where('event_id',$request->event_id)
								  ->where('delete' , '=' , 0)
								  ->get();
			if(count($exist) > 0) {
				$response = [
	            'status' => false,
	            'message' => 'This Competetion Is Already There!',
				];
				return response()->json($response); //@ sending response
			} else {
				if($request->image) {
					$data = $this->fileUpload($request->image , $request->image_name);
					$insert_array = [
					'sport_id' => $request->sport_id,
					'sport_type' => $request->sport_type,
					'event_id' => $request->event_id,
	    			'name' => $request->name,
	    			'image' => $data
		    		];
		    		Competetion::create($insert_array);
		    		$response = [
		            'status' => true,
		            'message' => 'Competetion Created Successfully',
					];
					return response()->json($response); //@ sending response
				} else {
					$insert_array = [
					'sport_id' => $request->sport_id,
					'sport_type' => $request->sport_type,
					'event_id' => $request->event_id,
	    			'name' => $request->name
		    		];
		    		Competetion::create($insert_array);
		    		$response = [
		            'status' => true,
		            'message' => 'Competetion Created Successfully',
					];
					return response()->json($response); //@ sending response
				}
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
	 * //@ updateCompetetion() is updating the competetion
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function updateCompetetion(Request $request,$id){
		$user = Auth::user();
		if($user) {
			$data = Competetion::findOrFail($id);
			if(json_decode($request->image)) {
				$image = $this->fileUpload($request->image , $request->image_name);
				$update_array = [
				   $data->name = $request->name,
				   $data->image = $image
			    ];
			    $data->update($update_array);
			    $response = [
	            'status' => true,
	            'message' => 'Competetion Successfully Updated!',
	            'data' => $data
				];
				return response()->json($response); //@ sending response
			} else {
				$update_array = [
				   $data->name = $request->name
			    ];
			    $data->update($update_array);
			    $response = [
	            'status' => true,
	            'message' => 'Competetion Successfully Updated!',
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
	 * //@ advanceUpdateCompetetion() is updating the competetion in advance
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function advanceUpdateCompetetion(Request $request,$id){
		$user = Auth::user();
		if($user) {
			$exist = Competetion::where('delete' , '=' , 0)
								->where('event_id',$request->shortname)
								->get();
			if(count($exist) > 0) {
				$response = [
	            'status' => false,
	            'message' => 'This Competetion is already There!'
				];
				return response()->json($response); //@ sending response
			} else {
				$data = Competetion::findOrFail($id);
				if(json_decode($request->image)) {
					$image = $this->fileUpload($request->image , $request->image_name);
					$update_array = [
					   $data->event_id = $request->event_id,
					   $data->name = $request->name,
					   $data->sport_id = $request->sport_id,
					   $data->sport_type = $request->sport_type,
					   $data->image = $image
				    ];
				    $data->update($update_array);
				    $response = [
		            'status' => true,
		            'message' => 'Competetion Successfully Updated!',
		            'data' => $data
					];
					return response()->json($response); //@ sending response
				} else {
					$update_array = [
					   $data->event_id = $request->event_id,
					   $data->name = $request->name,
					   $data->sport_id = $request->sport_id,
					   $data->sport_type = $request->sport_type
				    ];
				    $data->update($update_array);
				    $response = [
		            'status' => true,
		            'message' => 'Competetion Successfully Updated!',
		            'data' => $data
					];
					return response()->json($response); //@ sending response
				}
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
	public function deleteCompetetion($id){
		$user = Auth::user();
		if($user) {
			$account = Competetion::findOrFail($id);
			$account->update(['delete' => 1]);		    
			$response = [
	            'status' => true,
	            'message' => 'Competetion Successfully Deleted',
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
	 * //@ statusChange() is for change the status
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function statusChange(Request $request,$id) {
		$data = Auth::user();
		if($data) {
			$competetion = Competetion::findOrFail($id);
			if($request->active == 0) {
				$competetion->update(['active' => $request->active]);
				$response = [
					'status' => true,
					'message' => 'Competetion is Inactive Now!'
				];
				return response()->json($response); //@sending response
			} else {
				$competetion->update(['active' => $request->active]);
				$response = [
					'status' => true,
					'message' => 'Competetion is Active Now'
				];
				return response()->json($response); //@sending response
			}
		} else {
			$response = [
	            'status' => false,
	            'message' => 'You Dont Have Permission To do it!',
			];
			return response()->json($response); //@ sending response
		}
	}
}
