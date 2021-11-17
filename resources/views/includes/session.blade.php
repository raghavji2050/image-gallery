@if(session()->has('message'))
@php $message = session('message'); @endphp
  <p class="alert alert-{{$message['status']}}">{!! $message['text'] !!}</p>
@endif
