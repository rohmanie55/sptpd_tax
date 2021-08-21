@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('content')
@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif

<div class="text-center">
<h4>You are logged in!</h4>
</div>
@endsection