<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\model\Token;
use App\model\Betusers;
use App\model\Roles;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class CustomLogin extends Controller
{

/*
|--------------------------------------------------------------------------
| Login Controller
|--------------------------------------------------------------------------
|
| This controller handles authenticating users for the application and
| redirecting them to your home screen. The controller uses a trait
| to conveniently provide its functionality to your applications.
|
*/
    use AuthenticatesUsers;

     /**
     * Create a new controller instance.
     *
     * @return void
     */


     /**  
     * //@ userLogin() to Login user credentials
     * @access   public
     * @param    $_POST
     * @return   JSON
     * @version  v0.1
     * @author name <email>
    */
     function userLogin(Request $request) {
        if(!empty($request->username) && !empty($request->password)) {
            $user_ip = $this->getIp();
            $credentials = User::where('username' , $request->username)->first();
            if($credentials && ($credentials->is_multisign == 0)) {
                Token::where('user_id', $credentials->id)
                       ->where('name', '=' , 1)
                       ->delete();
                if(Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password ,
                    'deleted' => 0])){
                    $user = Auth::user();
                    $user['role'] = Roles::where('id', $user->role)->first();
                    unset($user['password']);
                    unset($user['orginal_password']);
                    $success['token'] =  $user->createToken(1)-> accessToken;
                    $user['token'] = $success['token'];
                    $response = [
                        'status' => true,
                        'message' => 'Login Successfull',
                    ];
                    $response['data'] = $user; 
        
                    return response()->json($response); //@ sending response
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'Invalid Credentials!'
                    ];
                    return response()->json($response); //@ sending response
                }
            } else {
                if(Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password ,
                    'deleted' => 0])){

                    $user = Auth::user();
                    $user['role'] = Roles::where('id', $user->role)->first();
                    unset($user['password']);
                    unset($user['orginal_password']);
                    $success['token'] =  $user->createToken(1)-> accessToken;
                    $user['token'] = $success['token'];
                    $response = [
                        'status' => true,
                        'message' => 'Login Successfull',
                    ];
                    $response['data'] = $user; 
        
                    return response()->json($response); //@ sending response
                }else{
                    $response = [
                        'status' => false,
                        'message' => 'Invalid Credentials!'
                    ];
                    return response()->json($response); //@ sending response
                }

            }
        } else {
            $response = [
                'status' => false,
                'message' => 'Please Fillup The Fields!'
            ];
            return response()->json($response); //@ sending response
        }
    }
   
    

    use AuthenticatesUsers;

     /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**  
     * //@ userLogin() to Login user credentials
     * @access   public
     * @param    $_POST
     * @return   JSON
     * @version  v0.1
     * @author name <email>
    */
    function betUserLogin(Request $request){    
        if(!empty($request->username) && !empty($request->password)){
            $credentials = Betusers::where('username' , $request->username)->first();
            if($credentials && ($credentials->is_multisign == 0)) {
                Token::where('user_id', $credentials->id)
                       ->where('name', '=' , 0)
                       ->delete();
                if(Auth::guard('client_login')->attempt(['username' => $request->username, 'password' => $request->password , 'deleted' => 0])){
                    $user = Betusers::where('username' , $request->username)->first();
                    unset($user['password']);
                    unset($user['orginal_password']);
                    $success['token'] =  $user->createToken(0)-> accessToken;
                    $user['token'] = $success['token'];
                    $response = [
                        'status' => true,
                        'message' => 'Login Successfull',
                    ];
                    $response['data'] = $user; 
        
                    return response()->json($response); //@ sending response
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'Invalid Credentials!'
                    ];
                    return response()->json($response); //@ sending response
                }       
            } else {
                if(Auth::guard('client_login')->attempt(['username' => $request->username, 'password' => $request->password , 'deleted' => 0])){
                    $user = Betusers::where('username' , $request->username)->first();
                    unset($user['password']);
                    unset($user['orginal_password']);
                    $success['token'] =  $user->createToken(0)-> accessToken;
                    $user['token'] = $success['token'];
                    $response = [
                        'status' => true,
                        'message' => 'Login Successfull',
                    ];
                    $response['data'] = $user; 
        
                    return response()->json($response); //@ sending response
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'Invalid Credentials!'
                    ];
                    return response()->json($response); //@ sending response
                }

            }

        } else {
            $response = [
                'status' => false,
                'message' => 'Please Fillup The Fields!'
            ];
            return response()->json($response); //@ sending response
        }
    }

    /**  
     * //@ getIp() to get the 
     * @access   public
     * @param    $_POST
     * @return   JSON
     * @version  v0.1
     * @author name <email>
    */
    public function getIp() {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    return $ip;
                    // if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    //     return $ip;
                    // }
                }
            }
        }
    }

    /**  
     * //@ logOut() to logout the User
     * @access   public
     * @param    $_POST
     * @return   JSON
     * @version  v0.1
     * @author name <email>
    */
    public function logOut(Request $request) {
        // $user = Auth::user();
        // $data = Token::where('user_id', $user->id)
        //               ->where('name', $request->name)
        //               //->where('id',$request->token)
        //               ->first(); 
        // $bearerToken = $request->_token;
        // $tokenId = (new \Lcobucci\JWT\Parser())->parse($bearerToken)->getHeader('jti');
        // print_r($tokenId);
        // exit();

        // $client = \Laravel\Passport\Token::find($tokenId)->client;              
        // print_r($client);
        // exit();                                 
    }
}
