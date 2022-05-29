<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{   
    protected $status = null;
    protected $error = null;
    protected $data = null;


    public function userDashboard()
    {
        $users = User::all();
        $success =  $users;

        // return response()->json($success, 200);
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $success 
        ], 200);
    }

    public function adminDashboard()
    {
        $admin = Admin::all();
        $success =  $admin;

         // return response()->json($success, 200);
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $success 
        ], 200);
    }

    public function perusahaanDashboard()
    {
        $perusahaans = Perusahaan::all();
        $success =  $perusahaans;

        // return response()->json($success, 200);
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $success 
        ], 200);
    }

     // MENAMPILKAN DATA SESUAI ID
    public function userShow($id)
    {
        //find by ID
        $user = User::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $user
        ], 200);

    }

    // MENAMPILKAN DATA SESUAI ID
    public function adminShow($id)
    {
        //find by ID
        $admin = Admin::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $admin
        ], 200);

    }

     // MENAMPILKAN DATA SESUAI ID
    public function perusahaanShow($id)
    {
        //find by ID
        $user = Perusahaan::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $user
        ], 200);

    }

    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if (auth()->guard('user')->attempt(['email' => request('email'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'user']);

            $user = User::select('users.*')->find(auth()->guard('user')->user()->id);
            $success =  $user;
            $success['token'] =  $user->createToken('MyApp', ['user'])->accessToken;
            return response()->json([$success]);
        } else {
            return response()->json(['error' => 'Email and Password are Wrong.'], 200);
        }
    }

    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if (auth()->guard('admin')->attempt(['email' => request('email'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'admin']);

            $admin = Admin::select('admins.*')->find(auth()->guard('admin')->user()->id);
            $success =  $admin;
            $success['token'] =  $admin->createToken('MyApp', ['admin'])->accessToken;

            return response()->json([$success]);
        } else {
            return response()->json(['error' => 'Email and Password are Wrong.'], 200);
        }
    }

    public function perusahaanLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if (auth()->guard('perusahaan')->attempt(['email' => request('email'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'perusahaan']);

            $perusahaan = Perusahaan::select('perusahaans.*')->find(auth()->guard('perusahaan')->user()->id);
            $success =  $perusahaan;
            $success['token'] =  $perusahaan->createToken('MyApp', ['perusahaan'])->accessToken;
            return response()->json([$success]);
        } else {
            return response()->json(['error' => 'Email and Password are Wrong.'], 200);
        }
    }



    public function userRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('nApp')->accessToken;
        $success['name'] =  $user;

        return response()->json(['success' => $success]);
    }

    public function adminRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Admin::create($input);
        $success['token'] =  $user->createToken('nApp')->accessToken;
        $success['name'] =  $user;

        return response()->json(['success' => $success]);
    }
    
    public function perusahaanRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Perusahaan::create($input);
        $success['token'] =  $user->createToken('nApp')->accessToken;
        $success['name'] =  $user;

        return response()->json(['success' => $success]);
    }

    public function userDestroy($id)
    {
        //
        $user = User::where('id', $id);
        $user->delete();

        return response(
            [
                'status' => "success",
                'data' => ["message" => "data berhasil di hapus"],
                'erorr' => ''
            ]
        );
    }

    public function adminDestroy($id)
    {
        //
        $admin = Admin::where('id', $id);
        $admin->delete();

        return response(
            [
                'status' => "success",
                'data' => ["message" => "data berhasil di hapus"],
                'erorr' => ''
            ]
        );
    }

    public function perusahaanDestroy($id)
    {
        //
        $perusahaan = Perusahaan::where('id', $id);
        $perusahaan->delete();

        return response(
            [
                'status' => "success",
                'data' => ["message" => "data berhasil di hapus"],
                'erorr' => ''
            ]
        );
    }

    public function userEdit(Request $request, User $user, $id)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'   => 'required',
            'password'   => 'required'
        ]);
        
            //response error validation
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

        //find by ID
        $user = User::findOrFail($id);

            if($user) {

                //update
                $user->update([
                    'name'     => $request->name,
                    'email'     => $request->email,
                    'password'     => Hash::make($request->password),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Update',
                    'data'    => $user  
                ], 200);

            }

            //data not found
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);

    }

     public function perusahaanEdit(Request $request, Perusahaan $perusahaan, $id)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'   => 'required',
            'password'   => 'required'
        ]);
        
            //response error validation
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

        //find by ID
        $perusahaan = Perusahaan::findOrFail($id);

            if($perusahaan) {

                //update
                $perusahaan->update([
                    'name'     => $request->name,
                    'email'     => $request->email,
                    'password'     => Hash::make($request->password),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Update',
                    'data'    => $perusahaan  
                ], 200);

            }

            //data not found
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);

    }

    public function adminEdit(Request $request, Admin $user, $id)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'   => 'required',
            'password'   => 'required'
        ]);
        
            //response error validation
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

        //find by ID
        $user = Admin::findOrFail($id);

            if($user) {

                //update
                $user->update([
                    'name'     => $request->name,
                    'email'     => $request->email,
                    'password'     => Hash::make($request->password),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Update',
                    'data'    => $user  
                ], 200);

            }

            //data not found
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);

    }

    // public function userEdit(Request $request, User $post)
    // {
    //     //set validation
    //     $validator = Validator::make($request->all(), [
    //         'name'     => $request->title,
    //         'email'   => $request->email,
    //         'password'   => $request->password,
    //     ]);
        
    //     //response error validation
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 400);
    //     }

    //     //find post by ID
    //     $post = User::findOrFail($post->id);

    //     if($post) {

    //         //update post
    //         $post->update([
    //             'name'     => $request->title,
    //             'email'   => $request->email,
    //             'password'   => $request->password,
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Post Updated',
    //             'data'    => $post  
    //         ], 200);

    //     }

    //     //data post not found
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Post Not Found',
    //     ], 404);

    // }

    // public function userEdit(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), []);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'validation fails',
    //             'errors' => $validator->errors()
    //         ]);
    //     }

    //     $user = User::find($id);
    //     if (Hash::check($request->old_password, $user->password)) {
    //         $user->update([
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password)
    //         ]);
    //         return response()->json([
    //             'message' => 'Data Pribadi berhasil di updated',

    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'Old password does not matched',

    //         ], 400);
    //     }
    // }

    // public function adminEdit(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), []);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'validation fails',
    //             'errors' => $validator->errors()
    //         ]);
    //     }

    //     $admin = Admin::find($id);
    //     if (Hash::check($request->old_password, $admin->password)) {
    //         $admin->update([
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password)
    //         ]);
    //         return response()->json([
    //             'message' => 'Data Pribadi berhasil di updated',

    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'Old password does not matched',

    //         ], 400);
    //     }
    // }

    // public function perusahaanEdit(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), []);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'validation fails',
    //             'errors' => $validator->errors()
    //         ]);
    //     }

    //     $perusahaan = Perusahaan::find($id);
    //     if (Hash::check($request->old_password, $perusahaan->password)) {
    //         $perusahaan->update([
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password)
    //         ]);
    //         return response()->json([
    //             'message' => 'Data Pribadi berhasil di updated',

    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'Old password does not matched',

    //         ], 400);
    //     }
    // }

    public function userDetail($id)
    {
        $user = User::findOrfail($id);

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'Detail Data User',
            'data' => $user
        ]);
    }

    public function currentUser(Request $request){
    $user = User::find($request->user());
    return response()->json($user);
    }

    public function currentUserPerusahaan(Request $request){
    $perusahaan = Perusahaan::find($request->user());
    return response()->json($perusahaan);
    }

    public function currentUserAdmin(Request $request){
    $admin = Admin::find($request->user());
    return response()->json($admin);
    }

    
  
    
    
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name'      => 'required',
    //         'email'     => 'required|email',
    //         'password'  => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 400);
    //     }

    //     $user = Admin::create([
    //         'name'      => $request->name,
    //         'email'     => $request->email,
    //         'password'  => Hash::make($request->password)
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Register Success!',
    //         'data'    => $user  
    //     ]);
    // }

    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 400);
    //     }

    //     $user = Admin::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Login Failed!',
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Login Success!',
    //         'data'    => $user,
    //         'token'   => $user->createToken('authToken')->accessToken    
    //     ]);
    // }
    
}
