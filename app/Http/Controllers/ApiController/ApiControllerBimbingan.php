<?php

namespace App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\bimbingan;
use App\Models\mahasiswa;
use App\Models\plotting;
use App\Models\dosen;
use App\Models\user;
use App\Models\sidang;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ApiControllerBimbingan extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
		$user = user::find(Auth::id());
		if($user->pengguna == 'mahasiswa'){
			$mhs = mahasiswa::find(Auth::id());
			$plot = plotting::find($mhs->plot_pembimbing);
			$dosen1 = dosen::find($plot->nip_dosen_1);
			$dosen2 = dosen::find($plot->nip_dosen_2);
			$response = ['mahasiswa'=>$mhs,'dosenPembimbing1'=>$dosen1,'dosenPembimbing2'=>$dosen2];
		}else{
			$dosen = dosen::find(Auth::id());
			$plots = plotting::where("nip_dosen_1",Auth::id())->orWhere("nip_dosen_2",Auth::id())->get();
			$mahasiswas = [];
			foreach($plots as $plot){
				$mahasiswa= mahasiswa::where("plot_pembimbing",$plot->id)->get();
				foreach($mahasiswa as $mhs){
					$bimbingan = bimbingan::where("mhs_nim",$mhs->username)->where("dsn_nip",Auth::id())->get();
					$sum = count($bimbingan);
					$mhs["bimbingan_sum"] = $sum;
					array_push($mahasiswas,$mhs);
				}
			}
			$response = ['dosen'=>$dosen,'mahasiswa'=>$mahasiswas];
		}

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
        $bimbingan = new bimbingan();
        $bimbingan->bimbingan_review = $request->bimbingan_review;
        $bimbingan->bimbingan_tanggal = Carbon::parse($request->bimbingan_tanggal)->format('Y-m-d H:i:s');
        $bimbingan->bimbingan_status = "pending";
        $bimbingan->dsn_nip = $request->dsn_nip;
        $bimbingan->mhs_nim = Auth::id();

        if (!$bimbingan->save()) {
            $response = [
                "msg" => "Sesuatu eror terjadi",
                "success" => false
            ];  
            return response()->json($response, 404);
        } else {
            $response = [
                "msg" => "Berhasil menyimpan data",
                "success" => true
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
		$user = user::find(Auth::id());
		if($user->pengguna == 'mahasiswa'){
			$mhs = mahasiswa::find(Auth::id());
			$dosen = dosen::find($id);
			$sidang = sidang::where('mhs_nim',Auth::id())->first();
			$bimbingan = bimbingan::where("dsn_nip",$id)->where("mhs_nim",Auth::id())->get();
			$pending = bimbingan::where("dsn_nip",$id)->where("mhs_nim",Auth::id())->where("bimbingan_status","pending")->get();
			$sum = count($bimbingan);
			$sumPending = count($pending);
			$mhs["bimbingan_sum"] = $sum;
			$mhs["pending_sum"] = $sumPending;
			$mhs["sidang_status"] = $sidang->sidang_status;
			$mhs["sidang_tanggal"] = $sidang->sidang_tanggal;
			$response = [
				"mahasiswa" => $mhs,
				"dosen" => $dosen,
				"bimbingan" => $bimbingan,
			];
		}else{
			$dosen = dosen::find(Auth::id());
			$mhs = mahasiswa::find($id);
			$sidang = sidang::where('mhs_nim',$id)->first();
			$bimbingan = bimbingan::where("dsn_nip",Auth::id())->where("mhs_nim",$id)->get();
			$pending = bimbingan::where("dsn_nip",Auth::id())->where("mhs_nim",$id)->where("bimbingan_status","pending")->get();
			$sum = count($bimbingan);
			$sumPending = count($pending);
			$mhs["bimbingan_sum"] = $sum;
			$mhs["pending_sum"] = $sumPending;
			$mhs["sidang_status"] = $sidang->sidang_status;
			$mhs["sidang_tanggal"] = $sidang->sidang_tanggal;
			$response = [
				"dosen" => $dosen,
				"mahasiswa" => $mhs,
				"bimbingan" => $bimbingan,
			];
		}
		
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
        
        $bimbingan = bimbingan::find($id);
        $bimbingan->bimbingan_review = $request->bimbingan_review;
        $bimbingan->bimbingan_tanggal = Carbon::parse($request->bimbingan_tanggal)->format('Y-m-d H:i:s');

        if (!$bimbingan->save()) {
            $response = [
                "msg" => "Sesuatu eror terjadi",
                "success" => false
            ];  
            return response()->json($response, 404);
        } else {
            $response = [
                "msg" => "Berhasil menyimpan data",
                "success" => true
            ];  
            return response()->json($response, 201);
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
        
        $bimbingan = bimbingan::find($id);
        
        if (!$bimbingan->delete()) {
            $response = [
                "msg" => "Sesuatu eror terjadi",
                "success" => false
            ];  
            return response()->json($response, 404);
        } else {
            $response = [
                "msg" => "Berhasil menghapus data",
                "success" => true
            ];  
            return response()->json($response, 201);
        }

    }

    public function getBimbinganSearchAllBy($parameter, $query)
    {
        
        $response = DB::table('bimbingan')
        ->join('proyek_akhir', 'proyek_akhir.proyek_akhir_id', '=', 'bimbingan.proyek_akhir_id')
        ->where($parameter, $query)
        ->get();
        
        return response()->json($response, 201);
    }

    public function getBimbinganSearchAllByTwo($parameter1, $query1, $parameter2, $query2)
    {
        
        $parameter = [$parameter1 => $query1, $parameter2 => $query2];
        $response = DB::table('bimbingan')
        ->join('proyek_akhir', 'proyek_akhir.proyek_akhir_id', '=', 'bimbingan.proyek_akhir_id')
        ->where($parameter)
        ->get();
        
        return response()->json($response, 201);
    }




    public function updateStatusBimbingan(Request $request, $id)
    {
        
        $bimbingan = bimbingan::find($id);
        $bimbingan->bimbingan_status = $request->bimbingan_status;
        
        if (!$bimbingan->save()) {
            $response = [
                "msg" => "Sesuatu eror terjadi",
                "success" => false
            ];  
            return response()->json($response, 404);
        } else {
            $response = [
                "msg" => "Berhasil menyimpan data",
                "success" => true
            ];  
            return response()->json($response, 201);
        }

    }

    public function getSiapSidang($jumlah_bimbingan){

        $parameter1 = "bimbingan.proyek_akhir_id";
        $query1 = "proyek_akhir.proyek_akhir_id";

        $parameter2 = "proyek_akhir.mhs_nim";
        $query2 = "mahasiswa.mhs_nim";
        
        $parameter3 = "mahasiswa.judul_id";
        $query3 = "judul.judul_id";

        $parameter4 = "bimbingan_status";
        $query4 = "disetujui";

        $arrayResponse = [
            'proyek_akhir.mhs_nim AS mhs_nim',
            'mahasiswa.mhs_nama AS mhs_nama',
            'mahasiswa.mhs_foto AS mhs_foto',
            'judul.judul_nama AS judul_nama',
            'proyek_akhir.nama_tim AS nama_tim'
             ];

        $parameter = [
            $parameter1 => $query1, 
            $parameter2 => $query2,
            $parameter3 => $query3,
            $parameter4 => $query4
        ];

        // $response = DB::table('bimbingan')
        // ->select(array('proyek_akhir.mhs_nim AS mhs_nim','mahasiswa.mhs_nama AS mhs_nama','mahasiswa.mhs_foto AS mhs_foto','judul.judul_nama AS judul_nama', 'proyek_akhir.nama_tim AS nama_tim', DB::raw('COUNT(*) as jumlah_bimbingan')))
        // ->join('proyek_akhir', 'proyek_akhir.proyek_akhir_id', '=', 'bimbingan.proyek_akhir_id')
        // ->join('mahasiswa', 'mahasiswa.mhs_nim', '=', 'proyek_akhir.mhs_nim')
        // ->join('judul', 'judul.judul_id', '=', 'mahasiswa.judul_id')
        // ->where($parameter)
        // ->groupBy("bimbingan.proyek_akhir_id")
        // ->havingRaw("COUNT(*) > 14")
        // ->get();

        $response = DB::select('SELECT proyek_akhir.nama_tim AS nama_tim, proyek_akhir.mhs_nim AS mhs_nim, mahasiswa.mhs_nama AS mhs_nama, mahasiswa.mhs_foto AS mhs_foto, judul.judul_nama AS judul_nama, COUNT(*) FROM bimbingan, proyek_akhir, mahasiswa, judul WHERE bimbingan.proyek_akhir_id = proyek_akhir.proyek_akhir_id and proyek_akhir.mhs_nim = mahasiswa.mhs_nim and mahasiswa.judul_id = judul.judul_id and bimbingan_status = "disetujui" GROUP BY bimbingan.proyek_akhir_id HAVING COUNT(*) >= 14');

        return response()->json($response, 201);
        
    }

}
