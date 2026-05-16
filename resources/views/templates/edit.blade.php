@extends('layouts.app')

@section('title', 'Shablonni tahrirlash')
@section('page-title', 'Shablonni tahrirlash')

@section('content')
<form action="{{ route('templates.update', $template) }}" method="POST" enctype="multipart/form-data" class="mt-4">
    @method('PUT')
    @include('templates._form')
</form>
@endsection