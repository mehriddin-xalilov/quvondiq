@extends('layouts.app')

@section('title', 'Yangi mutaxassislik')
@section('page-title', 'Yangi mutaxassislik')

@section('content')
<form action="{{ route('professions.store') }}" method="POST" class="mt-4">
    @include('professions._form')
</form>
@endsection