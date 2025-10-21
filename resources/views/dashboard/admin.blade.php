@extends('layout')
@section('title', 'Dashboard Admin')

@section('content')
<div class="card">
<div class="card-body">
     <h4>Dashboard Admin</h4>
    <div class="row">
        @foreach($data as $label => $value)
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">{{ str_replace('total', '', $label) }}</h5>
                        <h3>{{ $value }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>
@endsection
