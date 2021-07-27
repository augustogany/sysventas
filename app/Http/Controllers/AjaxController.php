<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Luecano\NumeroALetras\NumeroALetras;
use DB;

class AjaxController extends Controller
{
    public function getproviders(Request $request){

        $search = $request->search;

        if($search == ''){
        $providers = Contact::orderby('business_name','asc')
                            ->select('id','business_name as text','ci')
                            ->where('type','provider')
                            ->limit(5)->get();
        }else{
        $providers = Persona::orderby('business_name','asc')
                            ->select('id','business_name as text','ci')
                            ->where('type','provider')
                            ->where('ci', 'like', '%' .$search . '%')
                            ->limit(5)->get();
        }

        return response()->json($providers);
    }

    public function getcustomers(Request $request){

        $search = $request->search;

        if($search == ''){
        $customers = Contact::orderby('fullName','asc')
                            ->select('id','fullName as text','ci')
                            ->where('type','customer')
                            ->limit(5)->get();
        }else{
        $customers = Persona::orderby('fullName','asc')
                            ->select('id','fullName as text','ci')
                            ->where('type','customer')
                            ->where('ci', 'like', '%' .$search . '%')
                            ->limit(5)->get();
        }

        return response()->json($customers);
    }

    public function imprimir($id){
        $certificado = DB::table('certificates as cer')
            ->join('personas as per', 'cer.persona_id', '=', 'per.id')
            ->join('departamentos as dep', 'per.departamento_id', '=', 'dep.id')
            ->select([
                'cer.id','cer.codigo','cer.type','cer.price', 'per.full_name', 'per.ci','dep.sigla',
                'cer.descripcion','cer.deuda','cer.monto_deuda','per.alfanum',
                DB::raw("DATE_FORMAT(cer.created_at, '%d/%m/%Y') as fecha"),
                DB::raw("DATE_FORMAT(cer.created_at, '%H:%i:%S') as hora")
            ])
            ->where('cer.id',$id)
            ->first();
            $monto_literal = (new NumeroALetras())->toInvoice($certificado->deuda, 2, 'BOLIVIANOS', 'CENTAVOS');
        return view('livewire.certificates.certif', compact('certificado','monto_literal'));
    }
}
