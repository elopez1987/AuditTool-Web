<div class="btn-group">
    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm dropdown-toggle"
            data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
        <i class="ti ti-settings"></i>
        Acciones
    </button>
    <div class="dropdown-menu animated flipInX">


        @can('securityusers.edit')
            <a href="{{url('securityusers/' . $uuiduser. '/edit')}}" class="dropdown-item">
                <img src="{{asset('/ico/edituser.png')}}" alt="" class="img-16 ml-2">
                <span style="text-transform: capitalize;">Modificar registro</span>
            </a>
        @endcan

        <a href="{{url('securityusers/' . $uuiduser . '/securityaccesswarehouse')}}" class="dropdown-item">
            <img src="{{asset('/ico/systemcenterdistribution.png')}}" alt="" class="img-16 ml-2">
            <span style="text-transform: capitalize;">Registrar acceso a bodegas</span>
        </a>
        @can('securityusers.accesscarriers')
            <a href="{{url('securityusers/' . $uuiduser . '/securityaccesscarriers')}}" class="dropdown-item">
                <img src="{{asset('/ico/receiptunits.png')}}" alt="" class="img-16 ml-2">
                <span style="text-transform: capitalize;">Vincular Lineas de Transporte</span>
            </a>
        @endcan

        @can('securityusers.accesscustomer')
            <a href="{{url('securityusers/' . $uuiduser . '/securityaccesscustomer')}}" class="dropdown-item">
                <img src="{{asset('/ico/storageproviders.png')}}" alt="" class="img-16 ml-2">
                <span style="text-transform: capitalize;">Vincular Clientes</span>
            </a>
        @endcan

        @can('securityusers.accessproviders')
            <a href="{{url('securityusers/' . $uuiduser . '/providers')}}" class="dropdown-item">
                <img src="{{asset('/ico/storageproviders.png')}}" alt="" class="img-16 ml-2">
                <span style="text-transform: capitalize;">Vincular Proveedores</span>
            </a>
        @endcan

        @can('securityusers.parameter')
            <a href="{{url('securityusers/' . $uuiduser. '/parameter')}}" class="dropdown-item">
                <img src="{{asset('/ico/systemparameters.png')}}" alt="" class="img-16 ml-2">
                <span style="text-transform: capitalize;">Parametrizar usuario</span>
            </a>
        @endcan

    </div>
</div>
