<h5>{!! $title !!}</h5>
<p><a href="{{ url('blog/'.$seotitle) }}" target="blank"><span class="badge badge-light">{{ url('blog/'.$seotitle) }}</span></a></p>

<div class="btn-group btn-group-sm" role="group" aria-label="">
    <button type="button" class="btn btn-secondary">
        <i class=" ti-user"></i> {!! $author !!}</button>
    <button type="button" class="btn btn-secondary">
        <i class="ti-calendar"></i> {!! $created !!}</button>
    <button type="button" class="btn btn-secondary">
        <i class="ti-eye"></i> {!! $hits !!}</button>
    <button type="button" class="btn btn-secondary">
        <i class="ti-folder"></i> {!! $category !!}</button>
</div>