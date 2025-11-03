@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Jobs
                </div>
                <div class="card-body">
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-10">
                            <table class="table table-bordered  table-condensed tablegeneral">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>queue</th>
                                    <th>payload</th>
                                    <th>attempts</th>
                                    <th>reserved_at</th>
                                    <th>available_at</th>
                                    <th>created_at</th>
                                </tr>
                                </thead>
                                @if(!empty($jobs))
                                    <tbody>
                                        @foreach($jobs as $job)
                                            <tr>
                                                <td> {{$job->id}}</td>
                                                <td> {{$job->queue}}</td>
                                                <td> {{$job->payload}}</td>
                                                <td> {{$job->attempts}}</td>
                                                <td> {{$job->reserved_at}}</td>
                                                <td> {{$job->available_at}}</td>
                                                <td> {{$job->created_at}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('css')

@endsection


@section('script')
    @include('layouts.Partials.DataTable')
@endsection
