@extends('layouts.app')

@section('title', 'Guvohnomani tahrirlash')
@section('page-title', 'Guvohnomani tahrirlash')

@section('content')
<div class="mt-4">
    <form action="{{ route('guvohnomalar.update', $guvohnoma) }}" method="POST">
        @method('PUT')
        @include('guvohnomalar._form')
    </form>
</div>
@endsection