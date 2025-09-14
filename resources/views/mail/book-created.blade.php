<x-mail::message>
# Book Created

<x-mail::panel>
{{ $book->name }}
</x-mail::panel>

<x-mail::button :url="$url">
View Book
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
