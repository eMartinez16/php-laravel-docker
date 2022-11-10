<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{asset('storage/images/VYV_LOGO.png')}}" class="logo" alt="VyV Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
