@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-9">
            <div class="card" style="background-color: #0b0b0b">
                <div class="card-body">
                    <div style="max-width: 100%;">
                        @if(Auth::user()->access->center->centerid == 'HENKELTOL01')
                            @include('Yards.LayOutYard.Yards.HenkelTolucaOneRoof.HenkelTol')
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            @foreach($result as $item)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card" style="border-right: 3px solid #cfdd01">
                                    <div class="d-flex flex-row">
                                        <div class="p-10">
                                            <h2 class="text-black-50 m-b-0">{{$item->total}}</h2>
                                            <span class="text-muted font-16">{{$item->razonname}}</span>
                                        </div>
                                        <div class=" ml-auto">
                                            <h3 class="text-white box m-b-0 "><i class="lni lni-layers"></i></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection

@section('script')

@endsection

