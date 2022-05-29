<?php

namespace App\Http\Controllers;

use App\Exports\ProposalExport;
use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use File;
use Illuminate\Http\Response;

class ProposalController extends Controller
{
    protected $status = null;
    protected $error = null;
    protected $data = null;




     public function getProposal()
    {
        return Proposal::when(request('search'), function ($query) {
            $query->where('judul','like','%'. request('search'). '%');
        })->with('get_status')->with('get_user')->with('get_perusahaan')->paginate(5);
       

        
    }

    public function export_excel()
	{
		return Proposal::download(new ProposalExport, 'proposal.xlsx');
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
        $document = new Proposal();
        $document->judul = $request->judul;
        // $document->nama_dinas = $request->nama_dinas;
        $document->tanggal = $date;
        $document->id_status = $request->id_status;
        $document->id_dinas = $request->id_dinas;
        $document->id_perusahaan = $request->id_perusahaan;
    if ($request->file && $request->file->isValid()) {
        $file_name = $request->file->getClientOriginalName();
        $request->file->move(public_path('proposal'), $file_name);
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
            // 'nama_dinas' => 'required',
            // 'nama_perusahaan' => 'required',
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
        $document =  Proposal::find($id);
        $date = Carbon::now()->toDateString();
        $document->judul = $request->judul;
        // $document->nama_dinas = $request->nama_dinas;
        // $document->nama_perusahaan = $request->nama_perusahaan;
        $document->tanggal = $date;
        $document->id_status = $request->id_status;
        $document->id_dinas = $request->id_dinas;
        $document->id_perusahaan = $request->id_perusahaan;
        // if ($request->file && $request->file->isValid()) {
        //     $file_name = $request->file->getClientOriginalName();
        //     $request->file->move(public_path('proposal'), $file_name);
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


         public function updateperusahaan(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            // 'nama_dinas' => 'required',
            // 'nama_perusahaan' => 'required',
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
        $document =  Proposal::find($id);
        $date = Carbon::now()->toDateString();
        $document->judul = $request->judul;
        // $document->nama_dinas = $request->nama_dinas;
        // $document->nama_perusahaan = $request->nama_perusahaan;
        $document->tanggal = $date;
        $document->id_status = $request->id_status;
        $document->id_dinas = $request->id_dinas;
        $document->id_perusahaan = $request->id_perusahaan;
        // if ($request->file && $request->file->isValid()) {
        //     $file_name = $request->file->getClientOriginalName();
        //     $request->file->move(public_path('proposal'), $file_name);
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

    // public function update(Request $request, $id)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'judul' => 'required',
    //         'nama_dinas' => 'required',
    //         'file' => 'required|mimes:pdf',
    //     ]);
    //     if ($validator->fails()) {
    //         $error = $validator->errors()->all()[0];
    //         return response()->json([
    //             'status' => 'failed',
    //             'message' => $error,
    //             'data' => []
    //         ]);
    //     }
    //     $document =  Proposal::find($id);
    //     $date = Carbon::now()->toDateString();
    //     $document = new Proposal();
    //     $document->judul = $request->judul;
    //     $document->nama_dinas = $request->nama_dinas;
    //     $document->tanggal = $date;
    //     $document->id_status = '2';
    //     if ($request->file && $request->file->isValid()) {
    //         $file_name = $request->file->getClientOriginalName();
    //         $request->file->move(public_path('proposal'), $file_name);
    //         $path = $file_name;
    //         $document->file = $path;
    //     } 
    //     $document->update();
    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $document,
    //         'messagge' => 'data berhasil di update'
    //     ]);
    // }

    public function destroy($id){
        $document = Proposal::where('id', $id);
        $document->delete();

        return response([
            'status' => "success",
            'data' => ["message" => "data berhasil di hapus"],
            'error' => '' 
        ]);
    }

     // MENAMPILKAN DATA SESUAI ID
    public function showProposal($id)
    {
        //find by ID
        $admin = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $admin 
        ], 200);

    }

    // MENAMPILKAN DATA SESUAI ID
    public function showProposalRole()
    {

        return Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_dinas', auth()->guard('user-api')->user()->id)->paginate(2);
        

    }

     public function showProposalRolePerusahaan()
    {

        return Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->paginate(2);
        

    }

    public function showProposalDiterimaRolePerusahaan()
    {

        return Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','3')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->paginate(2);
        

    }

     

    public function showProposalRoleDashboard()
    {

        return Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_dinas', auth()->guard('user-api')->user()->id)->get();
        

    }

    public function showProposalRoleDashboardPerusahaan()
    {

        return Proposal::with('get_status')->with('get_perusahaan')->with('get_user')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->get();
        

    }

     

    //  public function downloadfile($id)
    // {
    //      $admin = Proposal::findOrfail($id);
         
    //     $filepath = public_path('proposal');
    //     return Response::download($filepath); 
    // }

    public function getStatus()
    {
        //find by ID
        $admin = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','1')->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $admin 
        ], 200);

    }

     public function getTerimaProposal()
    {
return Proposal::when(request('search'), function ($query) {
            $query->where('judul','like','%'. request('search'). '%');
        })->with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','3')->paginate(5);
       

        //find by ID
        // $admin = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','3')->paginate(5);

        // //make response JSON
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Detail Data',
        //     'data'    => $admin 
        // ], 200);

    }

     public function getTolak()
    {
        return Proposal::when(request('search'), function ($query) {
            $query->where('judul','like','%'. request('search'). '%');
        })->with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','4')->paginate(5);

        //find by ID
        // $admin = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','4')->get();

        // //make response JSON
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Detail Data',
        //     'data'    => $admin 
        // ], 200);

    }

     public function getTerima()
    {
        //find by ID
        $admin = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','2')->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $admin 
        ], 200);

    }

    public function countDataAll(){
        $count = Proposal::count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDataAllDashboard(){
        $count = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_dinas', auth()->guard('user-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDataAllDashboardPerusahaan(){
        $count = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }


    public function countDatabyStatusOne(){
        $count = Proposal::with('get_status')->where('id_status','1')->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDatabyStatusOneDashboard(){
        $count = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','1')->where('id_dinas', auth()->guard('user-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

     public function countDatabyStatusOneDashboardPerusahaan(){
        $count = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','1')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

     public function countDatabyStatusTwo(){
        $count = Proposal::with('get_status')->where('id_status','2')->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDatabyStatusTwoDashboard(){
        $count = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','2')->where('id_dinas', auth()->guard('user-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDatabyStatusTwoDashboardPerusahaan(){
        $count = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','2')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

     public function countDatabyStatusThree(){
        $count = Proposal::with('get_status')->where('id_status','3')->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDatabyStatusThreeDashboard(){
        $count = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','3')->where('id_dinas', auth()->guard('user-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDatabyStatusThreeDashboardPerusahaan(){
        $count = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','3')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

     public function countDatabyStatusFour(){
        $count = Proposal::with('get_status')->where('id_status','4')->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDatabyStatusFourDashboard(){
        $count = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','4')->where('id_dinas', auth()->guard('user-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

     public function countDatabyStatusFourDashboardPerusahaan(){
        $count = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','4')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    public function countDatabyStatusFive(){
        $count = Proposal::with('get_status')->where('id_status','5')->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

   public function countDatabyStatusFiveDashboard(){
        $count = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','5')->where('id_dinas', auth()->guard('user-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

     public function countDatabyStatusFiveDashboardPerusahaan(){
        $count = Proposal::with('get_status')->with('get_user')->with('get_perusahaan')->where('id_status','5')->where('id_perusahaan', auth()->guard('perusahaan-api')->user()->id)->count();
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    // public function getDownload(){

    //     $file = public_path()."proposal";
    //     $headers = array('Content-Type: application/pdf',);
    //     return Response::download($file, 'info.pdf',$headers);
    // }

   
    
}
