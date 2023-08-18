<x-mail::message>
# Welcome to the OTTO Story!

We created a password for you to log in:

<x-mail::panel>
{{ $password }}
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

