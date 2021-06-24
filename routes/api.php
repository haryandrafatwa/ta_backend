<?php

use App\Http\Controllers\ApiController\ApiControllerAuth;
use App\Http\Controllers\ApiController\ApiControllerBimbingan;
use App\Http\Controllers\ApiController\ApiControllerDosen;
use App\Http\Controllers\ApiController\ApiControllerInformasi;
use App\Http\Controllers\ApiController\ApiControllerJudul;
use App\Http\Controllers\ApiController\ApiControllerKoor;
use App\Http\Controllers\ApiController\ApiControllerKategori;
use App\Http\Controllers\ApiController\ApiControllerKegiatan;
use App\Http\Controllers\ApiController\ApiControllerMahasiswa;
use App\Http\Controllers\ApiController\ApiControllerMonev;
use App\Http\Controllers\ApiController\ApiControllerMonevDetail;
use App\Http\Controllers\ApiController\ApiControllerNotifikasi;
use App\Http\Controllers\ApiController\ApiControllerProyekAkhir;
use App\Http\Controllers\ApiController\ApiControllerProfile;
use App\Http\Controllers\ApiController\ApiControllerSidang;
use App\Http\Controllers\ApiController\ApiControllerUser;
use App\Http\Controllers\ApiController\ApiControllerPlotting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'v1'],function () {
    Route::post('/user/signin', [ApiControllerAuth::class, 'signin']);
	Route::resource('user', ApiControllerUser::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
});

Route::group(['prefix'=>'v1','middleware'=>['auth:api']],function () {
    Route::post('/user/logout', [ApiControllerAuth::class, 'logout']);
    Route::get('/user/', [ApiControllerUser::class, 'getCurrent']);
	// -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('plotting', ApiControllerPlotting::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
	Route::post('/plotting/upload', [ApiControllerPlotting::class, 'uploadFormExcel']);
    Route::post('/plotting/delete/{plotting}', [ApiControllerPlotting::class, 'destroy']);
    Route::post('/plotting/update/{plotting}', [ApiControllerPlotting::class, 'update']);
    Route::get('/plotting/pembimbing/{plotting}', [ApiControllerPlotting::class, 'getPembimbing']);
    Route::get('/plotting/penguji/{plotting}', [ApiControllerPlotting::class, 'getPenguji']);
	Route::get('/checkFormPlot', [ApiControllerPlotting::class, 'check']);
	Route::get('/downloadFormPlot', [ApiControllerPlotting::class, 'download']);
	Route::get('/deleteFormPlot', [ApiControllerPlotting::class, 'delete']);
	// -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('informasi', ApiControllerInformasi::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::post('/informasi/update/{informasi}', [ApiControllerInformasi::class, 'update']);
    Route::post('/informasi/delete/{informasi}', [ApiControllerInformasi::class, 'destroy']);
    // -----------------------------------------------------------------------------------------------------------------------------
    // -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('mahasiswa', ApiControllerMahasiswa::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::post('/mahasiswa/update/{mahasiswa}', [ApiControllerMahasiswa::class, 'update']);
    Route::post('/mahasiswa/update/skta/{mahasiswa}', [ApiControllerMahasiswa::class, 'updateSKMahasiswa']);
    Route::post('/mahasiswa/update/pembimbing/{mahasiswa}', [ApiControllerMahasiswa::class, 'addPembimbing']);
    Route::post('/mahasiswa/delete/{mahasiswa}', [ApiControllerMahasiswa::class, 'destroy']);
    Route::post('/mahasiswa/upload/pengajuan/perpanjangSK/{mahasiswa}', [ApiControllerMahasiswa::class, 'perpanjangSKTA']);
    Route::post('/mahasiswa/delete/pembimbing/{mahasiswa}', [ApiControllerMahasiswa::class, 'deletePembimbing']);
    Route::get('/askSidang', [ApiControllerMahasiswa::class, 'askSidangTerjadwal']);
    Route::post('/konfirmasiSidang', [ApiControllerMahasiswa::class, 'konfirmasiSidang']);
    Route::get('/mahasiswa/download/skta/{mahasiswa}', [ApiControllerMahasiswa::class, 'downloadSKTA']);
    Route::post('/uploadFormSidang', [ApiControllerMahasiswa::class, 'uploadFormSidang']);
    // -----------------------------------------------------------------------------------------------------------------------------
    // Route::resource('dosen', ApiControllerDosen::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::get('/dosen/all/', [ApiControllerDosen::class, 'index']);
    Route::post('/dosen', [ApiControllerDosen::class, 'store']);
    Route::get('/dosen', [ApiControllerDosen::class, 'getCurrent']);
    Route::get('/dosen/{dosen}', [ApiControllerDosen::class, 'show']);
    Route::get('/dosen/mahasiswa/sidang', [ApiControllerDosen::class, 'getMahasiswaSidang']);
    Route::get('/dosen/mahasiswa/sidang/{dosen}', [ApiControllerDosen::class, 'getMahasiswaSidangByNIM']);
    Route::post('/dosen/update/{dosen}', [ApiControllerDosen::class, 'update']);
    Route::post('/dosen/update/pure/{dosen}', [ApiControllerDosen::class, 'updatePure']);
    Route::post('/dosen/delete/{dosen}', [ApiControllerDosen::class, 'destroy']);
    // -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('koor', ApiControllerKoor::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::post('/koor/update/{koor}', [ApiControllerKoor::class, 'update']);
    Route::post('/koor/delete/{koor}', [ApiControllerKoor::class, 'destroy']);
    // -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('judul', ApiControllerJudul::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::post('/judul/update/{judul}', [ApiControllerJudul::class, 'update']);
    Route::post('/judul/delete/{judul}', [ApiControllerJudul::class, 'destroy']);
    Route::post('/judul/update/status/{judul}', [ApiControllerJudul::class, 'updateStatusJudul']);
    Route::get('/judul/search/{parameter}/{query}', [ApiControllerJudul::class, 'searchJudulBy']);
    Route::get('/judul/search/{parameter1}/{query1}/{parameter2}/{query2}', [ApiControllerJudul::class, 'searchJudulBy2']);
    Route::get('/judul/search/sum_team/{parameter1}/{query1}/{parameter2}/{query2}', [ApiControllerJudul::class, 'searchJudulTeamByTwo']);
    Route::get('/judul/search/mahasiswa/{parameter}/{query}', [ApiControllerJudul::class, 'searchJudulShowMhsBy']);
    // -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('bimbingan', ApiControllerBimbingan::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::post('/bimbingan/update/{bimbingan}', [ApiControllerBimbingan::class, 'update']);
    Route::post('/bimbingan/update/status/{bimbingan}', [ApiControllerBimbingan::class, 'updateStatusBimbingan']);
    Route::post('/bimbingan/delete/{bimbingan}', [ApiControllerBimbingan::class, 'destroy']);
    Route::get('/bimbingan/search/siap_sidang/{bimbingan}', [ApiControllerBimbingan::class, 'getSiapSidang']);
    Route::get('/bimbingan/search/all/{parameter}/{query}', [ApiControllerBimbingan::class, 'getBimbinganSearchAllBy']);
    Route::get('/bimbingan/search/all/{parameter1}/{query1}/{parameter2}/{query2}', [ApiControllerBimbingan::class, 'getBimbinganSearchAllByTwo']);
    // -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('monev', ApiControllerMonev::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::post('/monev/update/{monev}', [ApiControllerMonev::class, 'update']);
    Route::post('/monev/delete/{monev}', [ApiControllerMonev::class, 'destroy']);
    // -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('monev_detail', ApiControllerMonevDetail::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::post('/monev_detail/update/{monev_detail}', [ApiControllerMonevDetail::class, 'update']);
    Route::post('/monev_detail/delete/{monev_detail}', [ApiControllerMonevDetail::class, 'destroy']);
    Route::get('/monev_detail/search/{parameter}/{query}/', [ApiControllerMonevDetail::class, 'searchDetailMonevBy']);
    Route::get('/monev_detail/search/{parameter1}/{query1}/{parameter2}/{query2}', [ApiControllerMonevDetail::class, 'searchDetailMonevBy2']);
    // -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('proyek_akhir', ApiControllerProyekAkhir::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::post('/proyek_akhir/update/{proyek_akhir}', [ApiControllerProyekAkhir::class, 'update']);
    Route::post('/proyek_akhir/update/nilai/{proyek_akhir}', [ApiControllerProyekAkhir::class, 'updateNilaiTotal']);
    Route::post('/proyek_akhir/update/dosen/{proyek_akhir}', [ApiControllerProyekAkhir::class, 'updateDsnNip']);
    Route::post('/proyek_akhir/delete/{proyek_akhir}', [ApiControllerProyekAkhir::class, 'destroy']);
    Route::get('/proyek_akhir/search/all/{parameter}/{query}', [ApiControllerProyekAkhir::class, 'searchAllProyekAkhirBy']);
    Route::get('/proyek_akhir/search/all/{parameter1}/{query1}/{parameter2}/{query2}', [ApiControllerProyekAkhir::class, 'searchAllProyekAkhirBy2']);
    Route::get('/proyek_akhir/search/distinct', [ApiControllerProyekAkhir::class, 'getProyekAkhirDistinct']);
    Route::get('/proyek_akhir/search/distinct/{parameter}/{query}', [ApiControllerProyekAkhir::class, 'searchDistinctProyekAkhirBy']);
    Route::get('/proyek_akhir/search/distinct/{parameter1}/{query1}/{parameter2}/{query2}', [ApiControllerProyekAkhir::class, 'searchDistinctProyekAkhirBy2']);
    Route::get('/proyek_akhir/search/distinct/sum_team/{parameter1}/{query1}/{parameter2}/{query2}', [ApiControllerProyekAkhir::class, 'searchDistinctProyekAkhirTimBy2']);
    // -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('notifikasi', ApiControllerNotifikasi::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::post('/notifikasi/update/{notifikasi}', [ApiControllerProyekAkhir::class, 'update']);
    Route::post('/notifikasi/delete/{notifikasi}', [ApiControllerProyekAkhir::class, 'destroy']);
    Route::get('/notifikasi/search/all/{parameter}/{query}', [ApiControllerProyekAkhir::class, 'searchNotifikasiBy']);
    Route::get('/notifikasi/search/all/{parameter1}/{query1}/{parameter2}/{query2}', [ApiControllerProyekAkhir::class, 'searchNotifikasiBy2']);
    Route::get('/notifikasi/sort/{query1}/{query2}', [ApiControllerProyekAkhir::class, 'sortNotifikasi']);
    // -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('sidang', 'ApiController\ApiControllerSidang', ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::post('/sidang/update/{sidang}', [ApiControllerSidang::class, 'update']);
    Route::post('/sidang/nilai/{sidang}', [ApiControllerSidang::class, 'saveNilaiSidang']);
    Route::get('/sidang/nilai/{sidang}', [ApiControllerSidang::class, 'getNilaiSidang']);
    Route::post('/sidang/upload/revisi/{sidang}', [ApiControllerSidang::class, 'uploadFormRevisi']);
    Route::post('/sidang/update/status/{sidang}', [ApiControllerSidang::class, 'updateStatusSidang']);
    Route::post('/sidang/upload/jurnal/{sidang}', [ApiControllerSidang::class, 'uploadDraftJurnal']);
    Route::get('/sidang/check/revisi/{sidang}', [ApiControllerSidang::class, 'checkFormRevisi']);
    Route::get('/sidang/download/revisi/{sidang}', [ApiControllerSidang::class, 'downloadFormRevisi']);
    Route::get('/sidang/berita_acara/{sidang}', [ApiControllerSidang::class, 'getBeritaAcara']);
    Route::post('/sidang/review/{sidang}', [ApiControllerSidang::class, 'saveReviewSidang']);
    Route::post('/sidang/delete/{sidang}', [ApiControllerSidang::class, 'destroy']);
    Route::get('/sidang/search/all/{parameter}/{query}', [ApiControllerSidang::class, 'searchAllSidangBy']);
    Route::get('/sidang/search/all/{parameter1}/{query1}/{parameter2}/{query2}', [ApiControllerSidang::class, 'searchAllSidangByTwo']);
    // -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('kategori_judul', ApiControllerKategori::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::post('/kategori_judul/update/{kategori_judul}', [ApiControllerKategori::class, 'update']);
    Route::post('/kategori_judul/delete/{kategori_judul}', [ApiControllerKategori::class, 'destroy']);
    // -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('jadwal_kegiatan', ApiControllerKegiatan::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::get('/jadwal_kegiatan/{jadwal_kegiatan}',  [ApiControllerKegiatan::class, 'getByLike']);
    Route::post('/jadwal_kegiatan/update/{jadwal_kegiatan}',  [ApiControllerKegiatan::class, 'update']);
    Route::post('/jadwal_kegiatan/delete/{jadwal_kegiatan}', [ApiControllerKegiatan::class, 'destroy']);
    // -----------------------------------------------------------------------------------------------------------------------------
    Route::resource('kuota_dosen', 'ApiController\ApiControllerKuotaDosen', ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::post('/kuota_dosen/update/{kuota_dosen}', [ApiControllerKuotaDosen::class, 'update']);
    Route::post('/kuota_dosen/delete/{kuota_dosen}', [ApiControllerKuotaDosen::class, 'destroy']);
});
