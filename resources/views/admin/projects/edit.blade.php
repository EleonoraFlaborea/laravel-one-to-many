@extends('layouts.app')

@section('title', 'Modifica Project')

@section('content')

 <header class="pb-4">
    <h1>Modifica progetto</h1>
 </header>

 @include('includes.projects.form')

@endsection

@section('scripts')
  @vite('resources/js/image_preview.js')
@endsection