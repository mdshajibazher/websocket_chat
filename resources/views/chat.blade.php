@extends('layouts.app')

@section('content')
<div class="container">
    <chat-component :user="{{Auth::user()}}"/>
</div>
@endsection
