<?php

namespace App\Http\Controllers;
use Auth;
// use Illuminate\Http\Request;
use App\Http\Requests\ChangepassRequest;
use Illuminate\Support\Facades\Hash;
use App\User;

class ChangePassword extends Controller
{

    /**  
     * //@ changePassword() to change user password
     * @access   public
     * @param    $_POST
     * @return   JSON
     * @version  v0.1
     * @author name <email>
    */
    public function changePassword(ChangepassRequest $request){
    	
    	if(!empty($request->old_password) && !empty($request->new_password) && !empty($request->cnf_new_password)){
    		
    		if($request->new_password == $request->cnf_new_password){
    			$user = Auth::user();
    			$id = $user->id;
    			$old_password = $user->password;
    	        $match = Hash::check($request->old_password,$old_password); 
    	        if($match == true){
    	        	
    	        	$data = User::find($id);
		        	$data->password = $request->new_password;
		        	$data->orginal_password = $request->new_password;
		        	$data->save();
		        	$response = [
	                'status' => true,
	                'message' => 'Password Successfully Changed'
		            ];
		            
		            $response['password'] = $request->new_password;
		            return response()->json($response); //@ sending response

    	        }else{
    	        	$response = [
	                'status' => false,
	                'message' => 'Old Password Not Matching!'
		            ];
		            return response()->json($response); //@ sending response
    	        }

    		}else{
    			$response = [
                'status' => false,
                'message' => 'New Password Not Matching!'
	            ];
	            return response()->json($response); //@ sending response
    		}

    	}else{
    		$response = [
                'status' => false,
                'message' => 'Please Fillup The Fields!'
            ];
            return response()->json($response); //@ sending response
    	}
    }
}


