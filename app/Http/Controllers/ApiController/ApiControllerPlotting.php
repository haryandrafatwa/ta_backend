<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController\BaseController as BaseController;
use Validator;
use App\Models\plotting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\plotting as PlottingResource;

class ApiControllerPlotting extends BaseController
{
    //
	public function index()
    {
        $response = DB::table('plotting')
		->join('dosen as dsn1','dsn1.dsn_nip','=','plotting.nip_pembimbing_1')
		->join('dosen as dsn2','dsn2.dsn_nip','=','plotting.nip_pembimbing_2')
		->select('plotting.*', 'dsn1.dsn_nama AS nama_pembimbing_1', 'dsn1.dsn_kode AS kode_pembimbing_1', 'dsn2.dsn_nama AS nama_pembimbing_2', 'dsn2.dsn_kode AS kode_pembimbing_2')
		->orderBy('nama_pembimbing_1','ASC')
		->orderBy('nama_pembimbing_2','ASC')
		->get();
        // return response()->json($response, 201);
		return $this->sendResponse($response,'Plotting retrieved successfully');

    }
	
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$input = $request->all();
   
        $validator = Validator::make($input, [
            'nip_pembimbing_1' => 'required',
            'nip_pembimbing_2' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
		$matchThese = ['nip_pembimbing_1' => $request->nip_pembimbing_1,'nip_pembimbing_2' => $request->nip_pembimbing_2];
		$exist = plotting::where($matchThese)
		->get();
		if(count($exist) > 0){
			$response = "Plotting sudah tersedia";
            return $this->sendError($response);
		}else{
			$plotting = plotting::create($input);
			$plotting['success'] = true;
			$plotting['message'] = "Plotting created successfully";
			return $this->sendResponse($plotting, 'Plotting created successfully.');
		}
		
    }

/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /* $response = plotting::find($id);
        return response()->json($response, 201); */
		$response = DB::table('plotting')
		->join('dosen as dsn1','dsn1.dsn_nip','=','plotting.nip_pembimbing_1')
		->join('dosen as dsn2','dsn2.dsn_nip','=','plotting.nip_pembimbing_2')
		->select('plotting.*', 'dsn1.dsn_nama AS nama_pembimbing_1', 'dsn1.dsn_kode AS kode_pembimbing_1', 'dsn2.dsn_nama AS nama_pembimbing_2', 'dsn2.dsn_kode AS kode_pembimbing_2')
		->orderBy('nama_pembimbing_1','ASC')
		->find($id);
        return response()->json($response, 201);
		// return $this->sendResponse($response,'Plotting retrieved successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
	
	public function uploadFormExcel(Request $request) {
        
		$request->validate([
			'file' => 'required|mimes:xls,xlsx|max:2048',
		]);

        $fileName = 'form_plotting_pembimbing.'.$request->file->extension();  
		
		if(Storage::exists('form_plotting')){
            Storage::deleteDirectory('form_plotting');
		}
		
        // $request->file->move(public_path('uploads'), $fileName);
		Storage::disk('local')->put('form_plotting/'.$fileName, file_get_contents($request->file));

        $response = [
			'success' => true,
            'message' => "Upload Form Plotting Pembimbing Berhasil"
        ];

        return response()->json($response, 201);

    }
	
	public function check(){
		if(Storage::exists('form_plotting')){
			$message = "File ditemukan.";
		}else{
			$message = "File tidak ditemukan.";
		}
		$response = [
				'success' => true,
				'message' => $message
			];
        return response()->json($response, 201);
	}
	
	public function download(){
		try{
			$files = Storage::files("form_plotting");
            return response()->download(storage_path("app/".$files[0]));
        }catch(Exception $e){
            return response()->json([
                'error' => true,
                'message' => 'Form not found'
            ]);
        }
	}
	
	public function delete(){
		try{
			Storage::deleteDirectory('form_plotting');
			return response()->json([
                'success' => true,
                'message' => 'Hapus form plotting berhasil.'
            ]);
        }catch(Exception $e){
            return response()->json([
                'error' => true,
                'message' => 'Form not found'
            ]);
        }
	}
}
