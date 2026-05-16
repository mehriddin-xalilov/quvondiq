@extends('layouts.app')

@section('title', 'Yangi guvohnoma')
@section('page-title', 'Yangi guvohnoma yaratish')

@section('content')
<div class="mt-4">
    <form action="{{ route('guvohnomalar.store') }}" method="POST">
        @include('guvohnomalar._form')
    </form>
</div>
@endsection