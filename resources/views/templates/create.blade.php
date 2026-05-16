@extends('layouts.app')

@section('title', 'Yangi shablon')
@section('page-title', 'Yangi shablon yuklash')

@section('content')
<form action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
    @include('templates._form')
</form>
@endsection
