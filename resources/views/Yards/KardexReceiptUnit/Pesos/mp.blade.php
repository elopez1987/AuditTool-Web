<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table width="100%"
                       class="table table-condensed table-bordered  table-hover">
                    <thead>
                    <tr>
                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                            Folio
                        </th>
                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                            Número de ticket
                        </th>
                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                            KG ENTRADA
                        </th>
                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                            KG SALIDA
                        </th>
                        <th style="background-color: #2196f3 !important;color: white !important;text-transform: uppercase">
                            KG MATERIAL BRUTO
                        </th>
                        <th style="background-color: #673ab7 !important;color: white !important;text-transform: uppercase">
                            KG NETO
                        </th>
                        <th style="background-color: #673ab7 !important;color: white !important;text-transform: uppercase">
                            EMBALAJE
                        </th>
                        <th style="background-color: #2196f3 !important;color: white !important;text-transform: uppercase">
                            TOTAL
                        </th>
                        <th style="background-color: #e53935 !important;color: white !important;text-transform: uppercase">
                            DIF. KILOGRAMOS
                        </th>
                        <th style="background-color: #00e676 !important;color: black !important;text-transform: uppercase">
                            ESTATUS
                        </th>
                        <th style="background-color: #00e676 !important;color: black !important;text-transform: uppercase">
                            % VAR
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($peso)
                        <tr>
                            <td>{{$peso->folionumber}}</td>
                            <td>{{$peso->ticket}}</td>
                            <td>{{number_format($peso->weightin,2)}} Kg</td>
                            <td>{{number_format($peso->weightout,2)}} Kg</td>
                            <td>{{ number_format($peso->weightin - $peso->weightout,2)}}</td>
                            <td>{{number_format( $peso->kgneto,2)}} Kg</td>
                            <td>{{  number_format ($peso->embalaje,2) }} </td>
                            <td>{{number_format($peso->kgneto + $peso->embalaje,2)}} Kg</td>
                            <td>{{ number_format(($peso->weightin - $peso->weightout) - ($peso->kgneto + $peso->embalaje), 2) }}</td>
                            @php
                                $pesoMaterialBruto = $peso->weightin - $peso->weightout;
                                $pesoTotalMaterial = $peso->kgneto + $peso->embalaje;
                                $variacionPeso = $pesoTotalMaterial != 0 ? round(($pesoMaterialBruto / $pesoTotalMaterial) - 1, 4) : 0;
                                $pesoABS = abs($variacionPeso);
                                $status = ($pesoABS <= 0.01) ? 'OK' : 'REVISAR PESO';
                            @endphp

                            <td style="background-color: {{$pesoABS <= 0.01 ? '#00e676' : '#ff1744'}}">
                                {{$status}}
                            </td>
                            <td>
                                {{ number_format($variacionPeso * 100, 2) }}%
                            </td>
                        </tr>
                    </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>