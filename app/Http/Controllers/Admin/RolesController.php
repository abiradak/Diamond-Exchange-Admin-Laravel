<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RolesRequest;
use Auth;
use App\User;
use App\model\Roles;
use App\model\Controllers;
use App\model\UserAction;
use \Validator;
use Route;

class RolesController extends Controller
{

    /**  
     * //@ getRoles() to render the list view for roles
     * @access   public
     * @param    $_GET
     * @return   JSON
     * @version  v0.1
     * @author Abir Adak <abir10.wis@gmail.com>
    */
    public function getRoles() {
        $currentAction = \Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);
        $controller = preg_replace('/.*\\\/', '', $controller);
        $method = preg_replace('/.*\\\/', '', $method);
        $data = $this->checkPermission($controller, $method);
    	if($data == true) {
            $user = Auth::user();
            $role = $user->role;
    		$data = Roles::where('id' , '>' , $role)
                           ->where('is_deleted' , '=' , 0)
                           ->get();
                       
    		$response = [
	            'status' => true,
                'message' => 'Data Avialable',
	            'data' => $data
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
     * //@ rolesDetails() to getting the roles details
     * @access   public
     * @param    $_GET
     * @return   JSON
     * @version  v0.1
     * @author Abir Adak <abir10.wis@gmail.com>
    */
    public function rolesDetails($id){
        $currentAction = \Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);
        $controller = preg_replace('/.*\\\/', '', $controller);
        $method = preg_replace('/.*\\\/', '', $method);
        $data = $this->checkPermission($controller, $method);
        if($data) {
            $rolesData = Roles::findOrFail($id);
            $rolesData['actions'] = UserAction::with('controllers' , 'method')
                                                ->where('role_id', $id)
                                                ->get();
            $response = [
                'status' => true,
                'message' => 'Data Avialable',
                'data' => $rolesData,
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
     * //@ addRoles() to add the roles
     * @access   public
     * @param    $_POST
     * @return   JSON
     * @version  v0.1
     * @author Abir Adak <abir10.wis@gmail.com>
    */
    public function addRoles(RolesRequest $request){
        $currentAction = \Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);
        $controller = preg_replace('/.*\\\/', '', $controller);
        $method = preg_replace('/.*\\\/', '', $method);
        $data = $this->checkPermission($controller, $method);
    	if($data) {
    		$insert_array = [
    			'name' => $request->name
    		];
    		$data = Roles::create($insert_array);
            if($data)  {
                $insert_array2 = [];
                foreach ($request->method as $value) {
                    array_push($insert_array2, [
                        'role_id'   => $data->id,
                        'controller_id' => $value['controller_id'],
                        'action_id'  => $value['action_id'],
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
                    ]);
                }
                UserAction::insert($insert_array2);
            }
    		$response = [
	            'status' => true,
	            'message' => 'Roles Created Successfully',
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
     * //@ updateRole() to Update the roles
     * @access   public
     * @param    $_POST
     * @return   JSON
     * @version  v0.1
     * @author Abir Adak <abir10.wis@gmail.com>
    */
    public function updateRole(RolesRequest $request, $id) {
        $currentAction = \Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);
        $controller = preg_replace('/.*\\\/', '', $controller);
        $method = preg_replace('/.*\\\/', '', $method);
        $data = $this->checkPermission($controller, $method);
        if($data == true) {
            $data = Roles::findOrFail($id);
            $update_array = [
                $data->name = $request->name
            ];
            $data->save($update_array);
            $update_array2 = [];
            foreach ($request->method as $value) {
                array_push($update_array2, [
                    'role_id'   => $data->id,
                    'controller_id' => $value['controller_id'],
                    'action_id'  => $value['action_id'],
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]);
            }
            UserAction::where('role_id', '=' , $id)->delete();
            UserAction::insert($update_array2);
            $response = [
                'status' => true,
                'message' => 'Roles Successfully Updated!',
                'data' => $data
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
     * //@ deleteRole() to delete the roles
     * @access   public
     * @param    $_POST
     * @return   JSON
     * @version  v0.1
     * @author Abir Adak <abir10.wis@gmail.com>
    */
    public function deleteRole($id) {
        $currentAction = \Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);
        $controller = preg_replace('/.*\\\/', '', $controller);
        $method = preg_replace('/.*\\\/', '', $method);
        $data = $this->checkPermission($controller, $method);
    	// $user = Auth::user();
    	if($data == true){
    	    $data = Roles::findOrFail($id);
            $delete_array = [
                'is_deleted' => 1
            ];
            $data->save($delete_array);
    		$response = [
	            'status' => true,
	            'message' => 'Role Successfully Deleted',
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
     * //@ addRoles() to add the roles
     * @access   public
     * @param    $_POST
     * @return   JSON
     * @version  v0.1
     * @author Abir Adak <abir10.wis@gmail.com>
    */
    public function getController() {
        $user = Auth::user();
        if($user) {
            $controllers = Controllers::with('method')->get();
            $response = [
                    'status' => true,
                    'message' => 'Data Avialable',
                    'data' => $controllers
                ];
            return response()->json($response); //@ sending response
        }
    }
}
