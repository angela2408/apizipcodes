<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class ZipcodeController extends Controller
{
    public function index()
    {
        $mensaje = "Bienvenido al API de proyectos";
        //
        return $mensaje;
        //
    }

    public function Estados($id)
    {
        $datajson = array();

        $zipcode = DB::select('SELECT * FROM laravel.codigos_postales where d_codigo = '.$id);
        //dd($zipcode);

        foreach ($zipcode as $obj ) {

            $federal = $this->FederalEntity($id,$obj->d_estado);
            $settlements = $this->settlements($id,$obj->d_estado);
            $municipality = $this->municipality($id,$obj->d_estado);

            $datajson = array(
                'zip_code' => $obj->d_codigo,
                'locality' => $obj->d_ciudad,
                'federal_entity' => $federal,
                'settlements' => $settlements,
                'municipality' => $municipality,
            );
            # code...
        }
        return json_encode($datajson,JSON_NUMERIC_CHECK);
    }


    public function FederalEntity($id,$estado)
    {

        $zipcodeFederal = DB::select('SELECT * FROM laravel.codigos_postales where d_codigo = '.$id.' and d_estado = '."'$estado'");
        $datajson = array();
        foreach ($zipcodeFederal as $obj ) {
            $datajson = array(
                'key' => $obj->c_estado,
                'name' => $obj->d_estado,
                'code' => $obj->c_CP,
            );
            # code...
        }

        return $datajson;
    }

    public function settlements($id,$estado)
    {
        $zipcodeSett = DB::select('SELECT * FROM laravel.codigos_postales where d_codigo = '.$id.' and d_estado = '."'$estado'");
        $datajson = array();
        foreach ($zipcodeSett as $obj ) {
            $datajson[] = array(
                'key' => $obj->id_asenta_cpcons,
                'name' => $obj->d_asenta,
                'zone_type' => $obj->d_zona,
                'settlement_type' => array(
                    'name' => $obj->d_tipo_asenta
                )
            );
            # code...
        }
        //
        return $datajson;
    }


    public function municipality($id,$estado)
    {
        $zipcodeMuni = DB::select('SELECT * FROM laravel.codigos_postales where d_codigo = '.$id.' and d_estado = '."'$estado'");
        $datajson = array();
        foreach ($zipcodeMuni as $obj ) {
            $datajson = array(
                'key' => $obj->c_mnpio,
                'name' => $obj->D_mnpio,
            );
            # code...
        }
        //
        return $datajson;
    }

}
