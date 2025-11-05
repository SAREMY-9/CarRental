@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-10">
        @livewire('booking-form', ['vehicle' => $vehicle])
    </div>
@endsection
