@extends('layouts.app')

@section('content')
        <div class="row ">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if(!empty($image->auxiliary05))
                            <div class="row">
                                <div class="col-lg-12">
                                    <div style=" display: grid;height: 100vh;margin: 0;padding: 0;">
                                        <div style="margin: auto;text-align: center">
                                            <img src="{{asset('storage/' . $image->auxiliary05)}}" class="align-self-center img-responsive" alt="Bk">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            <img src="{{asset('/img/login-register.jpg')}}" alt="" style="width: 100%;height: 80%">
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('script')

@endsection
