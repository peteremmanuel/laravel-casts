@if($isHorizontal)
<div class="col-sm-{{ $fieldWidth }}">
@endif

    {!! $controlHtml !!}

    @if(! $errors->isEmpty() && $errors->has($name))
        <small class="form-control-feedback">{{ implode(' ', $errors->get($name)) }}</small>
    @endif

@if($isHorizontal)
</div>
@endif
