<?php

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Exceptions;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

function booksUrl(?string $resource = null): string
{
    return "/api/v1/books/$resource";
}

beforeEach(function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $user->createToken('token', ['books-admin']);
    $user->withAccessToken($user->tokens->first());
});

test('index', function () {
    $book = Book::factory()->create();

    $response = $this->getJson(booksUrl());

    $response->assertOk()
        ->assertJson([
            'data' => [
                $book->toArray(),
            ],
        ]);
});

test('index - Unauthenticated', function () {
    Exceptions::fake();
    Auth::forgetUser();

    $response = $this->getJson(booksUrl());

    $response->assertUnauthorized();

    Exceptions::assertNotReported(AuthenticationException::class);
});

test('index - wrong ability', function () {
    Exceptions::fake();
    $user = User::factory()->create();

    $user->createToken('token', ['guest']);
    $user->withAccessToken($user->tokens->first());

    Auth::setUser($user);

    $response = $this->getJson(booksUrl());

    $response->assertForbidden();

    Exceptions::assertNotReported(AuthenticationException::class);
});

test('index - filterByCategory', function () {
    $book = Book::factory()->create();

    $response = $this->getJson(booksUrl(), ['category_id' => $book->category_id]);

    $response->assertOk()
        ->assertJson([
            'data' => [
                $book->toArray(),
            ],
        ]);
});

test('index - filterByName', function () {
    $book = Book::factory()->create();

    $response = $this->getJson(booksUrl(), ['name' => $book->name]);

    $response->assertOk()
        ->assertJson([
            'data' => [
                $book->toArray(),
            ],
        ]);
});

test('store', function () {
    $category = Category::factory()->create();

    $attributes = [
        'category_id' => $category->id,
        'name' => 'lorem ipsum 2',
        'published_at' => '2025-01-28',
    ];

    $response = $this->postJson(booksUrl(), $attributes);

    $response->assertCreated()
        ->assertJson($attributes);
});

test('store - validation', function () {
    $emptyAttributes = [];

    $response = $this->postJson(booksUrl(), $emptyAttributes);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'category_id',
            'name',
            'published_at',
        ]);
});

test('show', function () {
    $book = Book::factory()->create();

    $response = $this->getJson(booksUrl($book->id));

    $response->assertOk()
        ->assertJson($book->toArray());
});

test('show - not found', function () {
    Exceptions::fake();

    $response = $this->withHeaders(['Accept' => 'application/json'])->get(booksUrl(1));

    $response->assertNotFound();

    Exceptions::assertNotReported(NotFoundHttpException::class);
});

test('update', function () {
    $book = Book::factory()->create();
    $updatedAttributes = [
        'name' => 'Updated Book'
    ];

    $response = $this->putJson(booksUrl($book->id), $updatedAttributes);

    $response->assertSuccessful()
        ->assertJson($updatedAttributes);
});

test('destroy', function () {
    $book = Book::factory()->create();

    $response = $this->deleteJson(booksUrl($book->id));

    $response->assertOk();

    $this->assertModelMissing($book);
});
