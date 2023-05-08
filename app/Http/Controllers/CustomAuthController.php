<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Hash;
use Session;

class CustomAuthController extends Controller
{
    // index function for showing login page
    public function index()
    {
        return view('auth.login');
    }
    // function for login process
    public function customLogin(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard')
                        ->withSuccess('Signed in');
        }

        return redirect("/")->withSuccess('Login Credentials are not valid');
    }
    // function for showing dashboard
    public function dashboard(){
        if(Auth::check()){
            $id = Auth::user()->id;
            return view('auth.dashboard', ['work' => DB::table('work_list')->where('created_by',$id)->orderBy('id','DESC')->paginate(5)]);
        }

        return redirect("/")->withSuccess('You are not allowed to access');
    }
    // function for viewing registration page
    public function registration(){
        return view('auth.registration');
    }
    // function for registration process
    public function customRegistration(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        $data = $request->all();
        $check = $this->create($data);
        return redirect("registration")->withSuccess('User Successfully Registered.....');
    }

    // function for save new register user
    public function create(array $data){
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }

    // function for sdr upload page
    public function uploadmodule(){
        if(Auth::check()){
            return view('auth.sdrupload');
        }
        return redirect("/")->withSuccess('You are not allowed to access');
    }

    // function for showing headerlist page
    public function headerlist(){
        if(Auth::check()){
            //dd($header->get());exit();
            return view('auth.headerlist', ['header' => DB::table('header_list')->where('active','1')->whereJsonLength('alias', '> ' ,0)->get()]);
        }
        return redirect("/")->withSuccess('You are not allowed to access');
    }

    // function for showing header matching page
    public function headermatch(){
        if(Auth::check()){
            $columns = [];
            $_headers = DB::table('header_list')->get();
            foreach($_headers as $_headName){
                $columns[$_headName->name] = ['alias' => json_decode($_headName->alias), 'index' => null, 'empty' => $_headName->empty];
            }
            return view('auth.headermatch', ['header' => base64_encode(json_encode($columns))]);
        }
        return redirect("/")->withSuccess('You are not allowed to access');
    }

    // function for getting header list using id
    function getHeaders(Request $request){
        if ($request->isMethod('post')) {
            $headerid = $request->headerid;
            $where = array('id'=> $headerid);
            $headersName = DB::table('header_list')->where($where)->first();
            return response()->json($headersName);
        }
    }

    // function for edit or add headers name
    function editHeaders(Request $request){
        if($request->isMethod('post')){
            $_alias = $request->alias;
            $_id = $request->id;
            $where = ['id' => $_id];
            $_rowinfo = DB::table('header_list')->where($where)->first();
            $_finAlias = json_decode($_rowinfo->alias);
            array_push($_finAlias, $_alias);
            $_alias_encode = json_encode($_finAlias,true);
            $affected_info = DB::table('header_list')->where($where)->update(['alias' => $_alias_encode]);
            if($affected_info){
                $_respMsg = array(
                    'code' => '1',
                    'status' => 'Header Updated Successfully'
                    );
                return response()->json($_respMsg);
            }else{
                $_respMsg = array(
                    'code' => '0',
                    'status' => 'Failed To Update Header'
                );
                return response()->json($_respMsg);
            }
        }
    }

    // function for upload file
    function uploadFile(Request $request){
        $_REQUEST["name"];
        $input = $request->all();
        $filePath = public_path('data');
        if (!file_exists($filePath)) {
            if (!mkdir($filePath, 0777, true)) {
                return response()->json(["ok"=>0, "info"=>"Failed to create $filePath"]);
            }
        }
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
        $filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;
        // DEAL WITH CHUNKS
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
        $out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");

        if ($out) {
            $in = fopen($_FILES['file']['tmp_name'], "rb");
            if ($in) {
                while ($buff = fread($in, 4096)) { fwrite($out, $buff); }
            } else {
                return response()->json(["ok"=>0, "info"=>'Failed to open input stream']);
            }
            fclose($in);
            fclose($out);
            unlink($_FILES['file']['tmp_name']);
        }
        // CHECK IF FILE HAS BEEN UPLOADED

        if (!$chunks || $chunk == $chunks - 1) {
            rename("{$filePath}.part", $filePath);
            $array = ['file' => $fileName];
            //ChunkFile::create($array);
        }
        $info = "Upload OK";
        $ok =1;

        return response()->json(["ok"=>$ok, "filename"=> $fileName]);
    }

    // function for save sdr related information
    function saveSdrInfo(Request $request){
        if($request->isMethod('post')){
            $id = Auth::user()->id;
            if (!empty($request->enclosure) && !empty($request->delimiter)) {
                $dataArr = [
                    'circle' => strtoupper($request->circle),
                    'provider' => strtoupper($request->provider),
                    'connection' => strtoupper($request->connection),
                    'activation_date_format' => $request->adf,
                    'dob_date_format' => $request->dobf,
                    'db_table' => strtolower($request->dbtable),
                    'delimiter' => $request->delimi,
                    'enclosure' => $request->enclosue,
                    'filepath' => $request->fileName,
                    'created_by' => $id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'month_year' => $request->monthDate,
                    'status' => 1
                ];
                $affectedRow = DB::table('work_list')->insert($dataArr);
                if($affectedRow == 1){
                    return response()->json(["status"=> 1]);
                }else{
                    return response()->json(["status"=> 0]);
                }
            }else if(empty($request->enclosure) && !empty($request->delimiter)){
                $dataArr = [
                    'circle' => strtoupper($request->circle),
                    'provider' => strtoupper($request->provider),
                    'connection' => strtoupper($request->connection),
                    'activation_date_format' => $request->adf,
                    'dob_date_format' => $request->dobf,
                    'db_table' => strtolower($request->dbtable),
                    'delimiter' => $request->delimi,
                    'filepath' => $request->fileName,
                    'created_by' => $id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'month_year' => $request->monthDate,
                    'status' => 1
                ];
                $affectedRow = DB::table('work_list')->insert($dataArr);
                if($affectedRow == 1){
                    return response()->json(["status"=> 1]);
                }else{
                    return response()->json(["status"=> 0]);
                }
            }else if(!empty($request->enclosure) && empty($request->delimiter)){
                $dataArr = [
                    'circle' => strtoupper($request->circle),
                    'provider' => strtoupper($request->provider),
                    'connection' => strtoupper($request->connection),
                    'activation_date_format' => $request->adf,
                    'dob_date_format' => $request->dobf,
                    'db_table' => strtolower($request->dbtable),
                    'enclosure' => $request->enclosue,
                    'filepath' => $request->fileName,
                    'created_by' => $id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'month_year' => $request->monthDate,
                    'status' => 1
                ];
                $affectedRow = DB::table('work_list')->insert($dataArr);
                if($affectedRow == 1){
                    return response()->json(["status"=> 1]);
                }else{
                    return response()->json(["status"=> 0]);
                }
            }else if(empty($request->enclosure) && empty($request->delimiter)){
                $dataArr = [
                    'circle' => strtoupper($request->circle),
                    'provider' => strtoupper($request->provider),
                    'connection' => strtoupper($request->connection),
                    'activation_date_format' => $request->adf,
                    'dob_date_format' => $request->dobf,
                    'db_table' => strtolower($request->dbtable),
                    'filepath' => $request->fileName,
                    'created_by' => $id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'month_year' => $request->monthDate,
                    'status' => 1
                ];
                $affectedRow = DB::table('work_list')->insert($dataArr);
                if($affectedRow == 1){
                    return response()->json(["status"=> 1]);
                }else{
                    return response()->json(["status"=> 0]);
                }
            }
        }
    }

    // function for fetch information by id
    public function updateInformation(Request $request){
        if($request->ajax()){
            $id = $request->id;
            $where = ['id' => $id];
            $data = DB::table('work_list')->where($where)->get();
            return response()->json(['code' => '1', 'result' => $data]);
        }
    }

    // function for update data for sdr
    function updateFinal(Request $request){
        if($request->ajax()){
            $id = $request->id;
            $dataArr = [
                'circle' => strtoupper($request->circle),
                'provider' => strtoupper($request->provider),
                'connection' => strtoupper($request->connection),
                'activation_date_format' => $request->adf,
                'dob_date_format'=> $request->dobf,
                'db_table' => strtoupper($request->dbtable),
                'delimiter' => $request->delimi,
                'enclosure' => $request->encls,
                'month_year' => $request->dateMy
            ];

            $updstatus = DB::table('work_list')->where('id',$id)->update($dataArr);
            if($updstatus){
                return response()->json(['code' => '1']);
            }else{
                return response()->json(['code' => '0']);
            }
        }
    }

    // function for completing the process
    public function completeProcess(Request $request){
        if($request->ajax()){
            $where = ['id' => $request->id];
            $dataArr = [
                'compete_at' => date('Y-m-d h:i:s')
            ];
            $updStatus = DB::table('work_list')->where($where)->update($dataArr);
            if($updStatus){
                return response()->json(['code' => '1']);
            }else{
                return response()->json(['code' => '0']);
            }
        }
    }
    // function for signout the page
    public function signOut() {
        Session::flush();
        Auth::logout();
        return Redirect('/');
    }
}
