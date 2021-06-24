<?php


namespace App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiController\ApiControllerDosen as ApiControllerDosen;
use App\Http\Controllers\ApiController\ApiControllerBimbingan as ApiControllerBimbingan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\sidang;
use App\Models\plotting;
use App\Models\mahasiswa;
use App\Models\nilai;
use App\Models\dosen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;
use Carbon\Carbon;

class ApiControllerSidang extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $response = DB::table('sidang')
        ->select($this->arrayResponse)
        ->join('proyek_akhir', 'proyek_akhir.proyek_akhir_id', '=', 'sidang.proyek_akhir_id')
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
        
        $sidang = new sidang();
        $sidang->sidang_review         = $request->sidang_review;
        $sidang->sidang_tanggal        = $request->sidang_tanggal;
        $sidang->nilai_proposal        = $request->nilai_proposal;
        $sidang->nilai_penguji_1       = $request->nilai_penguji_1;
        $sidang->nilai_penguji_2       = $request->nilai_penguji_2;
        $sidang->nilai_pembimbing      = $request->nilai_pembimbing;
        $sidang->nilai_total           = $request->nilai_total;
        $sidang->sidang_status         = $request->sidang_status;
        $sidang->proyek_akhir_id       = $request->proyek_akhir_id;

        if (!$sidang->save()) {
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
        $response = sidang::find($id);
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
        
        $sidang = sidang::find($id);
        $sidang->sidang_review         = $request->sidang_review;
        $sidang->sidang_tanggal        = $request->sidang_tanggal;
        $sidang->nilai_proposal        = $request->nilai_proposal;
        $sidang->nilai_penguji_1       = $request->nilai_penguji_1;
        $sidang->nilai_penguji_2       = $request->nilai_penguji_2;
        $sidang->nilai_pembimbing      = $request->nilai_pembimbing;
        $sidang->nilai_total           = $request->nilai_total;
        $sidang->sidang_status         = $request->sidang_status;

        if (!$sidang->save()) {
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
        $sidang = sidang::find($id);
        
        if (!$sidang->delete()) {
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

    public function searchAllSidangBy($parameter, $query){
        
        $response = DB::table('sidang')
        ->select($this->arrayResponse)
        ->join('proyek_akhir', 'proyek_akhir.proyek_akhir_id', '=', 'sidang.proyek_akhir_id')
        ->where($parameter, $query)
        ->get();

        return response()->json($response, 201);
    }


    public function searchAllSidangByTwo($parameter1, $query1, $parameter2, $query2){
        $parameter = [$parameter1 => $query1, $parameter2 => $query2];
        $response = DB::table('sidang')
        ->select($this->arrayResponse)
        ->join('proyek_akhir', 'proyek_akhir.proyek_akhir_id', '=', 'sidang.proyek_akhir_id')
        ->where($parameter)
        ->get();
        return response()->json($response, 201);
    }
	
	public function getNilaiSidang($id){
		$sidang = sidang::where('mhs_nim',$id)->first();
		$nilai_pembimbing_1 = nilai::find($sidang->nilai_pembimbing_1);
		$nilai_pembimbing_2 = nilai::find($sidang->nilai_pembimbing_2);
		$nilai_penguji_1 = nilai::find($sidang->nilai_penguji_1);
		$nilai_penguji_2 = nilai::find($sidang->nilai_penguji_2);
		if($nilai_penguji_1 != null){
			$sidang['nilai_penguji_1_clo1'] = $nilai_penguji_1->clo_1;
			$sidang['nilai_penguji_1_clo2'] = $nilai_penguji_1->clo_2;
			$sidang['nilai_penguji_1_clo3'] = $nilai_penguji_1->clo_3;
		}else{
			$sidang['nilai_penguji_1_clo1'] = null;
			$sidang['nilai_penguji_1_clo2'] = null;
			$sidang['nilai_penguji_1_clo3'] = null;
		}
		if($nilai_penguji_2 != null){
			$sidang['nilai_penguji_2_clo1'] = $nilai_penguji_2->clo_1;
			$sidang['nilai_penguji_2_clo2'] = $nilai_penguji_2->clo_2;
			$sidang['nilai_penguji_2_clo3'] = $nilai_penguji_2->clo_3;
		}else{
			$sidang['nilai_penguji_2_clo1'] = null;
			$sidang['nilai_penguji_2_clo2'] = null;
			$sidang['nilai_penguji_2_clo3'] = null;
		}
		if($nilai_pembimbing_1 != null){
			$sidang['nilai_pembimbing_1_clo1'] = $nilai_pembimbing_1->clo_1;
			$sidang['nilai_pembimbing_1_clo2'] = $nilai_pembimbing_1->clo_2;
			$sidang['nilai_pembimbing_1_clo3'] = $nilai_pembimbing_1->clo_3;
		}else{
			$sidang['nilai_pembimbing_1_clo1'] = null;
			$sidang['nilai_pembimbing_1_clo2'] = null;
			$sidang['nilai_pembimbing_1_clo3'] = null;
		}
		if($nilai_pembimbing_2 != null){
			$sidang['nilai_pembimbing_2_clo1'] = $nilai_pembimbing_2->clo_1;
			$sidang['nilai_pembimbing_2_clo2'] = $nilai_pembimbing_2->clo_2;
			$sidang['nilai_pembimbing_2_clo3'] = $nilai_pembimbing_2->clo_3;
		}else{
			$sidang['nilai_pembimbing_2_clo1'] = null;
			$sidang['nilai_pembimbing_2_clo2'] = null;
			$sidang['nilai_pembimbing_2_clo3'] = null;
		}
        return response()->json($sidang, 201);
	}
	
	public function getBeritaAcara($id){
		$sidang = sidang::where('mhs_nim',$id)->first();
		$nilai_pembimbing_1 = nilai::find($sidang->nilai_pembimbing_1);
		$nilai_pembimbing_2 = nilai::find($sidang->nilai_pembimbing_2);
		$nilai_penguji_1 = nilai::find($sidang->nilai_penguji_1);
		$nilai_penguji_2 = nilai::find($sidang->nilai_penguji_2);
			$sidang['nilai_penguji_1_clo1'] = $nilai_penguji_1->clo_1;
			$sidang['nilai_penguji_1_clo2'] = $nilai_penguji_1->clo_2;
			$sidang['nilai_penguji_1_clo3'] = $nilai_penguji_1->clo_3;
			$sidang['nilai_penguji_2_clo1'] = $nilai_penguji_1->clo_1;
			$sidang['nilai_penguji_2_clo2'] = $nilai_penguji_1->clo_2;
			$sidang['nilai_penguji_2_clo3'] = $nilai_penguji_1->clo_3;
			$sidang['nilai_pembimbing_1_clo1'] = $nilai_pembimbing_1->clo_1;
			$sidang['nilai_pembimbing_1_clo2'] = $nilai_pembimbing_1->clo_2;
			$sidang['nilai_pembimbing_1_clo3'] = $nilai_pembimbing_1->clo_3;
			$sidang['nilai_pembimbing_2_clo1'] = $nilai_pembimbing_2->clo_1;
			$sidang['nilai_pembimbing_2_clo2'] = $nilai_pembimbing_2->clo_2;
			$sidang['nilai_pembimbing_2_clo3'] = $nilai_pembimbing_2->clo_3;
		$mhs = mahasiswa::find($id);
			$plotPembimbing = plotting::find($mhs->plot_pembimbing);
		$plotPenguji = plotting::find($mhs->plot_penguji);
		$dosenP1 = dosen::find($plotPembimbing->nip_dosen_1);
		$dosenP2 = dosen::find($plotPembimbing->nip_dosen_2);
		$dosenR1 = dosen::find($plotPenguji->nip_dosen_1);
		$dosenR2 = dosen::find($plotPenguji->nip_dosen_2);
		$sidang["mhs_nama"] = $mhs->mhs_nama;
		$sidang["judul"] = $mhs->judul;
		$sidang["nip_pembimbing_1"] = $plotPembimbing->nip_dosen_1;
		$sidang["nama_pembimbing_1"] = $dosenP1->dsn_nama;
		$sidang["nilai_pembimbing_1"] = $sidang->nilai_pembimbing_1;
		$sidang["nip_pembimbing_2"] = $plotPembimbing->nip_dosen_2;
		$sidang["nama_pembimbing_2"] = $dosenP2->dsn_nama;
		$sidang["nilai_pembimbing_2"] = $sidang->nilai_pembimbing_2;
		$sidang["nip_penguji_1"] = $plotPenguji->nip_dosen_1;
		$sidang["nama_penguji_1"] = $dosenR1->dsn_nama;
		$sidang["nilai_penguji_1"] = $sidang->nilai_penguji_1;
		$sidang["nip_penguji_2"] = $plotPenguji->nip_dosen_2;
		$sidang["nama_penguji_2"] = $dosenR2->dsn_nama;
		$sidang["nilai_penguji_2"] = $sidang->nilai_penguji_2;
		$indexNilai = array('A'=>4, 'AB'=>3.5, 'B'=>3, 'BC'=>2.5, 'C'=>2, 'D'=>1, 'E'=>0);
			$int_p_1_clo1 = $indexNilai[$nilai_pembimbing_1->clo_1];
			$int_p_1_clo2 = $indexNilai[$nilai_pembimbing_1->clo_2];
			$int_p_1_clo3 = $indexNilai[$nilai_pembimbing_1->clo_3];
			$int_p_2_clo1 = $indexNilai[$nilai_pembimbing_2->clo_1];
			$int_p_2_clo2 = $indexNilai[$nilai_pembimbing_2->clo_2];
			$int_p_2_clo3 = $indexNilai[$nilai_pembimbing_2->clo_3];
			$int_r_1_clo1 = $indexNilai[$nilai_penguji_1->clo_1];
			$int_r_1_clo2 = $indexNilai[$nilai_penguji_1->clo_2];
			$int_r_1_clo3 = $indexNilai[$nilai_penguji_1->clo_3];
			$int_r_2_clo1 = $indexNilai[$nilai_penguji_2->clo_1];
			$int_r_2_clo2 = $indexNilai[$nilai_penguji_2->clo_2];
			$int_r_2_clo3 = $indexNilai[$nilai_penguji_2->clo_3];
			$Ra_clo1 = ($int_p_1_clo1+$int_p_2_clo1)/2;
			$Ra_clo2 = ($int_p_1_clo2+$int_p_2_clo2)/2;
			$Ra_clo3 = ($int_p_1_clo3+$int_p_2_clo3)/2;
			$Rb_clo1 = ($int_r_1_clo1+$int_r_2_clo1)/2;
			$Rb_clo2 = ($int_r_1_clo2+$int_r_2_clo2)/2;
			$Rb_clo3 = ($int_r_1_clo3+$int_r_2_clo3)/2;
			$nhp_clo1 = (60/100*$Ra_clo1)+(40/100*$Rb_clo1);
			$nhp_clo2 = (60/100*$Ra_clo2)+(40/100*$Rb_clo2);
			$nhp_clo3 = (60/100*$Ra_clo3)+(40/100*$Rb_clo3);
			$indexAkhir = ($nhp_clo1*35/100)+($nhp_clo2*30/100)+($nhp_clo3*35/100);
		if($sidang->sidang_status == 'lulus'){
			$statusSidang = "<b>Lulus / <strike>Lulus Bersyarat</strike> / <strike>Tidak Lulus</strike></b>";
		}else if($sidang->sidang_status == 'tidak lulus'){
			$statusSidang = "<b><strike>Lulus</strike> / <strike>Lulus Bersyarat</strike> / Tidak Lulus</b>";
		}else{
			$statusSidang = "<b><strike>Lulus</strike> / Lulus Bersyarat / <strike>Tidak Lulus</strike></b>";
		}
		if($sidang->nilai_total == 'A'){
			$indexNilai = "<b>A / <strike>AB</strike> / <strike>B</strike> / <strike>BC</strike> / <strike>C</strike> 
			/ <strike>D</strike> / <strike>E</strike></b>";
		}else if($sidang->nilai_total == 'AB'){
			$indexNilai = "<b><strike>A</strike> / AB / <strike>B</strike> / <strike>BC</strike> / <strike>C</strike> 
			/ <strike>D</strike> / <strike>E</strike></b>";
		}else if($sidang->nilai_total == 'B'){
			$indexNilai = "<b><strike>A</strike> / <strike>AB</strike> / B / <strike>BC</strike> / <strike>C</strike> 
			/ <strike>D</strike> / <strike>E</strike></b>";
		}else if($sidang->nilai_total == 'BC'){
			$indexNilai = "<b><strike>A</strike> / <strike>AB</strike> / <strike>B</strike> / BC / <strike>C</strike> 
			/ <strike>D</strike> / <strike>E</strike></b>";
		}else if($sidang->nilai_total == 'C'){
			$indexNilai = "<b><strike>A</strike> / <strike>AB</strike> / <strike>B</strike> / <strike>BC</strike> / C 
			/ <strike>D</strike> / <strike>E</strike></b>";
		}else if($sidang->nilai_total == 'D'){
			$indexNilai = "<b><strike>A</strike> / <strike>AB</strike> / <strike>B</strike> / <strike>BC</strike> / 
			<strike>C</strike> / D / <strike>E</strike></b>";
		}else if($sidang->nilai_total == 'D'){
			$indexNilai = "<b><strike>A</strike> / <strike>AB</strike> / <strike>B</strike> / <strike>BC</strike> / 
			<strike>C</strike> / <strike>D</strike> / E</b>";
		}
		$sidang['waktu_mulai'] = Carbon::parse($sidang->jam_mulai)->locale('id')->translatedFormat('H:i');
		$carbon = Carbon::parse($sidang->sidang_tanggal);
		$sidang["sidangStatus"] = $statusSidang;
		$sidang["indexNilai"] = $indexNilai;
		$sidang['tanggal_sidang'] = $carbon->locale('id')->translatedFormat('d F Y');
		$sidang["deadline"] = $carbon->addDays(15)->locale('id')->translatedFormat('d F Y');
		$sidang["ARa"] = $Ra_clo1;
		$sidang["BRa"] = $Ra_clo2;
		$sidang["CRa"] = $Ra_clo3;
		$sidang["ARb"] = $Rb_clo1;
		$sidang["BRb"] = $Rb_clo2;
		$sidang["CRb"] = $Rb_clo3;
		$sidang["Anhp"] = $nhp_clo1;
		$sidang["Bnhp"] = $nhp_clo2;
		$sidang["Cnhp"] = $nhp_clo3;
		$sidang["indexAkhir"] = $indexAkhir;
		$pdf = PDF::loadView('beritaAcara', $sidang);
		$fileNameSK = $mhs->username.'_berita_acara.pdf';  
		Storage::disk('local')->put('berita_acara/'.$mhs->username.'/'.$fileNameSK,$pdf->download()->getOriginalContent());

        return response()->json($sidang, 201);
	}
	
	public function uploadFormRevisi(Request $request){
		try {        
			$request->validate([
				'file' => 'required|mimes:pdf|max:2048',
			]);
			$fileName = date('Ymd').'_form_revisi.'.$request->file->extension();  
			Storage::disk('local')->put('form_revisi/'.Auth::id().'/'.$fileName, file_get_contents($request->file));
			$response = [
				'success' => true,
				'message' => 'Berhasil upload form revisi'
			];
        } catch (\Exception $e) {
			$response = [
				'error' => true,
				'message' => $e->getMessage()
			];
        }

        return response()->json($response, 201);
	}
	
	public function uploadDraftJurnal(Request $request, $id){
		try {   
			$bimbingan = (new ApiControllerBimbingan)->show($id);
			if($bimbingan->original['mahasiswa']->bimbingan_sum >= 14){
				$sidang = sidang::where('mhs_nim',$id)->first();
				if($sidang == null){
					$sidang = new sidang();
					$sidang->mhs_nim = $id;
					$sidang->sidang_status = 'mengajukan';
				}else{
					$sidang->sidang_status = 'mengajukan';
				}
				if(!$sidang->save()){
					$response = [
						'error' => true,
						'message' => 'Terjadi suatu error'
					];
				}else{
					$request->validate([
						'file' => 'required|mimes:pdf|max:2048',
					]);
					$fileName = date('Ymd').'_draft_jurnal_'.$id.'.'.$request->file->extension();  
					Storage::disk('local')->put('jurnal/'.$id.'/'.$fileName, file_get_contents($request->file));
					$response = [
						'success' => true,
						'message' => 'Berhasil upload form revisi'
					];
				}
			}else{
				$response = [
						'error' => true,
						'message' => 'Anda harus melakukan bimbingan minimal 14 pertemuan'
					];
			}
        } catch (\Exception $e) {
			$response = [
				'error' => true,
				'message' => $e->getMessage()
			];
        }

        return response()->json($response, 201);
	}
	
	public function checkFormRevisi($id){
		if(Storage::exists('form_revisi/'.$id)){
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
	
	public function updateStatusSidang(Request $request, $id){
		$sidang = sidang::where('mhs_nim',$id)->first();
		$sidang->sidang_status = $request->status;
		if(!$sidang->save()){
			$response = [
				'error' => true,
				'message' =>  "Terjadi kesalahan",
			];
		}else{
			$response = [
				'success' => true,
				'message' =>  "Berhasil menyimpan data",
			];
		}
        return response()->json($response, 201);
	}
	
	public function downloadFormRevisi($id){
		try{
			$files = Storage::files("form_revisi/".$id);
            return response()->download(storage_path("app/".$files[0]));
        }catch(Exception $e){
            return response()->json([
                'error' => true,
                'message' => 'Form not found'
            ]);
        }
	}
	
	public function saveReviewSidang(Request $request,$id){
		$sidang = sidang::where('mhs_nim',$id)->first();
		$sidang->sidang_review = $request->review;
		$sidang->sidang_status = $request->status;
		if(!$sidang->save()){
			$code = 404;
			$response = [
				'error' => true,
				'message' => "Terjadi error",
			];
		}else{
			$code = 201;
			$response = [
				'success' => true,
				'message' => "Berhasil menyimpan data review",
			];
		}
        return response()->json($response, $code);
	}
	
	public function saveNilaiSidang(Request $request, $id){
		$mhs = (new ApiControllerDosen)->getMahasiswaSidangByNIM($id)->original;
		if($mhs->nip_pembimbing_1 == Auth::id()){
			$role = "pembimbing_1";
		}else if($mhs->nip_pembimbing_2 == Auth::id()){
			$role = "pembimbing_2";
		}else if($mhs->nip_penguji_1 == Auth::id()){
			$role = "penguji_1";
		}else{
			$role = "penguji_2";
		}
		$sidang = sidang::where('mhs_nim',$id)->first();
		if($role == "pembimbing_1"){
			if($sidang->nilai_pembimbing_1 == null){
				$nilai = new nilai();
				$nilai->clo_1 = $request->clo1;
				$nilai->clo_2 = $request->clo2;
				$nilai->clo_3 = $request->clo3;
				$nilai->save();
			}else{
				$nilai = nilai::find($sidang->nilai_pembimbing_1);
				$nilai->clo_1 = $request->clo1;
				$nilai->clo_2 = $request->clo2;
				$nilai->clo_3 = $request->clo3;
				$nilai->save();
			}
			$sidang->nilai_pembimbing_1 = $nilai->nilai_id;
		}else if($role == "pembimbing_2"){
			if($sidang->nilai_pembimbing_2 == null){
				$nilai = new nilai();
				$nilai->clo_1 = $request->clo1;
				$nilai->clo_2 = $request->clo2;
				$nilai->clo_3 = $request->clo3;
				$nilai->save();
			}else{
				$nilai = nilai::find($sidang->nilai_pembimbing_2);
				$nilai->clo_1 = $request->clo1;
				$nilai->clo_2 = $request->clo2;
				$nilai->clo_3 = $request->clo3;
				$nilai->save();
			}
			$sidang->nilai_pembimbing_2 = $nilai->nilai_id;
		}else if($role == "penguji_1"){
			if($sidang->nilai_penguji_1 == null){
				$nilai = new nilai();
				$nilai->clo_1 = $request->clo1;
				$nilai->clo_2 = $request->clo2;
				$nilai->clo_3 = $request->clo3;
				$nilai->save();
			}else{
				$nilai = nilai::find($sidang->nilai_penguji_1);
				$nilai->clo_1 = $request->clo1;
				$nilai->clo_2 = $request->clo2;
				$nilai->clo_3 = $request->clo3;
				$nilai->save();
			}
			$sidang->nilai_penguji_1 = $nilai->nilai_id;
		}else{
			if($sidang->nilai_penguji_2 == null){
				$nilai = new nilai();
				$nilai->clo_1 = $request->clo1;
				$nilai->clo_2 = $request->clo2;
				$nilai->clo_3 = $request->clo3;
				$nilai->save();
			}else{
				$nilai = nilai::find($sidang->nilai_penguji_2);
				$nilai->clo_1 = $request->clo1;
				$nilai->clo_2 = $request->clo2;
				$nilai->clo_3 = $request->clo3;
				$nilai->save();
			}
			$sidang->nilai_penguji_2 = $nilai->nilai_id;
		}
		if($sidang->nilai_pembimbing_1 != null && $sidang->nilai_pembimbing_2 != null
		&& $sidang->nilai_penguji_1 != null && $sidang->nilai_penguji_2 != null){
			$nilai_pembimbing_1 = nilai::find($sidang->nilai_pembimbing_1);
			$nilai_pembimbing_2 = nilai::find($sidang->nilai_pembimbing_2);
			$nilai_penguji_1 = nilai::find($sidang->nilai_penguji_1);
			$nilai_penguji_2 = nilai::find($sidang->nilai_penguji_2);
			$indexNilai = array('A'=>4, 'AB'=>3.5, 'B'=>3, 'BC'=>2.5, 'C'=>2, 'D'=>1, 'E'=>0);
			$int_p_1_clo1 = $indexNilai[$nilai_pembimbing_1->clo_1];
			$int_p_1_clo2 = $indexNilai[$nilai_pembimbing_1->clo_2];
			$int_p_1_clo3 = $indexNilai[$nilai_pembimbing_1->clo_3];
			$int_p_2_clo1 = $indexNilai[$nilai_pembimbing_2->clo_1];
			$int_p_2_clo2 = $indexNilai[$nilai_pembimbing_2->clo_2];
			$int_p_2_clo3 = $indexNilai[$nilai_pembimbing_2->clo_3];
			$int_r_1_clo1 = $indexNilai[$nilai_penguji_1->clo_1];
			$int_r_1_clo2 = $indexNilai[$nilai_penguji_1->clo_2];
			$int_r_1_clo3 = $indexNilai[$nilai_penguji_1->clo_3];
			$int_r_2_clo1 = $indexNilai[$nilai_penguji_2->clo_1];
			$int_r_2_clo2 = $indexNilai[$nilai_penguji_2->clo_2];
			$int_r_2_clo3 = $indexNilai[$nilai_penguji_2->clo_3];
			$Ra_clo1 = ($int_p_1_clo1+$int_p_2_clo1)/2;
			$Ra_clo2 = ($int_p_1_clo2+$int_p_2_clo2)/2;
			$Ra_clo3 = ($int_p_1_clo3+$int_p_2_clo3)/2;
			$Rb_clo1 = ($int_r_1_clo1+$int_r_2_clo1)/2;
			$Rb_clo2 = ($int_r_1_clo2+$int_r_2_clo2)/2;
			$Rb_clo3 = ($int_r_1_clo3+$int_r_2_clo3)/2;
			$nhp_clo1 = (60/100*$Ra_clo1)+(40/100*$Rb_clo1);
			$nhp_clo2 = (60/100*$Ra_clo2)+(40/100*$Rb_clo2);
			$nhp_clo3 = (60/100*$Ra_clo3)+(40/100*$Rb_clo3);
			$indexAkhir = ($nhp_clo1*35/100)+($nhp_clo2*30/100)+($nhp_clo3*35/100);
			if($indexAkhir > 3.5){
				$sidang->nilai_total = 'A';
			}else if($indexAkhir > 3.25){
				$sidang->nilai_total = 'AB';
			}else if($indexAkhir > 2.75){
				$sidang->nilai_total = 'B';
			}else if($indexAkhir > 2.25){
				$sidang->nilai_total = 'BC';
			}else if($indexAkhir > 1.75){
				$sidang->nilai_total = 'C';
			}else if($indexAkhir > 1){
				$sidang->nilai_total = 'D';
			}else if($indexAkhir <= 1){
				$sidang->nilai_total = 'E';
			}
		}
		if(!$sidang->save()){
			$code = 404;
			$response = [
				'error' => true,
				'message' => "Terjadi error",
			];
		}else{
			$code = 201;
			$response = [
				'success' => true,
				'message' => "Berhasil menyimpan data nilai",
			];
		}
        return response()->json($response, $code);
	}

}
