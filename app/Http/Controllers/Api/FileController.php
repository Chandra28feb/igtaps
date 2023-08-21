<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function upload(Request $request){
        try {
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'photo'=>'required|image|mimes:jpg',
            ]);
            if ($validator->fails()) {
                return response(['error' => $validator->errors(),'success' =>false],422);
            }
            if($request->hasFile('photo')) {
              $photo = file_get_contents($request->file('photo'));
              $ext = $request->file('photo')->getClientOriginalExtension();
              $path = time().'.'.$ext;
              Storage::disk('public')->put($path, $photo);
              $input['photo']='storage/'.$path;
            }
            Image::create($input);
            return response()->json([
                'message' => 'File Uploaded successfully',
                'success' =>true,
                'status'=>200
            ]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => __('server error'),
                'success' =>false
            ], 500);
        }
    }
    public function xmlUpload(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'xml'=>'required|mimes:xml',
            ]);
            if ($validator->fails()) {
                return response(['error' => $validator->errors(),'success' =>false],422);
            }
            $xmlData = simplexml_load_file($request->file('xml'));
            $duplicate = 0;
            $inserted = 0;
            foreach ($xmlData->user as $item) {
                $user = new User;
                $user->name = $item->name;
                $user->email = $item->email;
                $user->mobile = $item->mobile;
                $user->password = bcrypt($item->password);
                $emailCount = User::whereEmail($item->email)->count();
                if($emailCount>0){
                    $duplicate++;
                    continue;
                }
                $user->save();
                $inserted++;
            }
            return response()->json([
                'message' => $inserted.' users inserted successfully and '. $duplicate.' duplicate record found',
                'success' =>true,
                'status'=>200
            ]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => __('server error'),
                'success' =>false
            ], 500);
        }
    }
    public function userLists(){
        $users = User::orderBy('id','desc')->get();
        return response()->json($users);
    }
}
