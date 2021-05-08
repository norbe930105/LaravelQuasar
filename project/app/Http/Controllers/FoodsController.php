<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Foods;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\Encriptar;

class FoodsController extends Controller
{

    /** @var Encriptar */
    private $crypt;


    public function __construct( Encriptar $crypt)
    {
        $this->crypt = $crypt;
    }

    public function show() {
        return Foods::with('file')->get();
    }

    public function store(Request $request) {
        $file = $request->file['file'];
        $arch = File::find($file['id']);
        $food = new Foods();
        $food->name = $request->name;
        $food->description = $request->description;
        $food->save();
        $arch->foods_id = $food->id;
        $arch->save();
        return response([
            'food' => $food,
        ], Response::HTTP_ACCEPTED);
    }

    public function update(Request $request) {
        $food = Foods::find($request->id);
        $food->name = $request->name;
        $food->description = $request->description;
        $food->save();
        return response([
            'food' => $food,
        ], Response::HTTP_ACCEPTED);
    }

    public function delete(Request $request) {
        $file = File::where('foods_id', $request->id)->delete();
        $food = Foods::find($request->id);
        $food->delete();
        return response([
            'message' => 'Comida eliminada',
        ], Response::HTTP_ACCEPTED);
    }

    public function upload(Request $request) {
        $type = $request->file('file')->getClientMimeType();
        $name = $request->file('file')->getClientOriginalName();
        $size = $request->file('file')->getSize();
        $data = $this->crypt->encrypt(file_get_contents($request->file('file')));
        $file = new File();
        $file->nombre = $name;
        $file->extension = $type;
        $file->tamanno = $size;
        $file->archivo = $data;
        $file->save();
        return response([
            'file' => $file,
        ], Response::HTTP_ACCEPTED);
    }
}
