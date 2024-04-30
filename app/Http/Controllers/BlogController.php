<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Traits\RequestTrait;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    use RequestTrait;

    public function index()
    {
        $blogs = Blog::query()->paginate(10);

        if ($blogs->isEmpty()) {

            return $this->getJsonResponse(null, "There Are No Blogs Found!");
        }


//        BlogResource::collection($blogs);

        return $this->getJsonResponse($blogs, "Blogs Fetched Successfully");
    }



    public function store(CreateBlogRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            $path = $request->file('image')->store('images/blogs','public');

            $data['image'] = $path;
        }

        $blog = Blog::query()->create($data);

        return $this->getJsonResponse($blog, "Blog Created Successfully");
    }


    public function show(Blog $blog): JsonResponse
    {
        return $this->getJsonResponse($blog, "Blog Fetched Successfully");
    }


    public function update(UpdateBlogRequest $request, Blog $blog): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            $path = $request->file('image')->store('images/blogs','public');

            $data['image'] = $path;
        }

        $blog->update($data);

        return $this->getJsonResponse($blog, "Blog Updated Successfully");
    }


    public function destroy(Blog $blog): JsonResponse
    {
        $blog->delete();

        return $this->getJsonResponse(null, "Blog Deleted Successfully");
    }
}
