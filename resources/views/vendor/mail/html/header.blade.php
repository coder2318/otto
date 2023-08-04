@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{ asset('build/assets/logo-row.svg') }}" class="logo" alt="{{ config('app.name') }}" width="250">
        </a>
    </td>
</tr>
