<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SportRequest;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\model\Sports;
use App\model\SportType;
use \Validator;
use App\model\Competetion;
class SportsController extends Controller
{

	/**	 
	 * //@ sportList() is listing the sports
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function sportList() {
		$user = Auth::user();
		if($user){
			$sportsList = Sports::all();
			$response = [
	            'status' => true,
	            'message' => 'Data Avialable',
	            'data' => $sportsList
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
	 * //@ sportListForHeader() is listing the sports For header(Website)
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function sportListForHeader() {
		$sportsList = Sports::where('is_sidebar' , '=' , 3 )
							->orWhere('is_sidebar' , '=' , 2 )
							->get();		
		$response = [
            'status' => true,
            'message' => 'Data Avialable',
            'data' => $sportsList
		];
		return response()->json($response); //@ sending response
	}

	/**	 
	 * //@ sportListForSidebar() is listing the sports For sidebar(Website)
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function sportListForSidebar() {
		$results = Sports::with(['competetion','competetion.match'])
							->where('is_sidebar', '=' , 1)
							->orWhere('is_sidebar' , '=' , 3)
							->get();						
		$response = [
            'status' => true,
            'message' => 'Data Avialable',
            'data' => $results
		];
		return response()->json($response); //@ sending response
	}


	/**  
     * //@ sportDetails() to getting the sport details
     * @access   public
     * @param    $_GET
     * @return   JSON
     * @version  v0.1
     * @author Abir Adak <abir10.wis@gmail.com>
    */
    public function sportDetails($id){
        $user = Auth::user();
        if($user){
            $sportData = Sports::where('id',$id)->get();
            $response = [
                'status' => true,
                'message' => 'Data Avialable',
                'data' => $sportData,
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
	 * //@ addSports() is adding the sports
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function addSports(SportRequest $request) {
		$user = Auth::user();
		if($user) {
			if(json_decode($request->image)) {
				$image = $this->fileUpload($request->image ,$request->image_name);
				$insert_array = [
					'is_sidebar' => $request->is_sidebar,
	    			'name'   => $request->name,
	    			'image'  => $image
	    		];
	    		Sports::create($insert_array);
	    		$response = [
		            'status' => true,
		            'message' => 'Sport Created Successfully',
				];
				return response()->json($response); //@ sending response
			} else {
				$insert_array = [
					'is_sidebar' => $request->is_sidebar,
	    			'name'   => $request->name
	    		];
	    		Sports::create($insert_array);
	    		$response = [
		            'status' => true,
		            'message' => 'Sport Created Successfully',
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
	 * //@ updateSport() is updating the sports
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function updateSport(Request $request, $id){
		$user = Auth::user();
		if($user) {
			$data = Sports::findOrFail($id);
			if(json_decode($request->image)) {
				$image = $this->fileUpload($request->image ,$request->image_name);
				$update_array = [
					$data->name = $request->name,
					$data->is_sidebar = $request->is_sidebar,
					$data->image = $image
				];
				$data->update($update_array);
				$response = [
		            'status' => true,
		            'message' => 'Sport Successfully Updated!',
		            'data' => $data
				];
				return response()->json($response); //@ sending response
			} else {
				$update_array = [
					$data->is_sidebar = $request->is_sidebar,
					$data->name = $request->name
				];
				$data->update($update_array);
				$response = [
		            'status' => true,
		            'message' => 'Sport Successfully Updated!',
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
	 * //@ deleteSport() is delete the sports
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/                                                                                    
	public function deleteSport($id) {
		$user = Auth::user();
		if($user){
			Sports::destroy($id);
			$response = [
	            'status' => true,
	            'message' => 'Sport Successfully Deleted',
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
	 * //@ changeStatus() is the method for change the sport status
	 * @access 	 public
	 * @param    $_POST
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function changeStatus(Request $request,$id){
		$user = Auth::user();
		if($user){
			$data = Sports::findOrFail($id);
			$data->active = $request->active;
			$data->save();
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
	 * //@ sportsType() is listing the sports_type Listing
	 * @access 	 public
	 * @param    $_GET
	 * @return	 JSON
	 * @version	 v0.1
	 * @author Abir Adak <abir10.wis@gmail.com>
	*/
	public function sportsType($id = null) {
		$data = Auth::user();
		if($data) {
			if($id) {
				$sport_type = SportType::where('sport_id' , $id)->get();
				$response = [
		            'status' => true,
		            'message' => 'Data Avialable',
		            'data' => $sport_type
		  		];
				return response()->json($response); //@ sending response

			} else {
				$sport_type = SportType::all();
				$response = [
		            'status' => true,
		            'message' => 'Data Avialable',
		            'data' => $sport_type
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
}
