<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\fileExists;
use App\Http\Controllers\API\BaseController as BaseController;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['post'] = Post::all();
    

       return $this->SendResponse($data,'data fetched succesfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),[
                'title'=>'required',
                'description'=>'required',
                'image'=>'required|mimes:png,jpg,jpeg'
            ]
            );
            if($validator->fails()){
              ;

                return $this->sendError('please check credentia',$validator->errors()->all(),401);

            }
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();

            $imageName = time(). "." . $ext;
            $image->move(public_path().'/uploads', $imageName);

           $post =  Post::create([
                'title'=>$request->title,
                'description'=>$request->description,
                'image'=>$imageName
            ]);

         return $this->SendResponse($post,'post create sucessfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['post']=Post::select(
        'id',
        'title',
        'description',
        'image'
        )->where(['id' => $id])->get();
        return $this->SendResponse($data,'the single post of user');
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        
        $validator  = Validator::make(
            $request->all(),[
                'title'=>'required',
                'description'=>'required',
                'image'=>'required|mimes:png,jpg,jpeg'
            ]
            );
            if($validator->fails()){
             
                return $this->sendError('please check credential',$validator->errors()->all());

            }
                $postImage = Post::select('id','image')->where(['id'=>$id])->get();
             
            if($request->image != ''){
                    $path = public_path().'/uploads/';
                    if($postImage[0]['image'] != '' && $postImage[0]['image'] != null){
                            $old_file   = $path .$postImage[0]['image'];
                            if(fileExists($old_file)){
                                unlink($old_file);
                            }
                    }
                    $image = $request->image;
                    $ext = $image->getClientOriginalExtension(); // ✅ Correct method
                    $uploadPath = public_path('uploads');
                    $imageName = time(). "." . $ext;
                    $image->move(public_path().'/uploads', $imageName);
            }else{
                $imageName = $postImage[0]['image'];     
            }


      

           $post =  Post::where(['id'=>$id])->update([
                'title'=>$request->title,
                'description'=>$request->description,
                'image'=>$imageName
            ]);

           
            return $this->SendResponse($post,'post updated succefully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imge_path  = Post::select('image')->where('id',$id)->get();
        $file_path  = public_path().'/uploads/'.$imge_path[0]['image'];
        unlink($file_path);
        $post  = Post::where('id',$id)->delete();

      

        return $this->SendResponse($post,'post delete succesfully');

    }
}
