<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Book::class);

        $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $booksQuery = Book::with('category:id,description');

        $request->whenHas('category_id', fn(int $category_id) => $booksQuery->filterByCategory($category_id));

        $request->whenHas('name', fn(string $name) => $booksQuery->filterByName($name));

        return $booksQuery->paginate($request->integer('per_page', 10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        // Gate::authorize('create', Book::class); // Moved to FormRequest

        $book = Book::create($request->validated());

        return Response::json($book, HttpResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        Gate::authorize('view', $book);

        return $book->load('category:id,description');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        // Gate::authorize('update', $book); // Moved to FormRequest

        $book->update($request->validated());

        return $book->load('category:id,description');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        Gate::authorize('delete', $book);

        $book->delete();

        return $book;
    }
}
