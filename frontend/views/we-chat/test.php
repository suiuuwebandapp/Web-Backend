<script src="/assets/other/weixin/nouislider/jquery.nouislider.min.js"></script>
<script src="/assets/other/weixin/nouislider/nouislider.min.js"></script>
<link rel="stylesheet" href="/assets/other/weixin/nouislider/nouislider.css">
<div id="v1">0</div>
<div id="v2">1</div>
<div id="slider" style="width: 400px;" >
</div>
<script>
    var slider = document.getElementById('slider');

    noUiSlider.create(slider, {
        start: [20, 80],
        connect: true,
        range: {
            'min': 0,
            'max': 100
        }
    });
    var valueInput = document.getElementById('v1'),
        valueSpan = document.getElementById('v2');

    // When the slider value changes, update the input and span
    slider.noUiSlider.on('update', function( values, handle ) {
        if ( handle ) {
            valueInput.innerHTML = values[handle];
        } else {
            valueSpan.innerHTML = values[handle];
        }
    });

    // When the input changes, set the slider value
    valueInput.addEventListener('change', function(){
        slider.noUiSlider.set([null, this.value]);
    });
</script>