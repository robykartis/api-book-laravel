<?php

namespace App\Http\Controllers;

use App\Http\Resources\BooksResource;
use App\Models\BookModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = BookModel::with("category")->paginate();
        $booksResource = BooksResource::collection($books);
        return $this->sendResponse($booksResource, "Successfuly Get books");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            "name" => "required|min:4",
            "description" => "required|min:10|max:300",
            "price" => "required",
            "image" => "required|mimes:png,jpg,jpeg,webp|max:2048"
        ]);
        if ($validator->fails()) {
            return $this->sendError("Validation Error", $validator->errors());
        }

        $uploadFile = $request->image;
        $fileName = uniqid() . "." . $uploadFile->getClientOriginalExtension();
        $uploadFile->move(public_path() . '/images', $fileName);
        $input["image"] = $fileName;
        $book = BookModel::create($input);
        $finbook = BookModel::with("category")->find($book->id);
        return $this->sendResponse(new BooksResource($finbook), "Books Create Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = BookModel::with("category")->find($id);
        return $this->sendResponse(new BooksResource($book), "Books Get Successfully");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            "name" => "required|min:4",
            "description" => "required|min:10|max:300",
            "price" => "required"
        ]);
        if ($validator->fails()) {
            return $this->sendError("Validation Error", $validator->errors());
        }
        $book = BookModel::find($id);
        $book->name = $input["name"];
        $book->description = $input["description"];
        $book->price = $input["price"];
        $book->category_id = $input["category_id"];
        $book->save();
        $finbook = BookModel::with("category")->find($book->id);
        return $this->sendResponse(new BooksResource($finbook), "Books Update Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = BookModel::find($id);
        $book->delete();
        return $this->sendResponse(new BooksResource($book), "Books Delete Successfully");
    }
}
