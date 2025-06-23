@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Registrar comentario a Unidad
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h3 class="box-title m-t-40">Información General</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td width="390">Folio</td>
                                        <td> {{$receiptunit->id}} </td>
                                    </tr>
                                    <tr>
                                        <td>Linea de transporte</td>
                                        <td> {{$receiptunit->carrier->name}} </td>
                                    </tr>
                                    <tr>
                                        <td>Placa de caja</td>
                                        <td>{{$receiptunit->boxnumber}} </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <form method="POST" action="{{url('receiptunitscommentssave/' . $receiptunit->id)}}"
                                  enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <a href="">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('receiptunits.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Comentario</label>
                                            <textarea class="form-control" rows="10" name="comment"
                                                      placeholder="Ingrese un comentario corto..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6" style="height: 500px;overflow-y: scroll;">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            Historico
                                        </div>
                                        <div class="card-body">
                                            @foreach($comments as $comment)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="p-2"><img src="{{asset('/ico/edilogs.png')}}"
                                                                              alt="user" width="50"
                                                                              class="rounded-circle">{{$comment->user->name}}
                                                        </div>
                                                        <div class="comment-text active w-100">
                                                            <h6 class="font-medium"></h6>
                                                            <span class="m-b-15 d-block">{{$comment->comment}} </span>
                                                            <div class="comment-footer ">
                                                                <span
                                                                    class="text-muted float-right">{{$comment->created_at}}</span>
                                                                <span
                                                                    class="label label-success label-rounded">Approved</span>
                                                                <span class="action-icons active">
                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/quill/dist/quill.min.js')}}"></script>
    <script>
        var quill = new Quill('#comment', {
            theme: 'snow'
        });
    </script>
@endsection

