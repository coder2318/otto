@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
            <img src="{{ asset('build/assets/logo.png') }}" class="logo" alt="{{ config('app.name') }}" height="50">
            <span>{{ config('app.name') }}</span>
        </a>
    </td>
</tr>
