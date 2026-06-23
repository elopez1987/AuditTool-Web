@if(!empty($photo))
    <div class="el-card-item">
        <div class="el-card-avatar el-overlay-1">
            <div class="el-overlay">
                <ul class="list-style-none el-info">
                    <li class="el-item bg-grey">
                        <a class="btn default btn-outline image-popup-vertical-fit el-link"
                           href="{{asset('storage/' . $photo)}}">
                            Ver
                            <i class="icon-magnifier"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@else
    Sin Evidencia
@endif