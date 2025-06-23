@extends('layouts.app')


@section('content')
    <main>
        <div class="container__box">

            @can('storagetags.index')
                <a href="{{route('storagetags.index')}}">
                    <div class="box">
                        <i class="lni lni-tag"></i>
                        <h5>Tags</h5>
                        <h4>Tags</h4>
                    </div>
                </a>
            @endcan

                @can('storagesafetytourtype.index')
                    <a href="{{route('storagesafetytourtype.index')}}">
                        <div class="box">
                            <i class="lni lni-tag"></i>
                            <h5>Armar recorridos</h5>
                            <h4>Armar recorridos</h4>
                        </div>
                    </a>
                @endcan


            @can('storagesafetytours.index')
                <a href="{{route('storagesafetytours.index')}}">
                    <div class="box">
                        <i class="lni lni-tag"></i>
                        <h5>Recorridos</h5>
                        <h4>Recorridos</h4>
                    </div>
                </a>
            @endcan


        </div>
    </main>

@endsection

