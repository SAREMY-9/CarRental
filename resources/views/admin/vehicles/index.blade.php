@extends('layouts.admin')


@section('content')
<div class="container">
    <h1>Vehicle List</h1>
    @if($vehicles->count())
        <table class="table">
            <thead>
                <tr>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Price/Day</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->make }}</td>
                    <td>{{ $vehicle->model }}</td>
                    <td>{{ $vehicle->type->name ?? 'N/A' }}</td>
                    <td>{{ $vehicle->location->name ?? 'N/A' }}</td>
                    <td>{{ $vehicle->price_per_day }}</td>
                    <td>{{ $vehicle->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No vehicles found.</p>
    @endif
</div>
@endsection
