<?php

namespace App\Http\Controllers;

use Auth;
use App\model\Action;
use App\model\Method;
use App\model\Controllers;
use App\model\UserAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkPermission($controller, $method) {
    	try {
    		$data = Auth::user();
            $action = UserAction::where('role_id', $data->role)
                            ->whereHas('controller', function($query) use ($controller) {
                            $query->where('controllers.controller', $controller);
                        })
                            ->whereHas('methods', function($query) use ($method)  {
                            $query->where('methods.method', $method);
                        })->get();
    	   	$count = count($action);
    	  	if($count > 0) {
    	  		return true;
    	  	} else {
    	  		return false;
    	  	}
    	} catch (Exception $e) {
    		
    	}
    }
    
    /**  
     * //@ fileUpload() to upload the Picture 
     * @access   public
     * @param    $_GET 
     * @return   JSON
     * @version  v0.1
     * @author Abir Adak <abir10.wis@gmail.com>
    */
    public function fileUpload($data, $name) {
       $destinationPath = 'images/'; // upload path
       $profilefile = $name . '.' . $data->getClientOriginalExtension();
       $data->move($destinationPath, $profilefile);
       return $profilefile;
    }
}
