@can('storage-category-safeties.edit')
    <a href="{{url('storage-category-safeties/' . $id. '/edit')}}">
        @include('buttons.edit')
    </a>
@endcan
    

@can('storage-category-safeties.show')
    <a href="{{url('storage-category-safeties/' . $id)}}">
        <img src="{{asset('/ico/storagepanel.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
