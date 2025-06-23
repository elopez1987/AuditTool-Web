@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    Agregar lista de contactos
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storeLabelDetail/' . $label->labelid)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventorylabels.index')}}">
                                    @include('buttons.back')
                                </a>
                                <a >
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md" id="limpiar">
                                        <img src="{{asset('/ico/addestroy1.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Limpiar</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <hr class="mt-4 mb-4">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Etiqueta <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" disabled id="namelabel" name="namelabel"
                                           maxlength="15"
                                           aria-describedby="namelabel" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{$label->auxiliary01}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10">
                                <table class="table table-condensed table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th width="10%">Secuencia</th>
                                        <th class="bg-inverse text-white">Descripción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($labelDetail as $detail)
                                        <tr>
                                            <td>
                                                <input type="text" value="{{$detail->id}}">
                                            </td>
                                            <td>
                                                <input type="text" value="{{$detail->description}}"
                                                       class="form-control" name="description[]">
                                            </td>
                                        </tr>
                                    @empty
                                        @for($i=1;$i <= 50 ;$i++)
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control" value="{{$i}}" disabled>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="description[]">
                                                </td>
                                            </tr>
                                        @endfor
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#limpiar').click(function() {
                $('input[type="text"]').val('');
            });
        });
    </script>
@endsection

