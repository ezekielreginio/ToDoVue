<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;

use App\Services\OneSignalAccessTokenService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Exception;

class ApiAuthController extends Controller
{
    protected $output;
    protected $oneSignalAccessTokenService;

    public function __construct(OneSignalAccessTokenService $oneSignalAccessTokenService){
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $this->oneSignalAccessTokenService = $oneSignalAccessTokenService;
    }

    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'onesignal_token' => 'required|string|max:255',
            'device_uuid'=>'required|string|max:255'
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        $data = $request->only([
            'onesignal_token',
            'device_uuid',
        ]);
        $data['user_id'] = $user->id;
        $result = ['status' => 200];
        try{
            $result['data'] = $this->oneSignalAccessTokenService->saveOneSignalAccessToken($data);
        } catch(Exception $e){
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        $response = ['token' => $token, 'user'=> $user];

        return response($response, 200);
    }

    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'onesignal_token' => 'required|string|max:255',
            'device_uuid'=>'required|string|max:255'
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token' => $token, 'user'=> $user];
                
                $data = $request->only([
                    'onesignal_token',
                    'device_uuid',
                ]);
                $data['user_id'] = $user->id;
                $result = ['status' => 200];
                try{
                    $result['data'] = $this->oneSignalAccessTokenService->saveOneSignalAccessToken($data);
                } catch(Exception $e){
                    $result = [
                        'status' => 500,
                        'error' => $e->getMessage()
                    ];
                }

                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }

    public function getUsers(){
        $users = User::all();
        return response($users, 200);
    }

    public function logout (Request $request) {
        $validator = Validator::make($request->all(), [
            'onesignal_token' => 'required|string|max:255',
            'device_uuid'=>'required|string|max:255'
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $result = ['status' => 200];
        try{
            $result['data'] = $this->oneSignalAccessTokenService->deleteByToken($request->onesignal_token);
            return response($result, 200);
        } catch(Exception $e){
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
            return response($result, $result['status']);
        }
        // $user = User::where('onesignal_player_id', $request->onesignal_player_id)->first();

        // $user->onesignal_player_id = null;
        // $user->save();
        
        
    }
}
