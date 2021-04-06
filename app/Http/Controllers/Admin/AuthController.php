<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\AksesUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\User;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use function PHPUnit\Framework\isNull;

//use App\Models\User;
class AuthController extends Controller
{
    /* base */
    private function basecolumn() {
        return $basecolumn=[
            'username',
            'empid',
            'fullname',
            'rankid',
            'city',
            'address',
            'email',
            'phone',
            'levelid',
            'active',
            'neverexpired',
            'structureid',
        ];
    }

    private function validation($data) {
        $rules = [
            'fullname' => 'required|min:3',
            'levelid' => 'required|numeric',
            'rankid' => 'required|numeric',
            'structureid' => 'required|numeric',
        ];
        $v = Validator::make($data, $rules);
        if ($v->fails()) {
            return [false, $v];
        }
        return [true, $v];
    }

    private function can($id) {
        $levelid = auth()->payload()->get('levelid');
        if ($levelid > $id) {
            return false;
        }
        return true;
    }

    /**
     * Register a new user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $value = $this->validation($request->all());
        $status = $value[0];
        $v = $value[1];
        if (! $status) {
            return Response::json([
                'status' => 'error',
                'error' => $v->errors(),
            ], 422);
        }
        $v = Validator::make($request->all(), [
            'email' => 'required|email|unique:nxt_users',
            'username' => 'required|unique:nxt_users',
        ]);
        if ($v->fails()) {
            return Response::json([
                'status' => 'error',
                'error' => $v->errors(),
            ], 422);
        }

        $data = new User();
        $basecolumn = $this->basecolumn();
        foreach ($basecolumn as $base) {
            $data->{$base} = $request->input($base);
        }
        //password
        $data->password = bcrypt($request->input('password'));

        //Avatar Image
        $avatar64 = $this->avatar64($request->input('avatar'));
        if ($avatar64 != "null") {
            $extension =  explode('/', mime_content_type($avatar64))[1];
            if ($extension === 'jpeg' or $extension === 'jpg') {
                $avatar = base64_decode(str_replace('data:image/jpeg;base64,', '', $avatar64));
            }
            elseif ($extension === 'png') {
                $avatar = base64_decode(str_replace('data:image/png;base64,', '', $avatar64));
            }
            else {
                return Response::json([
                    'error' => 'Gambar tidak dapat disimpan',
                ]);
            }
            $now = Carbon::now()->timestamp;
            Storage::put('/images/'.$now.'.jpg', $avatar);
            $avatar64 = $now.'jpg';
        } else {
            $avatar64 = null;
        }
        $data->avatar = $avatar64;

        $data->save();
        $id = $data->id;
        $akses = json_decode($request->input('akses'), true);
        foreach ($akses as $a) {
            $userrole = AksesUser::where('userid', '=', $id)->where('roleid', '=', $a['id'])->first();
            if (! isNull($userrole)) {
                $aksesuserdelete = AksesUser::find($userrole->id);
                $aksesuserdelete->delete();
            }
            $aksesuser = new AksesUser();
            $aksesuser->userid = $id;
            $aksesuser->roleid = $a['id'];
            $aksesuser->save();
        }
        return response()->json([
            'status' => 'success'
        ],200);
    }

    /* read */
    public function index(Request $request)
    {
        $length = $request->input('length');
        $sortBy = $request->input('column');
        $orderBy = $request->input('dir');
        $searchValue = $request->input('search');
        $levelid = auth()->payload()->get('levelid');
        $query = User::eloquentQuery($sortBy, $orderBy, $searchValue, [
            'level',
            'rank',
            'structure',
            "akses",
        ]);
        $query = $query->where('levelid', '>', $levelid);
        $data = $query->paginate($length);
        foreach ($data as $d){
            if (Storage::exists('images/'.$d['avatar'])) {
                $image = Storage::path('images/'.$d['avatar']);
                if (pathinfo($image)['extension'] === 'jpg' or pathinfo($image)['extension'] === 'jpeg') {
                    $image64 = 'data:image/jpeg;base64,'.base64_encode(file_get_contents($image));
                } elseif (pathinfo($image)['extension'] === 'png') {
                    $image64 = 'data:image/png;base64,'.base64_encode(file_get_contents($image));
                }
                $d['avatar'] = $image64;
            } else {
                $d['avatar'] = null;
            }
            unset($d);
        }
        return new DataTableCollectionResource($data);
    }

    public function show($id)
    {
        if ($id === null) {
            return response()->json([
                'error' => 'id cant null',
            ]);
        }

        $levelid = auth()->payload()->get('levelid');
        $userid = auth()->payload()->get('sub');

        $user = User::with('level', 'rank', 'structure', 'akses')->find($id);

        if (is_null($user)) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        if ($user['id'] != $userid) {
            if ($user->levelid <= $levelid) {
                return response()->json([
                    'error' => 'Tidak memiliki otorisasi',
                ], 403);
            }
        }

        if (Storage::exists('images/'.$user['avatar'])) {
            $image = Storage::path('images/'.$user['avatar']);
            if (pathinfo($image)['extension'] === 'jpg' or pathinfo($image)['extension'] === 'jpeg') {
                $image64 = 'data:image/jpeg;base64,'.base64_encode(file_get_contents($image));
            } elseif (pathinfo($image)['extension'] === 'png') {
                $image64 = 'data:image/png;base64,'.base64_encode(file_get_contents($image));
            }
            $user['avatar'] = $image64;
        } else {
            $user['avatar'] = null;
        }


        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    /* update */
    public function update(Request $request, $id)
    {
        $value = $this->validation($request->all());
        $status = $value[0];
        $v = $value[1];
        if (! $status) {
            return Response::json([
                'status' => 'error',
                'error' => $v->errors(),
            ], 422);
        }

        $v = Validator::make($request->all(), [
            'email' => 'required|email',
            'username' => 'required',
        ]);
        if ($v->fails()) {
            return Response::json([
                'status' => 'error',
                'error' => $v->errors(),
            ], 422);
        }

        if ($request->avatarChange === true) {
            if ($request->avatar == "") {
                $avatarFile = null;
            } else {
                $avatar64 = $request->avatar;
                $extension = explode('/', mime_content_type($avatar64))[1];
                if ($extension === 'jpeg' or $extension === 'jpg') {
                    $avatar = base64_decode(str_replace('data:image/jpeg;base64,', '', $avatar64));
                } elseif ($extension === 'png') {
                    $avatar = base64_decode(str_replace('data:image/png;base64,', '', $avatar64));
                }
                $now = Carbon::now()->timestamp;
                Storage::put('images/'.$now . '.jpg', $avatar);
                $avatarFile = $now.'.jpg';
            }
        }

        $data = User::find($id);

        $basecolumn = $this->basecolumn();
        try {
            foreach ($basecolumn as $base) {
                $data->update([
                    $base => $request->input($base)
                ]);
            }
            if ($request->avatarChange === true) {
                $data->update([
                    'avatar' => $avatarFile
                ]);
            }

            if ($request->password != null) {
                $data->update([
                    'password' => bcrypt($request->password),
                ]);
            }

            $dataakses = AksesUser::where('userid', '=', $id)->get();
            foreach ($dataakses as $da) {
                AksesUser::find($da->id)->delete();
            }
            $akses = json_decode($request->input('akses'), true);
            foreach ($akses as $a) {
                $userrole = AksesUser::where('userid', '=', $id)->where('roleid', '=', $a['id'])->first();
                if (! isNull($userrole)) {
                    $aksesuserdelete = AksesUser::find($userrole->id);
                    $aksesuserdelete->delete();
                }
                $aksesuser = new AksesUser();
                $aksesuser->userid = $id;
                $aksesuser->roleid = $a['id'];
                $aksesuser->save();
            }
            return Response::json([
                'status' => 'success'
            ], 200);
        } catch(\Throwable $tr) {
            return Response::json([
                'error' => 'error_update',
                'data' => $tr,
            ], 500);
        }
    }

    /* delete */
    public function delete($id) {

        $data = User::find($id);
        if (is_null($data)) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }
        try {
//            $data->delete();
            return Response::json([
                'status' => 'success',
                'data' => 'Entry berhasil dihapus'
            ], 204);
        } catch (\Throwable $tr) {
            return Response::json([
                'error' => 'Entry gagal dihapus'
            ], 304);
        }
    }

    /* custom */
    /**
     * Login user and return a token
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
//        if ($request->input('username') != 'fr' && $request->input('akses') == 0 ) {
//            return Response::json([
//                'error' => 'akses_required'
//            ], 403);
//        }
        $user = User::where('username', '=', $request->username)->first();
        if ($user->active === 0) {
            return response()->json([
                'error' => 'not_active'
            ], 401);
        }
        if ($token = $this->guard()->claims([
            'fullname' => $user->fullname,
            'username' => $user->username,
            'levelid' => $user->levelid,
            'akses' => $request->input('akses'),
        ])->attempt($credentials)) {
            return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
        }
        return response()->json(['error' => 'login_error'], 401);
    }

    public function loginconfirm(Request $request)
    {
        $username = auth()->payload()->get('username');
        $user = User::where('username', '=', $request->input('username'))->first();
        if ($user['active'] === 0) {
            return response()->json(['error' => 'login_error'], 401);
        }
        $usernamesame = $username == $user['username'];
        $passwordv = Hash::check($request->input('password'), $user['password']);
        if ($usernamesame && $passwordv) {
            return Response::json([
                'status' => 'success',
            ], 200);
        }
        return response()->json(['error' => 'login_error'], 403);
    }
    /**
     * Logout User
     */
    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }
    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function GetAkses(Request $request) {
        $username = $request->input('username');
        $data = User::with('akses')->where("username", "=", $username)->get();
        return Response::json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    /**
     * Refresh JWT token
     */
    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }
    /**
     * Return auth guard
     */
    private function guard()
    {
        return Auth::guard();
    }
}
