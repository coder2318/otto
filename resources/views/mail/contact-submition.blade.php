<x-mail::message>
# New Contact Form Submission

<x-mail::table>
| Field       | Value          |
| :---------- | :------------- |
| **Name**    | {{ $name }}    |
| **Email**   | {{ $email }}   |
| **Phone**   | {{ $phone }}   |
| **Message** | {{ $message }} |
</x-mail::table>
</x-mail::message>
