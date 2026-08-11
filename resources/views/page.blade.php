@extends('layouts.app')

@section('content')

@include('header')

 <!-- PAGE TITLE
        ================================================== -->
        <section class="page-title-section">
            <div class="container">

                <div class="breadcrumbs-info">
                    <ul class="ps-0">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>{{ $page->title }}</li>
                    </ul>
                </div>

            </div>
        </section>

        <!-- PAGE CONTENT
        ================================================== -->
        <section class="md">
            <div class="container">
                <h2 class="mb-4">{{ $page->title }}</h2>
                <div class="page-body">
                    {!! $page->body !!}
                </div>
            </div>
        </section>

@endsection
