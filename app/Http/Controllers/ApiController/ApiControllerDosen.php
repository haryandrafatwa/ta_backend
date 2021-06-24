<?php

namespace App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\dosen;
use App\Models\user;
use App\Models\plotting;
use App\Models\sidang;
use App\Models\mahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiControllerDosen extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $response = dosen::orderBy('dsn_nama','ASC')->get();
        return response()->json($response, 201);
    }
	
	public function getCurrent(){
		$response = dosen::find(Auth::id());
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
    
        $status_user = "dosen";
        $foto_default = "default-dosen.jpg";

        $exist=user::find($request->dsn_nip);
        if($exist){
            $response = "NIP dosen sudah ada";
            return response()->json($response, 404);
        } else {
            $this->validate($request, [
                'dsn_nip' => 'required',  
                'dsn_nama' => 'required', 
                'dsn_kode' => 'required',
            ]);

            $user = new user();
            $dosen = new dosen();

            $user->username = $request->dsn_nip;
            $user->password = bcrypt($request->dsn_nip);
            $user->pengguna = $status_user;
            $user->remember_token = Str::random(10);

            $dosen->dsn_nip     = $request->dsn_nip;
            $dosen->dsn_nama    = $request->dsn_nama;
            $dosen->dsn_kode    = $request->dsn_kode;
            $dosen->dsn_foto    = $foto_default;
            $dosen->username    = $request->dsn_nip;
            
            if (!$user->save()) {
                $response = "Sesuatu eror terjadi"; 
                $showUser = 'Gagal menyimpan user';
                return response()->json($response, 404); 
            } else {
                $showUser = "Berhasil menyimpan data user dosen";
            }

            if (!$dosen->save()) {
                $response = "Sesuatu eror terjadi";
                $showDosen = "Gagal menyimpan dosen";  
                return response()->json($response, 404); 
            } else {
                $showDosen = "Berhasil menyimpan data dosen";
            }

            $response = [
                'user'      => $showUser,
                'dosen'     => $showDosen
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

        // $response = DB::table('dosen')
        // ->join('user', 'user.username', '=', 'user.username')
        // ->where('dsn_nip', $id)
        // ->get();
        
        $response = dosen::find($id);
        return response()->json($response, 201);

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
        $dosen = dosen::find($id);

        $nip = $request->dsn_nip;

        if (!empty($nip)) {
            $user->username = $nip;
            $dosen->dsn_nip = $nip;
            
            if (!$user->save()) {
                $response = "Sesuatu eror terjadi"; 
                $showUser = 'Gagal merubah user';
                return response()->json($response, 404); 
            } else {
                $showUser = "Berhasil merubah data user dosen";
            }
        
        } else {
            $showUser = "tidak merubah user dosen";
        }

        $dosen->dsn_nama    = $request->dsn_nama;
        $dosen->dsn_kode    = $request->dsn_kode;
        $dosen->dsn_kontak  = $request->dsn_kontak;
        $dosen->dsn_email   = $request->dsn_email;
        $dosen->kuota_bimbingan = $request->kuota_bimbingan;
        $dosen->kuota_reviewer = $request->kuota_reviewer;


        if (!$dosen->save()) {
            $response = "Sesuatu eror terjadi";
            $showDosen = "Gagal merubah dosen";  
            return response()->json($response, 404); 
        } else {
            $showDosen = "Berhasil merubah data dosen";
        }

        $response = [
            'user'      => $showUser,
            'dosen'     => $showDosen
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
        $dosen = dosen::find($id);
		$plotting = plotting::where('nip_pembimbing_1','=',$id)->orWhere('nip_pembimbing_2','=',$id)->get();
        
		foreach($plotting as $plot){
			$mahasiswas = mahasiswa::where("plot_id",$plot->id)->get();
			if(count($mahasiswas) >0){
				foreach($mahasiswas as $mahasiswa){
					$mahasiswa->plot_id = null;
					$mahasiswa->save();
				}
			}
			$plot->delete();
		}
		
        if (!$user->delete()) {
            $response = "Sesuatu eror terjadi"; 
            $showUser = 'Gagal menyimpan user';
            return response()->json($response, 404); 
        } else {
            $showUser = "berhasil menghapus data user dosen";
        }

        if (!$dosen->delete()) {
            $response = "Sesuatu eror terjadi";
            $showDosen = "Gagal menyimpan dosen";  
            return response()->json($response, 404); 
        } else {
            $showDosen = "berhasil menghapus data dosen";
        }

        $response = [
            'user'      => $showUser,
            'dosen'     => $showDosen
        ];

        return response()->json($response, 201);;


    }
	
	public function getMahasiswaSidang(){
		$dosen = dosen::find(Auth::id());
		$plots = plotting::where("nip_dosen_1",Auth::id())->orWhere("nip_dosen_2",Auth::id())->get();
		$mahasiswas = [];
		foreach($plots as $plot){
			$mahasiswa= mahasiswa::where("plot_penguji",$plot->id)->get();
			foreach($mahasiswa as $mhs){
				array_push($mahasiswas,$mhs);
			}
		}
        return response()->json($mahasiswas, 201);
	}
	
	public function getMahasiswaSidangByNIM($id){
		$mhs = mahasiswa::find($id);
		$plotPembimbing = plotting::find($mhs->plot_pembimbing);
		$dosenP1 = dosen::find($plotPembimbing->nip_dosen_1);
		$dosenP2 = dosen::find($plotPembimbing->nip_dosen_2);
		$plotPenguji = plotting::find($mhs->plot_penguji);
		if($plotPenguji != null){			
			$dosenR1 = dosen::find($plotPenguji->nip_dosen_1);
			$dosenR2 = dosen::find($plotPenguji->nip_dosen_2);
			$sidang = sidang::where('mhs_nim',$id)->first();
			$mhs["nip_pembimbing_1"] = $plotPembimbing->nip_dosen_1;
			$mhs["nama_pembimbing_1"] = $dosenP1->dsn_nama;
			$mhs["nip_pembimbing_2"] = $plotPembimbing->nip_dosen_2;
			$mhs["nama_pembimbing_2"] = $dosenP2->dsn_nama;
			$mhs["nip_penguji_1"] = $plotPenguji->nip_dosen_1;
			$mhs["nama_penguji_1"] = $dosenR1->dsn_nama;
			$mhs["nip_penguji_2"] = $plotPenguji->nip_dosen_2;
			$mhs["nama_penguji_2"] = $dosenR2->dsn_nama;
			if($sidang != null){
				$mhs["nilai_total"] = $sidang->nilai_total;
				$mhs["sidang_status"] = $sidang->sidang_status;
				$mhs["sidang_tanggal"] = $sidang->sidang_tanggal;
				$mhs["sidang_review"] = $sidang->sidang_review;
				$mhs["nilai_pembimbing_1"] = $sidang->nilai_pembimbing_1;
				$mhs["nilai_pembimbing_2"] = $sidang->nilai_pembimbing_2;
				$mhs["nilai_penguji_1"] = $sidang->nilai_penguji_1;
				$mhs["nilai_penguji_2"] = $sidang->nilai_penguji_2;
			}else{
				$mhs["nilai_total"] = null;
				$mhs["sidang_status"] = null;
				$mhs["sidang_tanggal"] = null;
				$mhs["sidang_review"] = null;
				$mhs["nilai_pembimbing_1"] = null;
				$mhs["nilai_pembimbing_2"] = null;
				$mhs["nilai_penguji_1"] = null;
				$mhs["nilai_penguji_2"] = null;
			}
		}else{
			$sidang = sidang::where('mhs_nim',$id)->first();
			$mhs["sidang_status"] = $sidang->sidang_status;
		}
		$response = $mhs;
        return response()->json($response, 201);
	}

    public function updatePure(Request $request, $id)
    {
        
        $user = user::find($id);
        $dosen = dosen::find($id);

        $nip = $request->dsn_nip;

        if (!empty($nip)) {
            $user->username = $nip;
            $dosen->dsn_nip = $nip;
            
            if (!$user->save()) {
                $response = "Sesuatu eror terjadi"; 
                $showUser = 'Gagal merubah user';
                return response()->json($response, 404); 
            } else {
                $showUser = "Berhasil merubah data user dosen";
            }
        
        } else {
            $showUser = "tidak merubah user dosen";
        }

        $dosen->dsn_nama    = $request->dsn_nama;
        $dosen->dsn_kode    = $request->dsn_kode;
        $dosen->dsn_kontak  = $request->dsn_kontak;
        $dosen->dsn_email   = $request->dsn_email;


        if (!$dosen->save()) {
            $response = "Sesuatu eror terjadi";
            $showDosen = "Gagal merubah dosen";  
            return response()->json($response, 404); 
        } else {
            $showDosen = "Berhasil merubah data dosen";
        }

        $response = [
            'user'      => $showUser,
            'dosen'     => $showDosen
        ];
        return response()->json($response, 201);

    }

}
