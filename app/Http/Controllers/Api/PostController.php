<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
// use App\Http\Controllers\Api\ApiResponseTrait;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ApiResponseTrait;

    ///////////////////////// Show All ////////////////////////
    
    public function index()
    {
        $posts =PostResource::collection(Posts::get());
        
        return $this->apiResponse($posts,'Done', true);
    }

    ///////////////////////// Show By Id ////////////////////////

    public function show($id){
        $post = Posts::find($id);

        if($post){
            return $this->apiResponse(new PostResource($post),'Done', true);
        }
        else {
            return $this->apiResponse(null,'the post not found', false);
        }
        
    }

    /////////////////////////// store /////////////////////////////

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'title'=>'required|max:255',
            'body'=>'required'
        ]);

        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(), false);
        }
        $post = Posts::create($request->all());

        if($post){
            return $this->apiResponse(new PostResource($post),'The Post Saved', true);
        }
        else {
            return $this->apiResponse(null,'Not Save', false);
        }
    }


    /////////////////////////// Update /////////////////////////////

    public function update(Request $request , $id){
        $validator = Validator::make($request->all(),[
            'title'=>'required|max:255',
            'body'=>'required'
        ]);

        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(), false);
        }

        $post = Posts::find($id);

        

        if($post){
            $post->update($request->all());
            return $this->apiResponse(new PostResource($post),'The Post Updated', true);
        }
        else {
            return $this->apiResponse(null,'The Post Not Updated', false);
        }
    }

    /////////////////////////// Delete /////////////////////////////

    public function destroy($id){

        $post = Posts::find($id);

        if($post){
            $post->delete($id);
            // Posts::distroy($id);
            return $this->apiResponse(null,'The Post Deleted', true);
        }
        else {
            return $this->apiResponse(null,'The Post Not Found', false);
        }
    }

}
