<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LaporanController extends Controller
{
    protected $status = null;
    protected $error = null;
    protected $data = null;

        public function getLaporan()
        {

            return Laporan::when(request('search'), function ($query) {
            $query->where('judul','like','%'. request('search'). '%');
        })->with('get_status')->with('get_user')->with('get_perusahaan')->paginate(5);
        
        }

        public function store(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'judul' => 'required',
            // 'nama_dinas' => 'required',
            'file' => 'required|mimes:pdf',
        ]);

        if ($validator->fails()){
            return response(
                [
                    'status' => "failed",
                    'data' => ["message" => "data salah"],
                    'error' => $validator->errors(),
                ]
                );
        }
        $date = Carbon::now()->toDateString();
        $document = new Laporan();
        $document->judul = $request->judul;
        // $document->nama_dinas = $request->nama_dinas;
        $document->tanggal = $date;
        $document->id_status = $request->id_status;
         $document->id_dinas = $request->id_dinas;
        $document->id_perusahaan = $request->id_perusahaan;
        $document->keterangan = $request->keterangan;
    if ($request->file && $request->file->isValid()) {
        $file_name = $request->file->getClientOriginalName();
        $request->file->move(public_path('laporan'), $file_name);
        $path = $file_name;
        $document->file = $path;
    } 

    try {
        $document->save();
        $this->data = $document;
        $this->status = "success";
    } catch (QueryException $e) {
        $this->status = "failed";
        $this->error = $e;
    }
    return response()->json([
        "status" => $this->status,
        "data" => $this->data,
        "error" =>$this->error
    ]);
    } 

        public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            // 'keterangan' => 'required',
            // 'nama_dinas' => 'required',
            // 'file' => 'required|mimes:pdf',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->all()[0];
            return response()->json([
                'status' => 'failed',
                'message' => $error,
                'data' => []
            ]);
        }
        $document =  Laporan::find($id);
        $date = Carbon::now()->toDateString();
        $document->judul = $request->judul;
        $document->nama_dinas = $request->nama_dinas;
        $document->tanggal = $date;
        $document->id_status = $request->id_status;
        $document->id_dinas = $request->id_dinas;
        $document->id_perusahaan = $request->id_perusahaan;
        $document->keterangan = $request->keterangan;
        // if ($request->file && $request->file->isValid()) {
        //     $file_name = $request->file->getClientOriginalName();
        //     $request->file->move(public_path('laporan'), $file_name);
        //     $path = $file_name;
        //     $document->file = $path;
        // } 
        $document->save();
        return response()->json([
            'status' => 'success',
            'data' => $document,
            'messagge' => 'data berhasil di update'
        ]);
    }

          public function updatetolak(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'keterangan' => 'required',
            // 'nama_dinas' => 'required',
            // 'file' => 'required|mimes:pdf',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->all()[0];
            return response()->json([
                'status' => 'failed',
                'message' => $error,
                'data' => []
            ]);
        }
        $document =  Laporan::find($id);
        $date = Carbon::now()->toDateString();
        $document->judul = $request->judul;
        // $document->nama_dinas = $request->nama_dinas;
        $document->tanggal = $date;
        $document->id_status = $request->id_status;
        $document->id_dinas = $request->id_dinas;
        // $document->id_perusahaan = $request->id_perusahaan;
        $document->keterangan = $request->keterangan;
        // if ($request->file && $request->file->isValid()) {
        //     $file_name = $request->file->getClientOriginalName();
        //     $request->file->move(public_path('laporan'), $file_name);
        //     $path = $file_name;
        //     $document->file = $path;
        // } 
        $document->save();
        return response()->json([
            'status' => 'success',
            'data' => $document,
            'messagge' => 'data berhasil di update'
        ]);
    }



     public function destroy($id){
        $document = Laporan::where('id', $id);
        $document->delete();

        return response([
            'status' => "success",
            'data' => ["message" => "data berhasil di hapus"],
            'error' => '' 
        ]);
    }

     // MENAMPILKAN DATA SESUAI ID
    public function showLaporan($id)
    {
        //find by ID
        $admin = Laporan::with('get_status')->findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $admin 
        ], 200);

    }

    public function showLaporanRole()
    {

        return Laporan::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_dinas', auth()->guard('user-api')->user()->id)->paginate(2);
        

    }

     public function showLaporanRolePerusahaan()
    {

        return Laporan::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->paginate(2);
        

    }

    public function showLaporanDiterimaRolePerusahaan()
    {

        return Laporan::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','2')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->paginate(2);
        

    }

      public function showLaporanDitolakRolePerusahaan()
    {

        return Laporan::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','3')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->paginate(2);
        

    }

    public function getStatus()
    {
        //find by ID
        $admin = Laporan::with('get_status')->where('id_status','1')->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $admin 
        ], 200);

    }

    public function getStatusTwo()
    {
        //find by ID
        $admin = Laporan::with('get_status')->where('id_status','2')->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $admin 
        ], 200);

    }

    public function countDataAll(){
        $count = Laporan::count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDataAllDashboard(){
        $count = Laporan::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_dinas', auth()->guard('user-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

     public function countDataAllDashboardPerusahaan(){
        $count = Laporan::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDatabyStatusOne(){
        $count = Laporan::with('get_status')->where('id_status','1')->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDatabyStatusOneDashboard(){
        $count = Laporan::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','1')->where('id_dinas', auth()->guard('user-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

     public function countDatabyStatusOneDashboardPerusahaan(){
        $count = Laporan::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','1')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDatabyStatusTwo(){
        $count = Laporan::with('get_status')->where('id_status','2')->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

     public function countDatabyStatusTwoDashboard(){
        $count = Laporan::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','2')->where('id_dinas', auth()->guard('user-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

     public function countDatabyStatusTwoDashboardPerusahaan(){
        $count = Laporan::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','2')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDatabyStatusThree(){
        $count = Laporan::with('get_status')->where('id_status','3')->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

     public function countDatabyStatusThreeDashboard(){
        $count = Laporan::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','3')->where('id_dinas', auth()->guard('user-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

     public function countDatabyStatusThreeDashboardPerusahaan(){
        $count = Laporan::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','3')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }
}
