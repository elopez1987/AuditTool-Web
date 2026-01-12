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
                            Peso de entrada kilogramos
                        </th>
                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                            Peso de entrada libras
                        </th>
                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                            Peso de salida kilogramos
                        </th>
                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                            Peso de salida libras
                        </th>
                        <th style="background-color: #2196f3 !important;color: white !important;text-transform: uppercase">
                            Peso de material kilogramos
                        </th>
                        <th style="background-color: #66bb6a !important;color: white !important;text-transform: uppercase">
                            Peso de material libras
                        </th>
                        <th style="background-color: #673ab7 !important;color: white !important;text-transform: uppercase">
                            Peso de SAP libras
                        </th>
                        <th style="background-color: #e53935 !important;color: white !important;text-transform: uppercase">
                            % de diferencia
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$peso->folionumber}}</td>
                        <td>{{$peso->ticket}}</td>
                        <td>{{number_format($peso->weightin,2)}} Kg</td>
                        <td>{{ number_format($peso->weightinlb,2) }} lbs</td>
                        <td>{{number_format($peso->weightout,2)}} Kg</td>
                        <td>{{ number_format($peso->weightoutlb,2)}} lbs</td>
                        <td>{{number_format( $peso->weightout - $peso->weightin,2)}} Kg</td>
                        <td>{{  number_format ($peso->weightoutlb - $peso->weightinlb,2) }} lbs</td>
                        <td>{{number_format($peso->weightoutsap,2)}} lbs</td>
                        <td>{{number_format($peso->value01,2)}} %</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>