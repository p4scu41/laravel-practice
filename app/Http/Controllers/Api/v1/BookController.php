<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\BookRegistered;
use App\Events\BookRemoved;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Jobs\BookJob;
use App\Mail\BookCreated;
use App\Mail\BookDeleted;
use App\Models\Book;
use App\Notifications\BookUpdated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class BookController extends Controller
{
    /**
     * index
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

        return BookResource::collection($booksQuery->paginate($request->integer('per_page', 10)));
    }

    /**
     * store
     */
    public function store(StoreBookRequest $request)
    {
        // Gate::authorize('create', Book::class); // Moved to FormRequest

        $book = Book::create($request->validated());

        BookJob::dispatch($book);

        Mail::to($request->user())->queue((new BookCreated($book))->afterCommit());

        BookRegistered::dispatch($book);

        return (new BookResource($book))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * show
     */
    public function show(Book $book)
    {
        Gate::authorize('view', $book);

        return new BookResource($book->load('category:id,description'));
    }

    /**
     * update
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        // Gate::authorize('update', $book); // Moved to FormRequest

        $book->update($request->validated());

        Auth::user()->notify(new BookUpdated($book));
        // Notification::send(Auth::user(), new BookUpdated($book));

        return new BookResource($book->load('category:id,description'));
    }

    /**
     * destroy
     */
    public function destroy(Book $book)
    {
        Gate::authorize('delete', $book);

        $book->delete();

        Mail::to(Auth::user())->send(new BookDeleted($book));

        BookRemoved::dispatch($book);

        return new BookResource($book);
    }
}
