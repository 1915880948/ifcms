@extends('layouts.main')

@section('title')产品编辑@stop

@section('head-css')
    <link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="/bower_components/wangEditor/wangEditor.min.css">
@stop

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">产品编辑</h3>
        </div>
        <form class="form-horizontal" id="editForm">
            <input type="hidden" name="id" value="{{ $data['id'] }}" id="id">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">序号</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="number" value="{{ $data['number'] }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">封面图片</label>
                    <div class="col-sm-4" id="container">
                        <img src="{{ $data['picture'] ? : '/image/boxed-bg.jpg' }}" alt="" id="upload" style="width: 200px;">
                        <input type="hidden" name="picture" value="{{ $data['picture'] }}">
                        <input type="hidden" name="picture_height" value="{{ $data['picture_height'] }}">
                        <input type="hidden" name="picture_width" value="{{ $data['picture_width'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">风格</label>
                    <div class="col-sm-4">
                        <select name="style_id" class="form-control select2" style="width: 100%">
                            @foreach($style as $list)
                                <option value="{{ $list['id'] }}" {{ $list['id'] == $data['style_id'] ? 'selected' : '' }}>{{ $list['name_cn'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">系列</label>
                    <div class="col-sm-4">
                        <select name="series[]" class="form-control select2" style="width: 100%" multiple>
                            @foreach($series as $list)
                                <option value="{{ $list['id'] }}" {{ in_array($list['id'], \app\common\ArrayHelper::stringToArray($data['series'])) ? 'selected' : '' }}>{{ $list['name_cn'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">标签</label>
                    <div class="col-sm-4">
                        <select name="tag[]" class="form-control" style="width: 100%" multiple>
                            @foreach(\app\common\ArrayHelper::stringToArray($data['tag']) as $list)
                                <option value="{{ $list }}" selected>{{ $list }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">富文本详情</label>
                    <div class="col-sm-10">
                        <div id="editor"><?= $data['detail'] ?></div>
                        <input type="hidden" name="detail" value="{{ $data['detail'] }}">
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">提交</button>
            </div>
        </form>
    </div>
@stop

@section('foot-script')
    <script src="/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script type="text/javascript" src="/bower_components/wangEditor/wangEditor.min.js"></script>
    <script src="/js/qiniu4js.min.js"></script>
<script>
    $(function(){
        $('.select2').select2();
        $('select[name="tag[]"]').select2({tags: true});

        var E = window.wangEditor;
        var editor = new E('#editor');
        // 隐藏“网络图片”tab
        editor.customConfig.showLinkImg = false;
        editor.customConfig.uploadImgServer = '/file/upload';
        // 将图片大小限制为 1M
        editor.customConfig.uploadImgMaxSize = 1024 * 1024;
        editor.customConfig.uploadFileName = 'file';
        editor.customConfig.uploadImgHooks = {
            // 如果服务器端返回的不是 {errno:0, data: [...]} 这种格式，可使用该配置
            // （但是，服务器端返回的必须是一个 JSON 格式字符串！！！否则会报错）
            customInsert: function (insertImg, result, editor) {
                // 图片上传并返回结果，自定义插入图片的事件（而不是编辑器自动插入图片！！！）
                // insertImg 是插入图片的函数，editor 是编辑器对象，result 是服务器端返回的结果

                // 举例：假如上传图片成功后，服务器端返回的是 {url:'....'} 这种格式，即可这样插入图片：
                var url = result.data;
                insertImg(url)

                // result 必须是一个 JSON 格式字符串！！！否则报错
            }
        };
        editor.create();

        //构建uploader实例
        var qiniu = new Qiniu.UploaderBuilder()
            .debug(false)
            .tokenShare(true)
            .chunk(true)
            .multiple(false)
            //            .accept(['image/*'])
            .tokenUrl('/file/token')
            .listener({
                onTaskSuccess: function (task) {
                    layer.msg('上传成功', {time: 1200});
                    $('input[name="picture"]').val('http://img-cdn.suixiangpin.com/' + task.result.key);
                    $('input[name="picture_height"]').val(task.result.h);
                    $('input[name="picture_width"]').val(task.result.w);
                    $('#upload').attr('src', 'http://img-cdn.suixiangpin.com/' + task.result.key);
                }, onTaskFail: function (task) {
                    layer.msg('上传失败', {time: 1200});
                }
            }).build();

        $('#upload').click(function () {
            qiniu.chooseFile();
        });

        $('#editForm').submit(function (e) {
            e.preventDefault();
            $('input[name="detail"]').val(editor.txt.html());
            if($('#id').val()){
                CHelper.asynRequest('/product/edit', $('#editForm').serialize(), {
                    success: function () {
                        layer.msg('提交成功', {time: 1200}, function () {
                            location.href = '/product/list';
                        })
                    }
                })
            } else {
                CHelper.asynRequest('/product/create', $('#editForm').serialize(), {
                    success: function () {
                        layer.msg('提交成功', {time: 1200}, function () {
                            location.href = '/product/list';
                        })
                    }
                })
            }

        })
    });
</script>
@stop