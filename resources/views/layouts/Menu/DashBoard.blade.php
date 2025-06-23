<li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                            aria-expanded="false">
        <img src="{{asset('/ico/dashboard.png')}}" alt="" class="img-25">
        <span class="hide-menu mr-left"> Graficas</span></a>
    <ul aria-expanded="false" class="collapse first-level">

        @can('viewdashboardyard')
            <li class="sidebar-item"><a href="{{route('viewdashboardyard')}}" class="sidebar-link">
                    <img src="{{asset('/ico/dashboard.png')}}" alt="" class="img-16">
                    <span class="hide-menu mr-left"> Control de patios</span></a>
            </li>
        @endcan
        <li class="sidebar-item"><a href="{{route('dashboard')}}" class="sidebar-link">
                <img src="{{asset('/ico/dashabord02.png')}}" alt="" class="img-16">
                <span class="hide-menu mr-left"> Embarques</span></a>
        </li>
        <li class="sidebar-item"><a href="{{route('dashboard')}}" class="sidebar-link">
                <img src="{{asset('/ico/dashabord02.png')}}" alt="" class="img-16">
                <span class="hide-menu mr-left"> Recibos</span></a>
        </li>

    </ul>
</li>
