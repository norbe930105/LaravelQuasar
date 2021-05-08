<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Utils\Encriptar;

class FileController extends Controller
{
    /** @var Encriptar */
    private $crypt;


    public function __construct( Encriptar $crypt)
    {
        $this->crypt = $crypt;
    }

    public function getById(Request $request){
        $file = File::find($request->id);
        return response($file)->header('Content-Type', $file->extension);
    }
}
