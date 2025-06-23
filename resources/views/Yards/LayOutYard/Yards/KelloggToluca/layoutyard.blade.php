@extends('layouts.app')

@section('content')
    @can('layouttoluca')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div style="max-width: 100%;align-content: center;margin: auto">
                            @include('Yards.LayOutYard.Yards.KelloggToluca.Toluca')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection


@section('script')

@endsection
