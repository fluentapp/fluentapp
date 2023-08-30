@extends('layouts.app')
@section('title', 'Manage Site')

@section('content')
<div class="container">
    <Site-Managment domain="{{ $domain }}" timezone="{{ $timezone }}" />
</div>
@endsection