@extends('layouts.app')

@section('title', 'Yangi sertifikat')
@section('page-title', 'Yangi sertifikat yaratish')

@section('content')
<div class="mt-4">
    <form action="{{ route('sertifikatlar.store') }}" method="POST">
        @include('sertifikatlar._form')
    </form>
</div>
@endsection