@props(['title' => null])

@php
    $title = $title;
@endphp

@include('layouts.member', ['title' => $title, 'slot' => $slot])
