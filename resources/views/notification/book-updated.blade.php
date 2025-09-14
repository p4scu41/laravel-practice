<x-mail::message>
# Book Updated

{{ $book->name }}

<x-mail::button :url="$url">
View Book
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
