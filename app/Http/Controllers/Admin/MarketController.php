<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MarketRequest;
use App\model\Market;
use Auth;
use \Validator;

class MarketController extends Controller
{
    /**	 
	 * //@ addMarket() is for adding the market
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function addMarket(MarketRequest $request) {
		$data = true;
		if($data = true) {
			// $exist = Market::where('market_id', $request->market_id)
			// 			   ->where('name', $request->name)
		 //                   ->first();
		 //    print_r(count($exist));
		 //    exit();               
			if(count($exist) > 0) {
				$create = [];
				foreach ($request->data as $key => $value) {
					array_push($create, [
						'market_id' => $value['market_id'],
						'event_id' => $value['event_id'],
						'name' => $value['name'],
						'bet_min' => $value['min_bet'],
						'bet_max' => $value['max_bet'],
						'commission' => $value['commission'],
						'created_at' => \Carbon\Carbon::now(),
	                    'updated_at' => \Carbon\Carbon::now(),
					]);
				}
				Market::insert($create);
				$response = [
		            'status' => true,
		            'message' => 'Market Successfully Created'
				];
				return response()->json($response); //@ sending response
			} else {
				$response = [
	            'status' => false,
	            'message' => 'This Market is already there',
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
	 * //@ updateMarket() is for updating the market
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function updateMarket(Request $request , $id) {
		if($data = true) {
			$market = Market::findOrFail($id);
			$update = [
				$market->name = $request->name,
				$market->bet_min = $request->min_bet,
				$market->bet_max = $request->max_bet,
				$market->commission = $request->commission
			];
			$market->update($update);
			$response = [
	            'status' => true,
	            'message' => 'Market Successfully Updated!'
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
	 * //@ listMarket() is for listing the market
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function listMarket(){
		$data = true;
		if($data = true) {
			$market = Market::with('match')
							->where('delete' , '=' ,0)
							->orderBy('name' ,'DESC')
							->get();
			$response = [
				            'status' => true,
				            'message' => 'Data Avialable',
				            'data' => $market,
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
	 * //@ deleteMarket() is for deleting the market
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function deleteMarket($id) {
		$data = true;
		if($data = true) {
			$market = Market::findOrFail($id);
			$market->update(['delete' => 1]);
			$response = [
	            'status' => true,
	            'message' => 'Market Successfully Deleted',
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
				$data = Market::findOrFail($id);
				switch ($request->type) {
					case 'declared':
						switch ($data->completed) {
							case 1:
								$response = [
					            'status' => false,
					            'message' => 'The Market is Already Completed',
								];
								return response()->json($response); //@ sending response
								break;
							case 0:
								switch ($request->value) {
									case 0:
										$data->update(['declared' => $request->value]);
										$response = [
							            'status' => true,
							            'message' => 'The Market is Not Declare',
										];
										return response()->json($response); //@ sending response
										break;
									case 1:
										$data->update(['declared' => $request->value]);
										$response = [
							            'status' => true,
							            'message' => 'The Market is Declare',
										];
										return response()->json($response); //@ sending response
										break;
								}
								break;
						}
						break;
					case 'commission':
						switch ($data->completed) {
							case 1:
								$response = [
					            'status' => false,
					            'message' => 'The Market is already Completed',
								];
								return response()->json($response); //@ sending response
								break;
							case 0:
								switch ($request->value) {
									case 0:
										$data->update(['inplay' => $request->value]);
										$response = [
							            'status' => true,
							            'message' => 'This Market Do Not Have Any Commission',
										];
										return response()->json($response); //@ sending response
										break;
									case 1:
										$data->update(['inplay' => $request->value]);
										$response = [
							            'status' => true,
							            'message' => 'This Market Has A commission',
										];
										return response()->json($response); //@ sending response	
									    break;
								}
								break;
						}
						break;
				    case 'active':
						switch ($data->completed) {
							case 1:
								$response = [
					            'status' => false,
					            'message' => 'The Market is already Completed',
								];
								return response()->json($response); //@ sending response
								break;
							case 0:
								switch ($request->value) {
									case 0:
										$data->update(['active' => $request->value]);
										$response = [
							            'status' => true,
							            'message' => 'Market Inactive',
										];
										return response()->json($response); //@ sending response
										break;
									case 1:
										$data->update(['active' => $request->value]);
										$response = [
							            'status' => true,
							            'message' => 'Market Deactive',
										];
										return response()->json($response); //@ sending response	
									    break;
								}
								break;
						}
						break;		
					case 'completed':
						$data->update(['completed' => $request->value]);
						$response = [
					            'status' => true,
					            'message' => 'The Market Has Been Completed',
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
	 * //@ lockedMarket() is for locking  the market during the match
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function lockedMarket($id) {
		$data = true;
		if($data = true) {
			$market = Market::findOrFail($id);
			$market->update(['locked' => 1]);
			$response = [
	            'status' => true,
	            'message' => 'Market Successfully Locked',
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
