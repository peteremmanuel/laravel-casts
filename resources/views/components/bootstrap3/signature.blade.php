@if($isHorizontal)
    <div class="col-sm-offset-{{ $labelWidth }} col-sm-{{ $fieldWidth }}">
@endif

    <div class="signature {{ $name }}">
        <div class="embed-responsive embed-responsive-16by9 form-control">
            <canvas class="embed-responsive-item"></canvas>
            <div class="footer">
                <small>
                    <em>{{ $options['label'] }}</em>
                    &nbsp;

                    @if(! $errors->isEmpty() && $errors->has($name))
                        <span class="help-block" style="display: inline-block;">{{ implode(' ', $errors->get($name)) }}</span>
                    @endif
                </small>
                <button type="button" class="btn btn-default btn-xs pull-right" onclick="clearSignature('{{ $name }}');">&nbsp;{{ $options['clearButton'] }}&nbsp;</button>
            </div>
            {!! $controlHtml !!}
        </div>
    </div>

    @if(! $errors->isEmpty() && ! $errors->has($name))
        <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
        <span id="inputSuccess2Status" class="sr-only">(success)</span>
    @endif

    @if(! $errors->isEmpty() && $errors->has($name))
        <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
        <span id="inputError2Status" class="sr-only">(error)</span>
    @endif

    <script>
        window['genealabsLaravelCasts'] = window.genealabsLaravelCasts || {};
        window.genealabsLaravelCasts['signatureLoaders'] = window.genealabsLaravelCasts.signatureLoaders || [];
        window.genealabsLaravelCasts.signatureLoaders.push(function () {
            var canvas = document.querySelector('.signature.{{ $name }} canvas');

            if ((window.SignaturePad !== undefined) && (canvas !== null)) {
                window['signaturePad{{ $name }}'] = new window.SignaturePad(canvas);

                if (window.setImageData === undefined) {
                    window.setImageData = function (name) {
                        var imageData = window['signaturePad' + name].toDataURL();
                        var timestamp = '';

                        if (imageData.length > 0) {
                            timestamp = new Date().getTime();
                        }

                        $('input[type=hidden][name=' + name + ']').val(imageData);
                        $('input[type=hidden][name=' + name + '_date]').val(timestamp);
                    };
                }

                if (window.resizeCanvas === undefined) {
                    window.resizeCanvas = function (name) {
                        var canvas = document.querySelector('.signature.' + name + ' canvas');
                        var ratio =  Math.max(window.devicePixelRatio || 1, 1);

                        canvas.width = canvas.offsetWidth * ratio;
                        canvas.height = canvas.offsetHeight * ratio;
                        canvas.getContext("2d").scale(ratio, ratio);
                        window['signaturePad' + name].clear(); // otherwise isEmpty() might return incorrect value
                    };
                }

                if (window.clearSignature === undefined) {
                    window.clearSignature = function (name) {
                        window['signaturePad' + name].clear();
                        $('input[type=hidden][name=' + name + ']').val('');
                        $('input[type=hidden][name=' + name + '_date]').val('');
                    };
                }

                window['signaturePad{{ $name }}'].onEnd = function () {
                    window.setImageData('{{ $name }}');
                };

                window.addEventListener("resize", function () {
                    window.resizeCanvas('{{ $name }}');
                });
                window.resizeCanvas('{{ $name }}');
                window['signaturePad{{ $name }}'].fromDataURL($('input[type=hidden][name={{ $name }}]').val());
            }
        });
    </script>

@if($isHorizontal)
    </div>
@endif
