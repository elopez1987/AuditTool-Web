@if(!empty($filebox))
    <div class="el-card-item">
        <div class="el-card-avatar el-overlay-1">
            <div class="el-overlay">
                <ul class="list-style-none el-info">
                    <li class="el-item bg-grey">
                        <a class="btn default btn-outline image-popup-vertical-fit el-link"
                           href="{{asset('storage/' . $filebox)}}" target="_blank">Ver
                            <i class="icon-magnifier"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endif
@if(!empty($path))
    <div class="el-card-item">
        <div class="el-card-avatar el-overlay-1">
            <div class="el-overlay">
                <ul class="list-style-none el-info">
                    <li class="el-item bg-grey">
                        <a class="btn default btn-outline image-popup-vertical-fit el-link"
                           href="{{asset('storage/' . $path)}}" target="_blank">Ver
                            <i class="icon-magnifier"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@else
    Sin evidencia
@endif

