@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="container">
    <div class="site-title">
        <div class="m-0">
            <div class="dropdown">
                <button class="btn btn-lg dropdown-toggle" type="button" id="domainDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="pi pi-link"></i> {{ $domain }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="domainDropdown">
                    @foreach ($sites as $site)
                    <li>
                        <a class="dropdown-item" href="{{ route('home', ['domain' => $site['fqdn']]) }}" data-domain="{{ $site['fqdn'] }}">{{ $site['fqdn'] }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <site-dashboard domain="{{ $domain }}" with-not-found="{{ $show_404_widget }}" with-external="{{ $show_external_links_widget }}"></site-dashboard>
</div>
@endsection