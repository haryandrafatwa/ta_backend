<?php

namespace App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiController\BaseController as BaseController;
use App\Http\Controllers\ApiController\ApiControllerPlotting as ApiControllerPlotting;
use Illuminate\Http\Request;
use App\Models\mahasiswa;
use App\Models\skta;
use App\Models\user;
use App\Models\dosen;
use App\Models\sidang;
use App\Models\plotting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;

class ApiControllerMahasiswa extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		
		skta::where('sk_expired','<',Carbon::now())
		->where('sk_status',2)
		->update(['sk_status'=>3]);
		
		$response = DB::table('mahasiswa')
        ->leftjoin('plotting as pembimbing', 'pembimbing.id', '=', 'mahasiswa.plot_pembimbing')
        ->leftjoin('plotting as penguji', 'penguji.id', '=', 'mahasiswa.plot_penguji')
        ->leftjoin('skta', 'skta.mhs_nim', '=', 'mahasiswa.username')
        ->leftjoin('sidang', 'sidang.mhs_nim', '=', 'mahasiswa.username')
		->select('mahasiswa.*', 'pembimbing.nip_dosen_1 as nip_pembimbing_1', 'pembimbing.nip_dosen_2 as nip_pembimbing_2',
		'penguji.nip_dosen_1 as nip_pembimbing_1', 'penguji.nip_dosen_2 as nip_pembimbing_2',		
		'skta.sk_expired as sk_expired', 'skta.sk_status', 'sidang.sidang_tanggal', 'sidang.sidang_status')
		->orderBy('mhs_nama','ASC')
        ->get();
		
        return response()->json($response, 201);
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
        
        $status_user        = "mahasiswa";
        $foto_default       = "default-mahasiswa.jpg";

        $this->validate($request, [
            'mhs_nim' => 'required',
            'mhs_nama' => 'required'
        ]);

        $exist = user::find($request->mhs_nim);
        if($exist){
            $response = "NIM mahasiswa sudah ada";
            return response()->json($response, 404);
        }else {

            $user = new user();
            $mahasiswa = new mahasiswa();
			$skta = new skta();

            $user->username = $request->mhs_nim;
            $user->password = bcrypt($request->mhs_nim);
            $user->pengguna = $status_user;
            $user->remember_token = Str::random(10);

            $angkatan = '20'. substr($request->mhs_nim, 4,2);

            $mahasiswa->mhs_nim     = $request->mhs_nim;
            $mahasiswa->mhs_nama    = $request->mhs_nama;
            $mahasiswa->angkatan    = $angkatan;
            $mahasiswa->mhs_foto    = $foto_default;
            $mahasiswa->username    = $request->mhs_nim;
			
			$skta->mhs_nim = $request->mhs_nim;
            $skta->sk_status   = 1;

            if (!$user->save()) {
                $response = "Sesuatu eror terjadi"; 
                $showUser = 'Gagal menyimpan user';
                return response()->json($response, 404); 
            } else {
                $showUser = "berhasil menambahkan data user";
            }

            if (!$mahasiswa->save()) {
                $response = "Sesuatu eror terjadi";
                $showMahasiswa = "Gagal menyimpan mahasiswa";  
                return response()->json($response, 404); 
            } else {
                $showMahasiswa = "berhasil menambahkan data mahasiswa";
            }
			if (!$skta->save()) {
                $response = "Sesuatu eror terjadi";
                $showSKTA = "Gagal menyimpan skta";  
                return response()->json($response, 404); 
            } else {
                $showSKTA = "berhasil menambahkan data skta";
            }

            $response = [
                'user'      => $showUser,
                'mahasiswa' => $showMahasiswa,
                'skta' => $showSKTA,
            ];

            return response()->json($response, 201);

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
		skta::where('sk_expired','<',Carbon::now())
		->where('sk_status',2)
		->update(['sk_status'=>3]);

        $response = DB::table('mahasiswa')
        ->leftjoin('plotting as pembimbing', 'pembimbing.id', '=', 'mahasiswa.plot_pembimbing')
        ->leftjoin('plotting as penguji', 'penguji.id', '=', 'mahasiswa.plot_penguji')
        ->leftjoin('skta', 'skta.mhs_nim', '=', 'mahasiswa.username')
        ->leftjoin('sidang', 'sidang.mhs_nim', '=', 'mahasiswa.username')
		->select('mahasiswa.*', 'pembimbing.nip_dosen_1 as nip_pembimbing_1', 'pembimbing.nip_dosen_2 as nip_pembimbing_2',
		'penguji.nip_dosen_1 as nip_pembimbing_1', 'penguji.nip_dosen_2 as nip_pembimbing_2',		
		'skta.sk_expired as sk_expired', 'skta.sk_status', 'sidang.sidang_tanggal', 'sidang.sidang_status', 'sidang.nilai_total')
		->where('username','=',$id)
        ->first();
        return response()->json($response, 200);

        // $response = mahasiswa::find($id);
        // return response()->json($response, 200);

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

        $user = user::find($id);
        $mahasiswa = mahasiswa::find($id);

        $nim = $request->mhs_nim;

        if (!empty($nim)){
            $user->username         = $request->mhs_nim;
            $mahasiswa->mhs_nim     = $request->mhs_nim;

            if (!$user->save()) {
                $response = "Sesuatu eror terjadi"; 
                $showUser = 'Gagal merubah user';
                return response()->json($response, 404); 
            } else {
                $showUser = "berhasil merubah data user mahasiswa";
            }

        } else {
            $showUser = "tidak merubah user mahasiswa";
        }


        $mahasiswa->mhs_nama    = $request->mhs_nama;
        $mahasiswa->angkatan    = $request->mhs_angkatan;
        $mahasiswa->mhs_kontak  = $request->mhs_kontak;
        $mahasiswa->mhs_email   = $request->mhs_email;
        $mahasiswa->judul  = $request->judul;
        $mahasiswa->judul_inggris   = $request->judul_inggris;

        if (!$mahasiswa->save()) {
            $response = "Sesuatu eror terjadi";
            $showMahasiswa = "Gagal merubah dosen";  
            return response()->json($response, 404); 
        } else {
            $showMahasiswa = "berhasil merubah data mahasiswa";
        }

        $response = [
            'user'      => $showUser,
            'mahasiswa' => $showMahasiswa
        ];

        return response()->json($response, 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $user = user::find($id);
        $mahasiswa = mahasiswa::find($id);
        $skta = skta::where("mhs_nim",$id);
		
		if (!$skta->delete()) {
            $response = "Sesuatu eror terjadi"; 
            $showSKTA = 'Gagal menyimpan skta';
            return response()->json($response, 404); 
        } else {
            $showSKTA = "berhasil menghapus data skta";
        }
        
        if (!$user->delete()) {
            $response = "Sesuatu eror terjadi"; 
            $showUser = 'Gagal menyimpan user';
            return response()->json($response, 404); 
        } else {
            $showUser = "berhasil menghapus data user mahasiswa";
        }

        if (!$mahasiswa->delete()) {
            $response = "Sesuatu eror terjadi";
            $showMahasiswa = "Gagal menyimpan dosen";  
            return response()->json($response, 404); 
        } else {
            $showMahasiswa = "berhasil menghapus data mahasiswa";
        }

        $response = [
            'user'      => $showUser,
            'mahasiswa' => $showMahasiswa,
			'skta' => $showSKTA
        ];

        return response()->json($response, 201);;

    }
	
    public function updateSKMahasiswa(Request $request, $id) {

        $mahasiswa = mahasiswa::find($id);
		$skta = skta::where('mhs_nim',$id)->first();
        $sk_expired = $skta->sk_expired;
		
		if($sk_expired != null){
			$carbon = new Carbon($skta->sk_expired);
		}else{
			$carbon = Carbon::now();
			$skta->sk_terbit = $carbon;
		}
		$newSkExpired = $carbon->addMonth(3);
        $skta->sk_expired = $newSkExpired;
		$skta->sk_status = 2;
		$dataPembimbing = (new ApiControllerPlotting)->show($mahasiswa->plot_id)->original;
		$data = [
			"mahasiswa" => $mahasiswa,
			"pembimbing" => $dataPembimbing,
			"expired" => $newSkExpired->translatedFormat('d F Y'),
			"persetujuan" => $carbon->translatedFormat('d F Y')
		];
						
		$pdf = PDF::loadView('skPDF', $data);
		$fileNameSK = $mahasiswa->username.'_sk_ta.pdf';  
		Storage::disk('local')->put('skta/'.$mahasiswa->username.'/'.$fileNameSK,$pdf->download()->getOriginalContent());

        if (!$skta->save()) {
            $response = "Sesuatu eror terjadi";
            $message = "Gagal update SK TA";  
            return response()->json($response, 404); 
        } else {
            $message = "Berhasil update SK TA.";
        }

        $response = [
            'mahasiswa' => $message
        ];

        return response()->json($response, 201);

    }
	
	public function downloadSKTA($id){
		try{
			$files = Storage::files("skta/".$id);
            return response()->download(storage_path("app/".$files[0]));
        }catch(Exception $e){
            return response()->json([
                'error' => true,
                'message' => 'Form not found'
            ]);
        }
	}
	
	public function deletePembimbing(Request $request,$mhs_nim){
		$mahasiswa = mahasiswa::find($mhs_nim);
		$mahasiswa->plot_id = null;
		if (!$mahasiswa->save()) {
            $response = "Sesuatu eror terjadi";
            $message = "Gagal delete pembimbing";  
            return response()->json($response, 404); 
        } else {
            $message = "Berhasil hapus pembimbing";
        }

        $response = [
            'message' => $message
        ];

        return response()->json($response, 201);
		
	}
	
	public function addPembimbing(Request $request,$mhs_nim){		
		$mahasiswa = mahasiswa::find($mhs_nim);
		$request->validate([
			'plot_id' => 'required'
		]);
		
		$mahasiswa->plot_id = $request->plot_id;
		if (!$mahasiswa->save()) {
            $response = "Sesuatu eror terjadi";
            $showMahasiswa = "Gagal tambah pembimbing";  
            return response()->sendError($response);
        } else {
            $showMahasiswa = "Berhasil tambah pembimbing.";
        }
		$response = [
            'mahasiswa' => $showMahasiswa
        ];

        return $this->sendResponse($response,'Add pembimbing successfully.');
		
	}
	
	public function askSidangTerjadwal(){
		$mahasiswa = mahasiswa::all();
		$mahas = [];
		foreach($mahasiswa as $mhs){
			$skta = skta::where('mhs_nim',$mhs->username)->first();
			$mhs['sk'] = $skta;
			$start = new Carbon($skta->sk_terbit);
			$end = new Carbon($skta->sk_expired);
			$diff = $end->diffInMonths($start);
			if($skta->sk_status == 2){
				if($diff == 6 || $diff == 12){
					$sidang = sidang::where('mhs_nim',$mhs->username)->first();
					if($sidang == null){
						$sidang = new sidang();
						$sidang->sidang_status = 'konfirmasi';
						$sidang->mhs_nim = $mhs->username;
						$sidang->save();
					}else{
						if($sidang->sidang_status != 'lulus'){
							$sidang->nilai_total = null;
							$sidang->sidang_status = 'konfirmasi';
							$sidang->save();
						}
					}
					array_push($mahas,$mhs);
				}
			}
		}
		if(count($mahas) < 1){
			$response = [
				'error' => true,
				'message' => 'Prodi belum plotting pembimbing!'
			];
			return response()->json($response, 404);
		}else{
			$response = [
				'success' => true,
				'message' => 'Berhasil memasukkan data'
			];
			return response()->json($response, 201);
		}
	}
	
	public function konfirmasiSidang(Request $request){
		$sidang = sidang::where('mhs_nim',Auth::id())->first();
		if($request->konfirmasi == 'diterima'){
			$sidang->sidang_status = 'dijadwalkan';
			$sidang->nilai_total = null;
		}else{
			$sidang->sidang_status = 'ditolak';
			$sidang->nilai_total = 0;
		}
		
		if(!$sidang->save()){
			$response = [
				'error' => true,
				'message' => 'Gagal menyimpan data'
			];
			return response()->json($response, 404);
		}else{
			$response = [
				'success' => true,
				'message' => 'Berhasil menyimpan data'
			];
			return response()->json($response, 201);
		}
	}
	
	public function perpanjangSKTA(Request $request, $id){
		try {
			$skta = skta::where('mhs_nim',$id)->first();
			$start = new Carbon($skta->sk_terbit);
			$end = new Carbon($skta->sk_expired);
			$diff = $end->diffInMonths($start);
			if($diff == 6){
				$status = 4;
			}else if($diff == 9){
				$status = 5;
			}else if($diff == 12){
				$status = 6;
			}
			$skta->sk_status = $status;
			if(!$skta->save()){
				$response = [
					'error' => true,
					'message' => 'Terjadi kesalahan!'
				];
			}else{
				$request->validate([
					'file' => 'required|mimes:pdf|max:2048',
				]);
				$fileName = date('Ymd').'_pengajuan_sk_'.$id.'.'.$request->file->extension();  
				Storage::disk('local')->put('pengajuan_sk/'.$id.'/'.$fileName, file_get_contents($request->file));
				$response = [
				'success' => true,
				'message' => 'Berhasil upload pengajuan SK',
				'skta' => $status
			];
			}
			$response = [
				'success' => true,
				'message' => 'Berhasil upload pengajuan SK',
				'skta' => $status
			];
		} catch (\Exception $e) {
			$response = [
				'error' => true,
				'message' => $e->getMessage()
			];
        }
		return response()->json($response, 201);
	}
	
	public function uploadFormSidang(Request $request) {
		
		try {        
			$request->validate([
				'file' => 'required|mimes:xls,xlsx|max:2048',
			]);

			$fileName = date('Ymd').'_form_sidang_terjadwal.'.$request->file->extension();  
					
			Storage::disk('local')->put('form_sidang/'.$fileName, file_get_contents($request->file));
			
			$files = Storage::files("form_sidang");
			$theArray = Excel::toArray([],storage_path("app/".$files[0]));
			$array = $theArray[0];
			$removed = array_shift($array);
			$mahasiswa = [];
			foreach($array as $data){
				$mhs = mahasiswa::where("username",$data[0])->first();
				$sidang = sidang::where('mhs_nim',$mhs->username)->first();
				$dosen1 = dosen::where("dsn_kode",$data[1])->first();
				$dosen2 = dosen::where("dsn_kode",$data[2])->first();
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
					if(!empty($mhs)){
						if($sidang->sidang_status == 'dijadwalkan'){
							$mhs->plot_penguji = $plotting->id;
							$mhs->save();		
							$sidang->sidang_tanggal = Carbon::createFromFormat('d/m/Y', $data[3])->format('Y-m-d H:i:s');
							$sidang->jam_mulai = date('H:i:s', strtotime($data[4]));
							$sidang->jam_berakhir = date('H:i:s', strtotime($data[5]));
							$sidang->sidang_status = 'terjadwalkan';
							$sidang->save();
						}						
					}
				}
			}
			
			$response = [
				'success' => true,
				'message' => $mahasiswa
			];
        } catch (\Exception $e) {
			$response = [
				'error' => true,
				'message' => $e->getMessage()
			];
        }

        return response()->json($response, 201);
	}
}
