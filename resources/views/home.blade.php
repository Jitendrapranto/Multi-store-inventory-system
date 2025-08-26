<h1>Home</h1>

{{-- @session('name')
    {{ Session::get('name') }}
@endsession --}}
{{-- @if(session('name'))
 {{ session()->get('name') }}
@endif --}}


@if(session()->get('status'))
<h1>{{ session()->get('status') }}</h1>
@else
 <h1>{{ "no status" }}</h1>
@endif