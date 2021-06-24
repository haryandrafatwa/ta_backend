<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Imports\PlotImport;
use App\Http\Controllers\ApiController\BaseController as BaseController;
use App\Http\Controllers\ApiController\ApiControllerMahasiswa as ApiControllerMahasiswa;
use Validator;
use App\Models\plotting;
use App\Models\mahasiswa;
use App\Models\dosen;
use App\Models\skta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\plotting as PlottingResource;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use PDF;


class ApiControllerPlotting extends BaseController
{
    //
	public function index()
    {
        $response = DB::table('plotting')
		->join('dosen as dsn1','dsn1.dsn_nip','=','plotting.nip_dosen_1')
		->join('dosen as dsn2','dsn2.dsn_nip','=','plotting.nip_dosen_2')
		->select('plotting.*', 
		'dsn1.dsn_nama AS nama_dosen_1', 'dsn1.dsn_kode AS kode_dosen_1', 
		'dsn2.dsn_nama AS nama_dosen_2', 'dsn2.dsn_kode AS kode_dosen_2')
		->orderBy('nama_dosen_1','ASC')
		->orderBy('nama_dosen_2','ASC')
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
			$mahasiswas = (new ApiControllerMahasiswa)->index();
				$dosens = dosen::all();
				foreach($dosens as $dosen){
					$count = 0;
					foreach($mahasiswas->original as $mahasiswa){
						if($mahasiswa->nip_pembimbing_1 == $dosen->dsn_nip){
							$count += 1;
						}
						if($mahasiswa->nip_pembimbing_2 == $dosen->dsn_nip){
							$count += 1;
						}
					}
					$dosen->kuota_bimbingan = 15-$count;
					$dosen->save();
				}	
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
		->join('dosen as dsn1','dsn1.dsn_nip','=','plotting.nip_dosen_1')
		->join('dosen as dsn2','dsn2.dsn_nip','=','plotting.nip_dosen_2')
		->select('plotting.*', 
		'dsn1.dsn_nama AS nama_dosen_1', 'dsn1.dsn_kode AS kode_dosen_1', 
		'dsn2.dsn_nama AS nama_dosen_2', 'dsn2.dsn_kode AS kode_dosen_2')
		->orderBy('nama_dosen_1','ASC')
		->find($id);
        return response()->json($response, 201);
		// return $this->sendResponse($response,'Plotting retrieved successfully');
    }
	
	public function getPembimbing($id)
    {
        /* $response = plotting::find($id);
        return response()->json($response, 201); */
		$response = DB::table('plotting')
		->join('dosen as dsn1','dsn1.dsn_nip','=','plotting.nip_dosen_1')
		->join('dosen as dsn2','dsn2.dsn_nip','=','plotting.nip_dosen_2')
		->select('plotting.id','plotting.nip_dosen_1 as nip_pembimbing_1','plotting.nip_dosen_2 as nip_pembimbing_2', 
		'dsn1.dsn_nama AS nama_pembimbing_1', 'dsn1.dsn_kode AS kode_pembimbing_1', 
		'dsn2.dsn_nama AS nama_pembimbing_2', 'dsn2.dsn_kode AS kode_pembimbing_2')
		->orderBy('nama_pembimbing_1','ASC')
		->find($id);
        return response()->json($response, 201);
		// return $this->sendResponse($response,'Plotting retrieved successfully');
    }
	
	public function getPenguji($id)
    {
        /* $response = plotting::find($id);
        return response()->json($response, 201); */
		$response = DB::table('plotting')
		->join('dosen as dsn1','dsn1.dsn_nip','=','plotting.nip_dosen_1')
		->join('dosen as dsn2','dsn2.dsn_nip','=','plotting.nip_dosen_2')
		->select('plotting.id','plotting.nip_dosen_1 as nip_penguji_1','plotting.nip_dosen_2 as nip_penguji_2', 
		'dsn1.dsn_nama AS nama_penguji_1', 'dsn1.dsn_kode AS kode_penguji_1', 
		'dsn2.dsn_nama AS nama_penguji_2', 'dsn2.dsn_kode AS kode_penguji_2')
		->orderBy('nama_penguji_1','ASC')
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
			$plotting = plotting::find($id);
			$plotting->nip_pembimbing_1 = $request->nip_pembimbing_1;
			$plotting->nip_pembimbing_2 = $request->nip_pembimbing_2;
					
			if (!$plotting->save()) {
				$response = [
					"msg" => "Sesuatu eror terjadi",
					"success" => false
				];   
				return response()->json($response, 404);
			} else {
				$mahasiswas = (new ApiControllerMahasiswa)->index();
				$dosens = dosen::all();
				foreach($dosens as $dosen){
					$count = 0;
					foreach($mahasiswas->original as $mahasiswa){
						if($mahasiswa->nip_pembimbing_1 == $dosen->dsn_nip){
							$count += 1;
						}
						if($mahasiswa->nip_pembimbing_2 == $dosen->dsn_nip){
							$count += 1;
						}
					}
					$dosen->kuota_bimbingan = 15-$count;
					$dosen->save();
				}	
				$response['success'] = true;
				$response['message'] = "Plotting updated successfully";
				return response()->json($response, 201);
			}
			return $this->sendResponse($plotting, 'Plotting updated successfully.');
			return response()->json($dosens, 201);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		
		$mahasiswas = mahasiswa::where("plot_id",$id)->get();
		if(count($mahasiswas) >0){
			foreach($mahasiswas as $mahasiswa){
				$mahasiswa->plot_id = null;
				$mahasiswa->save();
			}
		}
		
		$plotting = plotting::find($id);
        
        if (!$plotting->delete()) {
            $response = [
                "msg" => "Sesuatu eror terjadi",
                "success" => false
            ];  
            return response()->json($response, 404);
        } else {
			$mahasiswas = (new ApiControllerMahasiswa)->index();
				$dosens = dosen::all();
				foreach($dosens as $dosen){
					$count = 0;
					foreach($mahasiswas->original as $mahasiswa){
						if($mahasiswa->nip_pembimbing_1 == $dosen->dsn_nip){
							$count += 1;
						}
						if($mahasiswa->nip_pembimbing_2 == $dosen->dsn_nip){
							$count += 1;
						}
					}
					$dosen->kuota_bimbingan = 15-$count;
					$dosen->save();
				}	
            $response = [
                "msg" => "Berhasil menghapus data",
                "success" => true
            ];  
            return response()->json($response, 201);
        }
        return response()->json($response, 201);
    }
	
	public function uploadFormExcel(Request $request) {
        
		$request->validate([
			'file' => 'required|mimes:xls,xlsx|max:2048',
		]);

        $fileName = date('Ymd').'_form_plotting_pembimbing.'.$request->file->extension();  
				
        // $request->file->move(public_path('uploads'), $fileName);
		Storage::disk('local')->put('form_plotting/'.$fileName, file_get_contents($request->file));
		
		try {
			$files = Storage::files("form_plotting");
			$theArray = Excel::toArray([],storage_path("app/".$files[0]));
			$array = $theArray[0];
			$removed = array_shift($array);
			$new = [];
			foreach($array as $rows){
				$data = $rows;
				unset ($data[count($data)-1]);
				array_push($new,$data);
			}
			foreach($new as $rows){
				$data = $rows;
				// $dosen1 = DB::table('dosen')->where("dsn_kode",$data[0])->first();
				$dosen1 = dosen::where("dsn_kode",$data[0])->first();
				// $dosen2 = DB::table('dosen')->where("dsn_kode",$data[1])->first();
				$dosen2 = dosen::where("dsn_kode",$data[1])->first();
				if(!empty($dosen1) && !empty($dosen2)){
					$matchThese = ['nip_dosen_1' => $dosen1->dsn_nip,'nip_dosen_2' => $dosen2->dsn_nip];
					$plotting = plotting::where($matchThese)->first();
					Carbon::setlocale("id");
					$carbon = Carbon::now();
					if(empty($plotting)){
						$plotting = new plotting();
						$plotting->nip_dosen_1 = $dosen1->dsn_nip;
						$plotting->nip_dosen_2 = $dosen2->dsn_nip;
						$plotting->save();
					}
					$mahasiswa = mahasiswa::where("username",$data[2])->first();
					$skta = skta::where("mhs_nim",$data[2])->first();
					if(!empty($mahasiswa)){
						$mahasiswa->plot_pembimbing = $plotting->id;
						$skta->sk_terbit = Carbon::now();
						$skta->sk_expired = $carbon->addMonth(6);
						$skta->sk_status = 2;
						$skta->save();
						$mahasiswa->judul = $data[3];
						$mahasiswa->judul_inggris = $data[4];
						$mahasiswa->save();						
						$dataMahasiswa = mahasiswa::where("username",$data[2])->first();
						$dataPembimbing = $this->getPembimbing($mahasiswa->plot_pembimbing)->original;
						$data = [
							"mahasiswa" => $dataMahasiswa,
							"pembimbing" => $dataPembimbing,
							"expired" => $carbon->translatedFormat('d F Y'),
							"persetujuan" => $carbon->subMonth(6)->translatedFormat('d F Y')
						];
						$pdf = PDF::loadView('skPDF', $data);
						$fileNameSK = $dataMahasiswa->username.'_sk_ta.pdf';  
						Storage::disk('local')->put('skta/'.$dataMahasiswa->username.'/'.$fileNameSK,$pdf->download()->getOriginalContent());
						/* $mahasiswas = (new ApiControllerMahasiswa)->index();
						$dosens = dosen::all();
						foreach($dosens as $dosen){
							$count = 0;
							foreach($mahasiswas->original as $mahasiswa){
								if($mahasiswa->nip_pembimbing_1 == $dosen->dsn_nip){
									$count += 1;
								}
								if($mahasiswa->nip_pembimbing_2 == $dosen->dsn_nip){
									$count += 1;
								}
							}
							$dosen->kuota_bimbingan = 15-$count;
							$dosen->save();
						}	 */
					}
				}
			}
			$response = [
				'success' => true,
				'message' => $dataPembimbing
			];
        } catch (\Exception $e) {
			$response = [
				'error' => true,
				'message' => $e->getMessage()
			];
        }

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
