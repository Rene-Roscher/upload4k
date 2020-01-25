<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Cassandra\Session;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use SoareCostin\FileVault\Facades\FileVault;
use ZipArchive;
use function Illuminate\Support\Facades\Response;
use function SoareCostin\FileVault\FileVault;

class HandleController extends Controller
{

    public function index()
    {
        return view('index');
    }

    public function upload()
    {
        $files = $_FILES['files'];

        $zipName = date("YmdHis", time()).'-'.rand(9999,10000).'.zip';
        $storage = storage_path('app\\'.$zipName);

        $zip = new ZipArchive;
        $zip->open($storage, ZipArchive::CREATE);

        for ($i = 0; $i < count($files['name']); $i++) {
            $name = $files['name'][$i];
            $tmpPath = $files['tmp_name'][$i];
            $zip->addFromString($name, file_get_contents($tmpPath));
        }
        $zip->close();

        FileVault::encrypt($zipName);
        $encryptUri = encrypt($zipName);
        return url('').'/download/'.$encryptUri;
    }

    public function download($downloadKey)
    {
        $fileName = decrypt($downloadKey);
        FileVault::decryptCopy($fileName . '.enc');
        return \response()->download(storage_path('app/' . $fileName))->deleteFileAfterSend(env('DELETE_AFTER_DOWNLOAD', false));
    }

}
