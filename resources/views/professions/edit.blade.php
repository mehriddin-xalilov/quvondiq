@extends('layouts.app')

@section('title', 'Mutaxassislikni tahrirlash')
@section('page-title', 'Mutaxassislikni tahrirlash')

@section('content')
<form action="{{ route('professions.update', $profession) }}" method="POST" class="mt-4">
    @method('PUT')
    @include('professions._form')
</form>
@endsection