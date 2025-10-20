<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    C_Home, C_Dosen, C_Mahasiswa, C_Login, C_admin, C_owner,
    UserController, AuthController, CalonMitraController,
    KasirController, C_Status, C_Survey, UlasanController,
    KontakController, TransaksiController, GudangController, C_Print , TransaksiAdminController, StrukdcController   
};

// ========================
// ROUTE UMUM (TANPA LOGIN)
// ========================
Route::view('/contact', 'v_contact');
Route::view('/kontak', 'v_kontak');
Route::view('/kontaks', 'v_kontaklog');
Route::view('/kemitraan', 'v_kemitraan');
Route::get('/kemitraann', [UlasanController::class, 'kkemitraanlog']);
Route::view('/cabang', 'v_cabang');
Route::view('/cabangg', 'v_cabanglog');
Route::view('/profile', 'v_profile');
Route::view('/profilee', 'v_profilelog');
Route::view('/status', 'v_preview');
Route::view('/cek-status', 'v_status');
// Forgot Password
Route::get('/forgotpassword', [App\Http\Controllers\C_LupaPassword::class, 'showForgot'])->name('password.request');
Route::post('/forgotpassword', [App\Http\Controllers\C_LupaPassword::class, 'sendOtp'])->name('password.send');

// Reset Password
Route::get('/resetpassword', [App\Http\Controllers\C_LupaPassword::class, 'showReset'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\C_LupaPassword::class, 'resetPassword'])->name('password.update');



// Route::view('/loginkasir', 'v_loginkasir');

// ========================
// ROUTE HALAMAN LOGIN/REGISTER
// ========================
Route::get('/login', fn() => view('v_login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
Route::get('/logout', [AuthController::class, 'logout'])->name('costum.logout');

Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register.proses');

Auth::routes([
    'reset' => true,
    'verify' => false,
    'login' => false,
    'register' => false
]);

// ========================
// ROUTE ULASAN DAN HOMEPAGE
// ========================
Route::get('/', [UlasanController::class, 'indexuser']);
Route::get('/profiles', [C_admin::class, 'indexprofile']);
Route::get('/franchisee', [C_admin::class, 'indexfranchise']);
Route::get('/home', [UlasanController::class, 'home']);
Route::post('/kirim-ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
Route::post('/kontak/kirim-ulasan', [KontakController::class, 'storeUlasan'])->name('kontak.storeUlasan');
Route::get('/loginkasir/{id_franchise}',[AuthController::class, 'loginkasir']);
Route::get('/print/{id_penjualan}', [KasirController::class, 'print'])->name('printkasir');

// ========================
// CEK STATUS & MITRA
// ========================
Route::get('/status', [CalonMitraController::class, 'status']);
Route::get('/cekstatus', [CalonMitraController::class, 'viewStatus']);
Route::get('/cekstatus/{id}', [CalonMitraController::class, 'viewStatus'])->name('cek.status.view');
Route::post('/cekstatus', [C_Status::class, 'cek'])->name('cek.status');
Route::get('/daftarmitra', function () {
    return view('daftarmitra');
});
Route::get('/tambahfranchise', [CalonMitraController::class, 'indextambahfranchise']);
Route::post('/tambahfranchise/insert', [CalonMitraController::class, 'tambahfranchise'])->name('franchise.baru');
Route::get('/daftarmitra', [CalonMitraController::class, 'indexdaftar']);

Route::middleware(['auth'])->group(function () {
    Route::post('/daftarmitra', [CalonMitraController::class, 'store'])->name('calonmitra.store');
});

Route::get('/qrcode', [CalonMitraController::class, 'qrcode'])->name('qr.code');
Route::get('/qrcode/{id_franchisebaru}', [CalonMitraController::class, 'qrcodefranchise'])->name('qrcode.franchise');
Route::post('/uploadbukti/{id_calon}', [CalonMitraController::class, 'uploadtransaksi'])->name('upload.transaksi');
Route::post('/uploadbuktifranchise/{id_franchisebaru}', [CalonMitraController::class, 'uploadtransaksifranchise'])->name('upload.transaksifranchise');

// =================================================
// KASIR GROUP (dengan middleware kasir)
// =================================================

    Route::get('/kasir', [KasirController::class, 'kasir']);
        Route::get('/dashkasir', [KasirController::class, 'index'])->name('kasir.v_dashkasir');
        Route::get('/stokbahan', [KasirController::class, 'stokFranchise'])->name('stok.franchise');
        Route::post('/tambahstok', [KasirController::class, 'tambahstok'])->name('insert.stok');
        Route::get('/editstok/{id}', [KasirController::class, 'editstok'])->name('edit.stok');
        Route::put('/updatestok/{id}', [KasirController::class, 'updateStok'])->name('stokbahan.update');
        Route::delete('/deletestok/{id}', [KasirController::class, 'destroyStok'])->name('stokbahan.destroy');
        Route::post('/kasir/store', [KasirController::class, 'store'])->name('kasir.store');
        Route::post('/kasir/checkout', [KasirController::class, 'checkout']);
        Route::get('/pelaporan', [KasirController::class, 'laporan']);
        Route::get('/print/{id_penjualan}', [KasirController::class, 'print'])->name('printkasir');
        Route::get('/loginkasir/{id_franchise}', [AuthController::class, 'loginkasir']);
        // Route::get('/kasir/akun', [KasirController::class, 'showakun'])->name('kasirakun');
        // Route::get('/kasir/akun/edit/{id_akun}', [KasirController::class, 'editakun'])->name('editakun');
        // Route::post('/kasir/akun/update/{id_akun}', [KasirController::class, 'updateakun'])->name('updateakun');
        Route::get('/printescpos/{id_penjualan}', [C_Print::class, 'printStruk']);
        Route::get('/pelaporan/delete/{id_penjualan}', [KasirController::class, 'destroy'])->name('kasir.destroy');

        // Pengaturan akun kasir
        Route::get('/kasir/akun', [KasirController::class, 'showakun'])->name('kasirakun');
        Route::get('/kasir/akun/edit/{id_akun}', [KasirController::class, 'editakun'])->name('editakun');
        Route::post('/kasir/akun/update/{id_akun}', [KasirController::class, 'updateakunkasir'])->name('updateakunkasir');

// =================================================
// OWNER GROUP (dengan middleware owner)
// =================================================
Route::middleware(['auth', 'owner.only'])->prefix('owner')->group(function () {
    Route::get('/', [C_owner::class, 'index1'])->name('owner.v_dashboard');
    Route::get('/tabelcalon', [C_owner::class, 'index'])->name('ownercalon');
    Route::get('/tabelakun', [C_owner::class, 'index2'])->name('ownerakun');
    Route::get('/tabelproduk', [C_owner::class, 'index3'])->name('ownerproduk');
    Route::get('/tabelfranchisebaru', [C_owner::class, 'index4'])->name('ownerfranchisebaru');
    Route::get('/tabelfranchise', [C_owner::class, 'tabelfranchise'])->name('ownerfranchise');
    Route::delete('/calonmitra/{id_calon}', [C_owner::class, 'deletecalon'])->name('C_owner.delete');
    Route::delete('/akun/{id_akun}', [C_owner::class, 'deleteakun'])->name('akunowner.delete');
    Route::put('/update-tipe/{id_akun}', [C_owner::class, 'updatetipe'])->name('owner.updatetipe');
    Route::delete('/produk/{id_akun}', [C_owner::class, 'deleteproduk'])->name('produkowner.delete');
    Route::get('/produk/edit/{id_produk}', [C_owner::class, 'editproduk']);
    Route::post('/produk/update/{id_produk}', [C_owner::class, 'updateproduk']);
    Route::get('/produk/add', [C_owner::class, 'tambahproduk']);
    Route::post('/produk/insert', [C_owner::class, 'insertproduk'])->name('produk.insert');
    Route::get('/tabelfranchisebaru', [C_owner::class, 'index4'])->name('ownerfranchisebaru');
    Route::get('/datasurvey', [C_Survey::class, 'indexowner'])->name('datasurvey');
});


// ========================
// ROUTE ADMIN - TERPROTEKSI
// ========================
Route::middleware(['auth', 'admin.only'])->prefix('admin')->group(function () {
    Route::get('/', [C_admin::class, 'index'])->name('admin.v_dashboard');
    Route::get('/tabelcalon', [C_admin::class, 'index1'])->name('admincalon');
    Route::get('/tabelakun', [C_admin::class, 'index2'])->name('adminakun');
    Route::get('/tabelproduk', [C_admin::class, 'index3'])->name('adminproduk');
    Route::get('/dataqr', [C_admin::class, 'indexqr'])->name('auditorqr');
    Route::post('/dataqr/tambah', [C_admin::class, 'qrcode'])->name('qr.store');
    Route::get('/qr/{data}', [C_admin::class, 'redirectToUrl'])->name('qr.redirect');
    Route::put('/qr/{id_qr}', [C_admin::class, 'updateqr'])->name('qr.update');
    Route::get('/dataqr/download/{id_qr}', [C_admin::class, 'download'])->name('admin.dataqr.download');
    Route::delete('/qr/delete/{id_qr}', [C_admin::class, 'deleteqr'])->name('qr.delete');
    Route::get('/tabelfranchisebaru', [C_admin::class, 'index4'])->name('adminfranchisebaru');
    Route::get('/tabelfranchise', [C_admin::class, 'tabelfranchise'])->name('adminfranchise');
    Route::get('/franchise/edit/{id_franchise}', [C_admin::class, 'editfranchise'])->name('editfranchise');
    Route::post('/franchise/update/{id_franchise}', [C_admin::class, 'updatefranchise']);
    Route::put('/franchise/update-akses/{id_franchise}', [C_admin::class, 'updateAkses'])->name('franchise.updateStatus');
    Route::delete('/franchise/{id_franchise}', [C_admin::class, 'deletefranchise'])->name('franchiseadmin.delete');
    Route::get('/tabelulasan', [UlasanController::class, 'index'])->name('admin.ulasan.index');
    Route::get('/datasurvey', [C_Survey::class, 'indexadmin'])->name('datasurvey');
    Route::delete('/admin/franchisebaru/{id_franchisebaru}', [C_admin::class, 'deletefranchisebaru'])->name('franchisebaruadmin.delete');

    Route::get('/profiles', [C_admin::class, 'indexprofileadmin']);
    Route::get('/admin/notifikasi', [C_admin::class, 'getNotifikasi'])->name('admin.notifikasi');


    // CRUD produk
    Route::get('/produk/edit/{id_produk}', [C_admin::class, 'editproduk']);
    Route::post('/produk/update/{id_produk}', [C_admin::class, 'updateproduk']);
    Route::get('/produk/add', [C_admin::class, 'tambahproduk']);
    Route::post('/produk/insert', [C_admin::class, 'insertproduk'])->name('produkadmin.insert');
    Route::delete('/produk/{id_akun}', [C_admin::class, 'deleteproduk'])->name('produkadmin.delete');

    // Calon Mitra
    Route::put('/calonmitra/update-status/{id_calonmitra}', [CalonMitraController::class, 'updateStatus'])->name('calonmitra.updateStatus');
    Route::delete('/calonmitra/{id_calon}', [   C_admin::class, 'deletecalon'])->name('C_admin.delete');
    Route::put('/admin/survey/acc/{id_calon}/{sumber}', [C_Survey::class, 'accCalon'])->name('survey.acc');

    // Akun
    Route::delete('/akun/{id_akun}', [C_admin::class, 'deleteakun'])->name('deleteadmin');

    // Franchise Baru
    Route::put('/franchisebaru/update-status/{id_franchisebaru}', [C_admin::class, 'updateStatus'])->name('franchisebaru.updateStatus');
    Route::delete('/franchisebaru/{id_franchisebaru}', [C_admin::class, 'deletefranchisebaru'])->name('deletefranchisebaru');

    // Ulasan
    Route::delete('/tabelulasan/{id}', [UlasanController::class, 'destroy']);
    Route::put('/ulasan/show/{id}', [UlasanController::class, 'tampilkan']);
    Route::put('/ulasan/hide/{id}', [UlasanController::class, 'sembunyikan']);
    Route::patch('/tabelulasan/tampilkan/{id}', [UlasanController::class, 'tampilkan']);

    // Transaksi
    Route::get('transaksi', [TransaksiAdminController::class, 'index'])->name('admin.transaksi.index');
    Route::get('transaksi/create', [TransaksiAdminController::class, 'create'])->name('transaksi.create');
    Route::post('transaksi/store', [TransaksiAdminController::class, 'store'])->name('transaksi.store');
    Route::get('transaksi/{id}/edit', [TransaksiAdminController::class, 'edit'])->name('transaksi.edit');
    Route::put('transaksi/{id}', [TransaksiAdminController::class, 'update'])->name('transaksi.update');
    Route::delete('transaksi/{id}', [TransaksiAdminController::class, 'destroy'])->name('transaksi.destroy');
    Route::get('transaksi/export/excel', [TransaksiAdminController::class, 'exportExcel'])->name('transaksi.export.excel');
    Route::get('transaksi/export/pdf', [TransaksiAdminController::class, 'exportPdf'])->name('transaksi.export.pdf');

});

// ========================
// PROFILE USER (dengan OTP)
// ========================
Route::middleware('auth')->group(function () {
    Route::get('/editakun', [UserController::class, 'showEditForm'])->name('user.edit');
    Route::put('/update-akun', [UserController::class, 'update'])->name('user.update');
    Route::get('/profile/edit', [UserController::class, 'showEditForm'])->name('user.edit-profile');
    Route::put('/profile/update', [UserController::class, 'update'])->name('user.update');
    Route::get('/profile/verify-otp', [UserController::class, 'showOtpForm'])->name('user.show-otp-form');
    Route::post('/profile/verify-otp', [UserController::class, 'verifyOtpAndUpdate'])->name('user.verify-otp');
    Route::post('/profile/resend-otp', [UserController::class, 'resendOtp'])->name('user.resend-otp');
});

// ========================
// SURVEY
// ========================
Route::get('/survey/datasurvey', [C_Survey::class, 'index'])->name('datasurvey');
Route::get('/survey/tabelcalon', [C_Survey::class, 'index2'])->name('survey.calon');
Route::get('/survey/laporansurvey/{id_calon}', [C_Survey::class, 'index3'])->name('survey.laporan');
Route::post('/survey/laporansurvey/insert/{id_calon}', [C_Survey::class, 'laporansurvey'])->name('membuat.laporan');
Route::delete('/survey/datasurvey/delete/{id_survey}', [C_Survey::class, 'destroy'])->name('survey.destroy');
Route::get('/survey/profile', [C_Survey::class, 'indexprofile'])->name('profile');
Route::post('/survey/profile/update', [C_Survey::class, 'updateprofile'])->name('profile.update');
Route::get('/survey/{id}/edit', [C_Survey::class, 'edit'])->name('survey.edit');
Route::put('/survey/{id}', [C_Survey::class, 'update'])->name('survey.update');




// ========================
// TEST EMAIL
// ========================
Route::get('/test-email', function () {
    try {
        Mail::raw('Ini adalah email test dari Laravel', function ($message) {
            $message->to('your@email.com')->subject('Test Kirim Email');
        });
        return 'Berhasil kirim email.';
    } catch (\Exception $e) {
        return 'Gagal: ' . $e->getMessage();
    }
});

Route::middleware(['auth', 'gudang.only'])->prefix('gudang')->group(function () {
    Route::get('/', [GudangController::class, 'index'])->name('gudang.index');
    Route::get('/masuk', function() { return view('gudang.masuk'); });
    Route::post('/tambahmasuk', [GudangController::class, 'simpanBarangMasuk'])->name('barang.masuk');
    Route::post('/tambahkeluar', [GudangController::class, 'simpanBarangKeluar'])->name('barang.keluar');
    Route::get('/keluar', function() { return view('gudang.keluar'); });
    Route::get('/stok', [GudangController::class, 'laporanstok'])->name('laporan.stok');
    Route::get('/riwayat', [GudangController::class, 'riwayatgudang'])->name('riwayat.gudang');
    Route::get('/riwayat/editkeluar/{id}', [GudangController::class, 'Editkeluar']);
    Route::get('/riwayat/editmasuk/{id}', [GudangController::class, 'Editmasuk']);
    Route::delete('/riwayat/hapus-bahankeluar/{id}', [GudangController::class, 'hapuskeluar']);
    Route::delete('/riwayat/hapus-bahanmasuk/{id}', [GudangController::class, 'hapusmasuk']);
    Route::post('/riwayat/updatekeluar', [GudangController::class, 'updatekeluar'])->name('riwayatkeluar.update');
    Route::post('/riwayat/updatemasuk', [GudangController::class, 'updatemasuk'])->name('riwayatmasuk.update');
    Route::post('/riwayat/tambah-bahankeluar', [GudangController::class, 'tambahBahanKeluar']);
    Route::post('/riwayat/tambah-bahanmasuk', [GudangController::class, 'tambahBahanMasuk']);
    Route::delete('/riwayat/hapus/{id}', [GudangController::class, 'hapusTransaksi'])->name('riwayat.hapus');
    Route::delete('/riwayatmasuk/hapus/{id}', [GudangController::class, 'hapusTransaksimasuk'])->name('riwayatmasuk.hapus');
    Route::get('/datasupplier', [GudangController::class, 'dataSupplier'])->name('gudang.datasupplier');
    Route::get('/bahan/edit/{id_bahan}/{id_supplier}', [GudangController::class, 'editbahan'])->name('edit.bahan');
    Route::post('/bahan/update/{id_bahan}/{id_supplier}', [GudangController::class, 'updatebahan'])->name('update.bahan');
    Route::get('/editakun/{id}', [GudangController::class, 'editakun'])->name('editakungudang');
    Route::post('/updateakun/{id}', [GudangController::class, 'updateakun'])->name('updateakun');
    Route::post('/tambah', [GudangController::class, 'storeSupplier'])->name('supplier.store');
    Route::post('/bahanbaku/store', [GudangController::class, 'storeBahan'])->name('bahanbaku.store');
    Route::delete('/supplier/{id}', [GudangController::class, 'deleteSupplier'])->name('supplier.delete');
    Route::delete('/bahanbaku/{id}', [GudangController::class, 'deleteBahan'])->name('bahanbaku.delete');
    Route::get('/gudang/print-nota/{id}', [GudangController::class, 'printNota'])->name('gudang.printnota');
    Route::get('/gudang/print-notamasuk/{id}', [GudangController::class, 'printNotamasuk'])->name('gudang.printnotamasuk');
    Route::post('/stok/tambah', [GudangController::class, 'tambahLaporanStok'])->name('laporan.stok.tambah');
    Route::put('/stok/update/{id}', [GudangController::class, 'updateStok'])->name('laporan.stok.update');Route::get('/tabelpengeluaran', [GudangController::class, 'tabelPengeluaran'])->name('gudang.tabelpengeluaran');
    Route::get('/tabelpenjualan', [GudangController::class, 'tabelPenjualan'])->name('gudang.tabelpenjualan');

       // Penjualan
        Route::get('/penjualan/export/pdf', [GudangController::class, 'exportPenjualanPDF'])->name('gudang.penjualan.export.pdf');
        Route::get('/penjualan/export/excel', [GudangController::class, 'exportPenjualanExcel'])->name('gudang.penjualan.export.excel');
    
        // Pengeluaran
        Route::get('/pengeluaran/export/pdf', [GudangController::class, 'exportPengeluaranPDF'])->name('gudang.pengeluaran.export.pdf');
        Route::get('/pengeluaran/export/excel', [GudangController::class, 'exportPengeluaranExcel'])->name('gudang.pengeluaran.export.excel');
    
        // Laporan Stok - Export
        Route::get('/gudang/export/stok/pdf', [GudangController::class, 'exportStokPDF'])->name('stok.export.pdf');
        Route::get('/gudang/export/stok/excel', [GudangController::class, 'exportStokExcel'])->name('stok.export.excel');
    
        // Export riwayat gudang
        // Pemasukan
        Route::get('/gudang/export/riwayatmasuk/pdf', [GudangController::class, 'exportRiwayatMasukPDF'])->name('riwayatmasuk.export.pdf');
        Route::get('/gudang/export/riwayatmasuk/excel', [GudangController::class, 'exportRiwayatMasukExcel'])->name('riwayatmasuk.export.excel');
    
        // Pengeluaran
        Route::get('/gudang/export/riwayatkeluar/pdf', [GudangController::class, 'exportRiwayatKeluarPDF'])->name('riwayatkeluar.export.pdf');
        Route::get('/gudang/export/riwayatkeluar/excel', [GudangController::class, 'exportRiwayatKeluarExcel'])->name('riwayatkeluar.export.excel');
        
        Route::get('/strukdc', [StrukdcController::class, 'index'])->name('gudang.strukdc');
        Route::get('/strukdc/download/{id}', [StrukdcController::class, 'download'])->name('gudang.strukdc.download');
        
        // Transaksi
        Route::get('transaksi', [TransaksiAdminController::class, 'index'])->name('gudang.transaksi.index');
        Route::get('transaksi/create', [TransaksiAdminController::class, 'create'])->name('transaksi.create');
        Route::post('transaksi/store', [TransaksiAdminController::class, 'store'])->name('transaksi.store');
        Route::get('transaksi/{id}/edit', [TransaksiAdminController::class, 'edit'])->name('transaksi.edit');
        Route::put('transaksi/{id}', [TransaksiAdminController::class, 'update'])->name('transaksi.update');
        Route::delete('transaksi/{id}', [TransaksiAdminController::class, 'destroy'])->name('transaksi.destroy');
        Route::get('transaksi/export/excel', [TransaksiAdminController::class, 'exportExcel'])->name('transaksi.export.excel');
        Route::get('transaksi/export/pdf', [TransaksiAdminController::class, 'exportPdf'])->name('transaksi.export.pdf');
});






