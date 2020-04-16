<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\model\Roles;
use App\model\Betusers;
use App\model\ClientMetaData;
use App\model\ClientComission;
use App\model\ClientBet;
use App\model\UserBet;
use App\model\UserComission;
use App\model\UserMetaData;
use App\model\UserPartneShip;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\FileRequest;
use App\Http\Requests\AddAccounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use \Validator;

class AdminController extends Controller
{

	/**	 
	 * //@ clientList() to render the list view for user
	 * @access 	 public
	 * @param    $_GET 
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function clientList($id) {
		//$user = Auth::user();
		$currentAction = \Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);
        $controller = preg_replace('/.*\\\/', '', $controller);
        $method = preg_replace('/.*\\\/', '', $method);
        $data = $this->checkPermission($controller, $method);
		if($data == true) {
			$userList = Betusers::with(['role','clientMetaData','clientBet','clientComission'])
								->where('deleted', '=', '0')
								->where('parent_id', $id)
						        ->orderBy('created_at', 'DESC')
						        ->get();
			$userList = json_decode($userList);
			foreach ($userList as $key => $value) {
				unset($value->username);
				unset($value->password);
				// unset($value->orginal_password);
			}
			$response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $userList,
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
	 * //@ clientList() to render the list view for user
	 * @access 	 public
	 * @param    $_GET 
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function userList($id) {
		// $user = Auth::user();
		$currentAction = \Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);
        $controller = preg_replace('/.*\\\/', '', $controller);
        $method = preg_replace('/.*\\\/', '', $method);
        $data = $this->checkPermission($controller, $method);
		if($data == true) {
			$userList = User::with(['role' ,'userMetaData','userBet','userComission','userPartnership'])
			                ->where('deleted', '=' , '0')
				            ->where('parent_id', $id)	
			                ->orderBy('created_at', 'DESC')
			                ->get();               
			$userList = json_decode($userList);
			foreach ($userList as $key => $value) {
				unset($value->username);
				unset($value->password);
				//unset($value->orginal_password);
			}
			$response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $userList,
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
	 * //@ userDetails() to getting the user details
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function userDetails($id) {
		$currentAction = \Route::currentRouteAction();
		list($controller, $method) = explode('@', $currentAction);
		$controller = preg_replace('/.*\\\/', '', $controller);
		$method = preg_replace('/.*\\\/', '', $method);
		$data = $this->checkPermission($controller, $method);
	    // $user = Auth::user();
		if($data == true) {
			$userData = User::with(['userMetaData','userBet','userComission','userPartnership'])->where('id',$id)              ->get();                
			$response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $userData,
			];
			return response()->json($response); //@ sending response
			
		} else {
			$response = [
	                'status' => false,
	                'message' => 'You Dont Have Permission To do it!'
	            ];
	        return response()->json($response); //@ sending response
		}
	}


    /**	 
	 * //@ updateClient() to update the clients
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
    public function updateClient(Request $request,$id) {
    	$currentAction = \Route::currentRouteAction();
		list($controller, $method) = explode('@', $currentAction);
		$controller = preg_replace('/.*\\\/', '', $controller);
		$method = preg_replace('/.*\\\/', '', $method);
		$data = $this->checkPermission($controller, $method);
    	if($data == true) {
    		$user = Auth::user();
    		if(Hash::check($request->master_password, $user->password) == true) {
    			if($request->password == $request->confirmpassword) {
    				$data = Betusers::with(['clientMetaData','clientBet','clientComission'])->findOrFail($id);
		    		$update_array1 = [
		    			$data->name = $request->name,
		    			$data->mobile = $request->mobile,
		    			$data->password = $request->password,
		    			$data->orginal_password = $request->password,
		    			$data['clientMetaData']->city = $request->city,
		    			$data['clientMetaData']->credit_reference = $request->credit_reference,
		    			$data['clientMetaData']->exposure_limit = $request->exposure_limit
		    		];
		    	    $data->push($update_array1);
		    	    $update_array2 = [];
			    	foreach ($request->bet_amount as $key => $value) {
		    	    	array_push($update_array2, [
				            'client_id'	=> $data->id,
				        	'sport_id'  => $value['id'],
				        	'min_bet' => $value['min'],
				        	'max_bet' => $value['max'],
				        	'delay'  => $value['delay'],
				        	'created_at' => \Carbon\Carbon::now(),
				        	'updated_at' => \Carbon\Carbon::now(),

				        ]);
			        }
			        $update_array3 = [];
			        foreach ($request->commission as $key => $value) {
			        	array_push($update_array3, [
				            'client_id'	=> $data->id,
				        	'sport_id'  => $value['id'],
				        	'commission' => $value['comm_value'],
				        	'created_at' => \Carbon\Carbon::now(),
				        	'updated_at' => \Carbon\Carbon::now(),
				        ]);
			        }
			        ClientBet::where('client_id', '=' , $id)->delete();
			        ClientBet::insert($update_array2);
			        ClientComission::where('client_id', '=' , $id)->delete();
			        ClientComission::insert($update_array3);
		    		$response = [
			            'status' => true,
			            'message' => 'User Successfully Updated!',
					];
					return response()->json($response); //@ sending response
    			} else {

    				$response = [
	                'status' => false,
	                'message' => 'Password Not Matching!'
		            ];
		            return response()->json($response); //@ sending response
    			}
    			
    		} else {
    			$response = [
	                'status' => false,
	                'message' => 'Wrong Master Password!'
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
	 * //@ updateUser() to update the users
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
    public function updateUser(Request $request,$id) {
    	$currentAction = \Route::currentRouteAction();
		list($controller, $method) = explode('@', $currentAction);
		$controller = preg_replace('/.*\\\/', '', $controller);
		$method = preg_replace('/.*\\\/', '', $method);
		$data = $this->checkPermission($controller, $method);
    	if($data == true) {
    		$user = Auth::user();
    		if(Hash::check($request->master_password, $user->password) == true) {
    			if($request->password == $request->confirmpassword) {
    				$data = User::with(['userMetaData','userBet','userComission','userPartnership'])->findOrFail($id);
		    		$update_array1 = [
		    			$data->name = $request->name,
		    			$data->mobile = $request->mobile,
		    			$data->password = $request->password,
		    			$data->orginal_password = $request->password,
		    			$data['userMetaData']->city = $request->city,
		    			$data['userMetaData']->credit_reference = $request->credit_reference,
		    			$data['userMetaData']->exposure_limit = $request->exposure_limit
		    		];
		    	    $data->push($update_array1);
		    	    $update_array2 = [];
				    
			    	foreach ($request->bet_amount as $key => $value) {
		    	    	array_push($update_array2, [
				            'user_id'	=> $data->id,
				        	'sport_id'  => $value['id'],
				        	'min_bet' => $value['min'],
				        	'max_bet' => $value['max'],
				        	'delay'  => $value['delay'],
				        	'created_at' => \Carbon\Carbon::now(),
				        	'updated_at' => \Carbon\Carbon::now(),

				        ]);
			        }
			        $update_array3 = [];
			        foreach ($request->commission as $key => $value) {
			        	array_push($update_array3, [
				            'user_id'	=> $data->id,
				        	'sport_id'  => $value['id'],
				        	'commission' => $value['comm_value'],
				        	'created_at' => \Carbon\Carbon::now(),
				        	'updated_at' => \Carbon\Carbon::now(),
				        ]);
			        }
			        $update_array4 = [];
			        foreach ($request->partnership as $key => $value) {
			        	array_push($update_array4, [
				            'user_id'	=> $data->id,
				        	'sport_id'  => $value['id'],
				        	'partnaship' => $value['part_value'],
				        	'created_at' => \Carbon\Carbon::now(),
                            'updated_at' => \Carbon\Carbon::now(),
				        ]);
			        }
			        UserBet::where('user_id', '=' , $id)->delete();
			        UserBet::insert($update_array2);
			        UserComission::where('user_id', '=' , $id)->delete();
			        UserComission::insert($update_array3);
			        UserPartneShip::where('user_id', '=' , $id)->delete();
			        UserPartneShip::insert($update_array4);
		    		$response = [
			            'status' => true,
			            'message' => 'User Successfully Updated!',
					];
					return response()->json($response); //@ sending response
    			} else {

    				$response = [
	                'status' => false,
	                'message' => 'Password Not Matching!'
		            ];
		            return response()->json($response); //@ sending response
    			}
    			
    		} else {
    			$response = [
	                'status' => false,
	                'message' => 'Wrong Master Password!'
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
	 * //@ deleteClient() to delete the client
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/

    public function deleteClient($id) {
    	// $user = Auth::user(); 
    	$currentAction = \Route::currentRouteAction();
		list($controller, $method) = explode('@', $currentAction);
		$controller = preg_replace('/.*\\\/', '', $controller);
		$method = preg_replace('/.*\\\/', '', $method);
		$data = $this->checkPermission($controller, $method);
    	if($data == true) {
    		$data = Betusers::with(['clientMetaData','clientBet','clientComission'])->findOrFail($id);
    		$delete_array = [
    			$data->deleted = 1
    		];
    		$data->push($delete_array);
    		$response = [
	            'status' => true,
	            'message' => 'User Successfully Deleted',
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
	 * //@ deleteUser() to delete the user
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/

    public function deleteUser($id) {
    	$currentAction = \Route::currentRouteAction();
		list($controller, $method) = explode('@', $currentAction);
		$controller = preg_replace('/.*\\\/', '', $controller);
		$method = preg_replace('/.*\\\/', '', $method);
		$data = $this->checkPermission($controller, $method);
    	if($data == true) {
    		$data = User::with(['userMetaData','userBet','userComission','userPartnership'])->findOrFail($id);
    		$delete_array = [
    			$data->deleted = 1
    		];
    		$data->push($delete_array);
    		$response = [
	            'status' => true,
	            'message' => 'User Successfully Deleted',
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
	 * //@ searchUser() is the method for global search(Not Used)
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function searchUser(Request $request){
		$currentAction = \Route::currentRouteAction();
		list($controller, $method) = explode('@', $currentAction);
		$controller = preg_replace('/.*\\\/', '', $controller);
		$method = preg_replace('/.*\\\/', '', $method);
		$data = $this->checkPermission($controller, $method);
		// $user = Auth::user();
		if($data == true){
			$data = $request->searchdata;
			if($data != ""){
				$searchData = User::where( 'name', 'LIKE', '%' . $data . '%' )
							        ->orWhere( 'mobile', 'LIKE', '%' . $data . '%' )
							        ->orWhere( 'username', 'LIKE', '%' . $data . '%')
							        ->get();		        
				if(count($searchData) >0){
					$response = [
		            'status' => true,
		            'message' => 'Data Avialable',
		            'data' => $searchData
				    ];
			        return response()->json($response); //@ sending response
				}else{
					$response = [
		            'status' => false,
		            'message' => 'No Details found. Try to search again !',
				    ];
				    return response()->json($response); //@ sending response
				}			 
			} else {
				$response = [
		            'status' => false,
		            'message' => 'No Details found. Try to search again !',
				    ];
				    return response()->json($response); //@ sending response
			}
		}else{
			$response = [
	            'status' => false,
	            'message' => 'You Dont Have Permission To do it!',
			];
			return response()->json($response); //@ sending response
		}
	}

	/**	 
	 * //@ changeStatusForClients() is the method for change the client status
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function changeStatusForClients(Request $request,$id) {
		// $user = Auth::user();
		$currentAction = \Route::currentRouteAction();
		list($controller, $method) = explode('@', $currentAction);
		$controller = preg_replace('/.*\\\/', '', $controller);
		$method = preg_replace('/.*\\\/', '', $method);
		$data = $this->checkPermission($controller, $method);
		if($data == true){
			$data = Betusers::with(['clientMetaData','clientBet','clientComission'])->findOrFail($id);
    		$status_array = [
    			$data->active = $request->active
    		];
    		$data->push($status_array);
			$response = [
		            'status' => true,
		            'message' => 'Status Successfully Updated',
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
	 * //@ changeStatusForUser() is the method for change the user status
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function changeStatusForUser(Request $request,$id) {
		$currentAction = \Route::currentRouteAction();
		list($controller, $method) = explode('@', $currentAction);
		$controller = preg_replace('/.*\\\/', '', $controller);
		$method = preg_replace('/.*\\\/', '', $method);
		$data = $this->checkPermission($controller, $method);
		// $user = Auth::user();
		if($data == true){
			$data = User::with(['userMetaData','userBet','userComission','userPartnership'])->findOrFail($id);
    		$status_array = [
    			$data->active = $request->active
    		];
    		$data->push($status_array);
			$response = [
		            'status' => true,
		            'message' => 'Status Successfully Updated',
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
	 * //@ changeMultiSignUser() is the method for change the user signin
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function changeMultiSignUser(Request $request,$id) {
		// $user = Auth::user();
		$currentAction = \Route::currentRouteAction();
		list($controller, $method) = explode('@', $currentAction);
		$controller = preg_replace('/.*\\\/', '', $controller);
		$method = preg_replace('/.*\\\/', '', $method);
		$data = $this->checkPermission($controller, $method);
		if($data == true){
			$data = User::findOrFail($id);
    		$status_array = [
    			$data->is_multisign = $request->is_multisign
    		];
    		$data->push($status_array);
			$response = [
		            'status' => true,
		            'message' => 'Multi SignIn Enebaled',
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
	 * //@ changeMultiSignUser() is the method for change the user signin
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function changeMultiSignClient(Request $request,$id) {
		$currentAction = \Route::currentRouteAction();
		list($controller, $method) = explode('@', $currentAction);
		$controller = preg_replace('/.*\\\/', '', $controller);
		$method = preg_replace('/.*\\\/', '', $method);
		$data = $this->checkPermission($controller, $method);
		if($data == true){
			$data = Betusers::findOrFail($id);
    		$status_array = [
    			$data->is_multisign = $request->is_multisign
    		];
    		$data->push($status_array);
			$response = [
		            'status' => true,
		            'message' => 'Multi SignIn Enebaled',
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
	 * //@ addAccount() is the method for change the user status
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function addAccount(UserStoreRequest $request) {
    	$currentAction = \Route::currentRouteAction();
		list($controller, $method) = explode('@', $currentAction);
		$controller = preg_replace('/.*\\\/', '', $controller);
		$method = preg_replace('/.*\\\/', '', $method);
		$data = $this->checkPermission($controller, $method);
    	if($data == true) {
    		$user = Auth::user();
    		if(Hash::check($request->master_password, $user->password) == true) {
				if($request->role == 5) {
				    return $this->addUserForBet($request , $user->id);
				} else {
					$users = User::where('username',$request->username)->first(); 
					if($users) {
						$response = [
							'status' => false,
							'message' => 'You Have Already An Account! please login!',
						];
						return response()->json($response); //@ sending response
					} else {
						if($request->password == $request->confirmpassword){
							$insert_array1 = [         //@ insert data array
								'parent_id'   => $user->id,
								'username'    => $request->username,
								'name'        => $request->name,
								'mobile'	  => $request->mobile,	
								'password'    => $request->password,
								'orginal_password'  => $request->password,
								'role'        => $request->role,
								'reference'   => $user->name
							];
							$data = User::create($insert_array1);
							if($data) {
								$insert_array2 = [
						    	'user_id'   => $data->id,
						    	'role'      => $data->role,
						    	'city'      => $request->city,
						    	'credit_reference' => $request->credit_reference,
						    	'exposure_limit' => $request->exposure_limit
						        ];
			                    $data = UserMetaData::create($insert_array2);
						        if($data) {
						        	$insert_array3 = [];
						        	$insert_array4 = [];
						        	$insert_array5 = [];
						        	foreach ($request->commission as $value) {
							        	array_push($insert_array3, [
								        	'user_id'	=> $data->user_id,
								        	'sport_id'  => $value['id'],
								        	'commission' => $value['comm_value'],
								        	'created_at' => \Carbon\Carbon::now(),
                                            'updated_at' => \Carbon\Carbon::now(),
							            ]);
							        }
							        UserComission::insert($insert_array3);
							        foreach ($request->bet_amount as $key => $value) {
							        	array_push($insert_array4, [
								        	'user_id'	=> $data->user_id,
								        	'sport_id'  => $value['id'],
								        	'min_bet' => $value['min'],
								        	'max_bet' => $value['max'],
								        	'delay'  => $value['delay'],
								        	'created_at' => \Carbon\Carbon::now(),
                                            'updated_at' => \Carbon\Carbon::now(),
							            ]);
							        }
							        UserBet::insert($insert_array4);
							        foreach ($request->partnership as $key => $value) {
							        	array_push($insert_array5, [
								        	'user_id'	=> $data->user_id,
								        	'sport_id'  => $value['id'],
								        	'partnaship' => $value['part_value'],
								        	'created_at' => \Carbon\Carbon::now(),
                                            'updated_at' => \Carbon\Carbon::now(),
							            ]);
							        }
							        UserPartneShip::insert($insert_array5);
						        }
								
								$response = [
									'status' => true,
									'message' => 'New User Successfully Created'
								];
								return response()->json($response); //@ sending response
							} 
						} else {

							$response = [
								'status' => false,
								'message' => 'Passwords Not Matching!',
							];
							return response()->json($response); //@ sending response
						}	
					}			
				}
			} else {
				$response = [
	                'status' => false,
	                'message' => 'Wrong Master Password!'
	            ];
	            return response()->json($response); //@ sending response
			}	
    	} else {
			$response = [
	                'status' => false,
	                'message' => 'You Dont Have Permission To do it!'
	            ];
	        return response()->json($response); //@ sending response
    	}
    }

    /**	 
	 * //@ addUserForBet() to adding new user for only frontend
	 * @access 	 private
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
    private function addUserForBet(UserStoreRequest $request , $user_id) {
    	$users = Betusers::where('username',$request->username)->first();
		if($users) {
    		$response = [
                'status' => false,
                'message' => 'This Account Already Exists! please login!',
	        ];
	        return response()->json($response); //@ sending response

		} else {
			if($request->password == $request->confirmpassword){
				$insert_array1 = [               //@ insert data array
					'parent_id'   => $user_id,
					'username'    => $request->username,
					'name'        => $request->name,
					'mobile'	  => $request->mobile,	
					'password'    => $request->password,
					'orginal_password'  => $request->password,
					'role'        => $request->role,
			    ];
			    $data = Betusers::create($insert_array1);
			   	if($data) {
			   		$insert_array2 = [
				    	'client_id'   => $data->id,
				    	'role'        => $data->role,
				    	'city'        => $request->city,
				    	'credit_reference' => $request->credit_reference,
				    	'exposure_limit' => $request->exposure_limit,
			        ];
			        $data = ClientMetaData::create($insert_array2);
			        if($data) {
			        	$insert_array3 = [];
			        	$insert_array4 = [];
			        	foreach ($request->commission as $value) {
				        	array_push($insert_array3, [
					        	'client_id'	=> $data->client_id,
					        	'sport_id'  => $value['id'],
					        	'commission' => $value['comm_value'],
					        	'created_at' => \Carbon\Carbon::now(),
                                'updated_at' => \Carbon\Carbon::now(),
				            ]);
				        }
				        ClientComission::insert($insert_array3);
				        foreach ($request->bet_amount as $key => $value) {
				        	array_push($insert_array4, [
					        	'client_id'	=> $data->client_id,
					        	'sport_id'  => $value['id'],
					        	'min_bet' => $value['min'],
					        	'max_bet' => $value['max'],
					        	'delay'  => $value['delay'],
					        	'created_at' => \Carbon\Carbon::now(),
                                'updated_at' => \Carbon\Carbon::now(),
				            ]);
				        }
				        ClientBet::insert($insert_array4);
			        }
			   	}
	    	    $response = [
	                'status' => true,
	                'message' => 'New User Successfully Created',
		        ];
		        return response()->json($response); //@ sending response
			} else {

				$response = [
	                'status' => false,
	                'message' => 'Passwords Not Matching!',
		        ];
		        return response()->json($response); //@ sending response
			}
    	}
    }
}



