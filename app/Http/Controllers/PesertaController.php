<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Daftarlayanan;
use App\Peserta;
use DB;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    public function index(){
        $data = new Peserta();
        $data = DB::table('pesertas')
                ->join('daftarlayanans', 'daftarlayanans.iddaftar', '=', 'pesertas.iddaftar')
                ->join('users', 'users.id', '=', 'daftarlayanans.iduser')
                ->join('layanans', 'layanans.idlayanan', '=', 'daftarlayanans.idlayanan')
                ->select('layanans.*','daftarlayanans.*','pesertas.*')
                ->where('daftarlayanans.iduser', Auth::id())
                ->get();
        return view('peserta')->with('data', $data);
    }


    public function tambahpeserta(){
        $peserta = new Daftarlayanan();
        $peserta = DB::table('daftarlayanans')
                    ->join('users', 'users.id', '=', 'daftarlayanans.iduser')
                    ->join('layanans', 'layanans.idlayanan', '=', 'daftarlayanans.idlayanan')
                    ->select('layanans.*','daftarlayanans.*')
                    ->where('daftarlayanans.iduser', Auth::id())
                    ->get();
                
        return view('tambahpeserta')->with('peserta', $peserta);
    }

    public function prosestambahpeserta(Request $request){

        $jmlhpesertadipesertas = DB::table('pesertas')
                                    ->where('iddaftar', 1)
                                    ->count();
        $jmlhpesertadilayanan = DB::table('daftarlayanans')
                                    ->where('iddaftar', 1)
                                    ->select('jumlahpeserta')
                                    ->get();
        $psrta;
        foreach($jmlhpesertadilayanan as $jmlh){
            $psrta  = $jmlh->jumlahpeserta;
        }


        if($psrta - $jmlhpesertadipesertas < $psrta && $psrta - $jmlhpesertadipesertas != 0){
            $data = new Peserta();
            $data->iddaftar     = $request->input('iddaftar');
            $data->nama         = $request->input('nama');
            $data->tempatlahir  = $request->input('tempatlahir');
            $data->tanggallahir = $request->input('tanggallahir');
            $data->alamat       = $request->input('alamat');
            $data->jeniskelamin = $request->input('jeniskelamin');
            $data->nik          = $request->input('nik');
            $data->email        = $request->input('email');
            $data->nohp         = $request->input('nohp');
            $data->ijazah       = $request->input('ijazah');
            $data->save();

            return redirect('tambahpeserta');
        }else{
            return view('gagaltambahpeserta');
        }
    }
}
