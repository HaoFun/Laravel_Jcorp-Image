@extends('layouts.admin')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading"><h4 style="font-weight: bold">圖檔裁切  (如裁切完網頁中圖片沒有變動，請案CTRL + F5刷新頁面)</h4></div>
        <div class="panel-body">
            <div style="margin-top: 10px" class="col-md-12">
                <div class="col-md-8">
                    @if(!empty($_GET['image']))
                        <img src="{{ $_GET['image'] }}" width="{{ Image::make(mb_substr($_GET['image'],1))->width() }}"  name="image" id="image" >
                    @endif
                </div>
                <div id="preview-pane" class="show">
                    <div class="preview-container">
                        <img src="{{ $_GET['image'] }}" class="jcrop-preview" alt="Preview" />
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-top: 10px">
                <form action="{{ route('image.update') }}" method="post" onsubmit="return checkCoords();">
                    {!! csrf_field() !!}
                    <input type="hidden" id="x" name="x" />
                    <input type="hidden" id="y" name="y" />
                    <input type="hidden" id="w" name="w" />
                    <input type="hidden" id="h" name="h" />
                    <input type="hidden" id="height" name="height">
                    <input type="hidden" id="width" name="width">
                    <input name="image_update" id="image_update" type="hidden" value="@if(!empty($_GET['image'])) {{ $_GET['image'] }} @endif">
                    <input type="submit" class="btn btn-primary form-control" value="確　認" />
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ mix('js/jcrop.js') }}"></script>
    <script>
        var jcrop_api,
            boundx,
            boundy,
            // Grab some information about the preview pane
            $preview = $('#preview-pane'),
            $pcnt = $('#preview-pane .preview-container'),
            $pimg = $('#preview-pane .preview-container img'),
            xsize = $pcnt.width(),
            ysize = $pcnt.height();
        $(document).ready(function()
        {
            $('#image').Jcrop({
                bgFade:     true,
                bgOpacity: 0.09,
                minSize:['200','200'],
                onSelect: updateCoords,
                onChange: updateCoords,
                setSelect: [200,200,10,10],
                aspectRatio: xsize/ysize,
            },function() {
                var bounds = this.getBounds();
                boundx = bounds[0];
                boundy = bounds[1];
                jcrop_api = this;
                $preview.appendTo(jcrop_api.ui.holder);
            });
        });
        function updateCoords(c)
        {
            $('#x').val(c.x);
            $('#y').val(c.y);
            $('#w').val(c.w);
            $('#h').val(c.h);
            $('#height').val(ysize);
            $('#width').val(xsize);
            if (parseInt(c.w) > 0)
            {
                var rx = xsize / c.w;
                var ry = ysize / c.h;
                $pimg.css({
                    width: Math.round(rx * boundx) + 'px',
                    height: Math.round(ry * boundy) + 'px',
                    marginLeft: '-' + Math.round(rx * c.x) + 'px',
                    marginTop: '-' + Math.round(ry * c.y) + 'px'
                });
            }
        };
        function checkCoords()
        {
            if (parseInt($('#w').val())>0) return true;
            swal({
                title: "請選擇要裁切的範圍",
                type: "error",
                timer: 1500,
                confirmButtonColor: "#2ac169",
                confirmButtonText: "確　認",
                closeOnConfirm: false
            });
            return false;
        };
        function changeurl()
        {
            window.location = 'http://localhost:8000';
        }
    </script>
@endsection


