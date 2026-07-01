@php
    $role         = Auth::user()->role?->slug ?? '';
    $isInstructor = in_array($role, ['professeur', 'instructor', 'teacher']);
    $completion   = $profile->completionPercentage();
@endphp

@if($isInstructor)
<x-instructor-layout>
    <x-slot name="title">Mon profil</x-slot>
    <x-slot name="header">@include('profile.partials.profile-header')</x-slot>
    @include('profile.partials.profile-content')
</x-instructor-layout>
@else
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mon profil — {{ config('app.name') }}</title>
    <link href="{{ asset('plugins/font-awesome-6.4.0/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="wrapper">
    @php $isStudent = in_array($role, ['apprenant','student','learner','subscriber']); @endphp
    @if($isStudent)
        @include('profile.partials.student-sidebar')
    @elseif(in_array($role, ['conseiller-pedagogique','counselor','advisor','conseiller']))
        @include('profile.partials.conseiller-sidebar')
    @elseif(in_array($role, ['inspecteur','inspector']))
        @include('profile.partials.inspecteur-sidebar')
    @endif
    <div class="main">
        <x-admin-navbar />
        <main class="content">
            <div class="container-fluid p-0">
                @include('profile.partials.profile-header')
                @include('profile.partials.profile-content')
            </div>
        </main>
        <x-admin-footer />
    </div>
</div>
<script src="{{ asset('plugins/jquery/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('plugins/font-awesome-6.4.0/js/all.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
@endif
