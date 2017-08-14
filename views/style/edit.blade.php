@extends('layouts.main')

@section('title')风格编辑@stop

@section('head-css')
    <link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="/bower_components/wangEditor/wangEditor.min.css">
@stop

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">风格编辑</h3>
        </div>
        <form class="form-horizontal" id="editForm">
            <input type="hidden" name="id" value="{{ $data['id'] }}" id="id">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">封面图片</label>
                    <div class="col-sm-4" id="container">
                        <img src="{{ $data['picture'] ? : '/image/boxed-bg.jpg' }}" alt="" id="upload" style="width: 200px;">
                        <input type="hidden" name="picture" value="{{ $data['picture'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">英文名</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="name_en" value="{{ $data['name_en'] }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">中文名</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="name_cn" value="{{ $data['name_cn'] }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">排序值</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="sort" value="{{ $data['sort'] }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">是否显示在热词</label>
                    <div class="col-sm-4">
                        <select name="display_status" class="form-control select2" style="width: 100%">
                            <option value="1"{{ ($data['display_status'] == 1)? "":"selected" }}  >是</option>
                            <option value="0" {{ ($data['display_status'] == 1)? "":"selected" }} >否</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">是否上架</label>
                    <div class="col-sm-4">
                        <select name="shelf_status" class="form-control select2" style="width: 100%">
                            <option value="0" {{ ($data['shelf_status'] == 1)? "":"selected" }} >否</option>
                            <option value="1"  {{ ($data['shelf_status'] == 1)? "selected":"" }}>是</option>
                        </select>
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
    <script src="/js/qiniu4js.min.js"></script>
    <script>
        $(function(){
            $('.select2').select2();
            $('select[name="tag[]"]').select2({tags: true});

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
                if($('#id').val()){
                    CHelper.asynRequest('/style/edit', $('#editForm').serialize(), {
                        success: function () {
                            layer.msg('提交成功', {time: 1200}, function () {
                                location.href = '/style/list';
                            })
                        }
                    })
                } else {
                    CHelper.asynRequest('/style/create', $('#editForm').serialize(), {
                        success: function () {
                            layer.msg('提交成功', {time: 1200}, function () {
                                location.href = '/style/list';
                            })
                        }
                    })
                }

            })
        });
    </script>
@stop