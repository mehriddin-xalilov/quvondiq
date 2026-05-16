@extends('layouts.app')

@section('title', 'Sertifikatni tahrirlash')
@section('page-title', 'Sertifikatni tahrirlash')

@section('content')
<div class="mt-4">
    <form action="{{ route('sertifikatlar.update', $sertifikat) }}" method="POST">
        @method('PUT')
        @include('sertifikatlar._form')
    </form>
</div>
@endsection