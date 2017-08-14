@extends('layouts.main')

@section('title')用户编辑@stop

@section('head-css')
@stop

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">用户编辑</h3>
        </div>
        <form class="form-horizontal" id="editForm">
            <input type="hidden" name="id" value="{{ $data['id'] }}" id="id">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">用户名</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="nickname" value="{{ $data['nickname'] }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">头像</label>
                    <div class="col-sm-4" id="container">
                        <img src="{{ $data['portrait'] ? : '/image/boxed-bg.jpg' }}" alt="" id="upload" style="width: 200px;">
                        <input type="hidden" name="portrait" value="{{ $data['portrait'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">性别</label>
                    <div class="col-sm-4">
                        <select name="display_status" class="form-control select2" style="width: 100%">
                            <option value="1"{{ ($data['gender'] == 1)? "":"selected" }}  >男</option>
                            <option value="0" {{ ($data['gender'] == 0)? "":"selected" }} >女</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">手机号</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="phone" value="{{ $data['phone'] }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">邮箱</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="email" value="{{ $data['email'] }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">公司</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="company" value="{{ $data['company'] }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">省</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="province" value="{{ $data['province'] }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">城市区代码</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="city" value="{{ $data['city'] }}" required>
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
    <script src="/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="/js/qiniu4js.min.js"></script>
    <script>
        $(function(){
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
                        $('input[name="portrait"]').val('http://img-cdn.suixiangpin.com/' + task.result.key);
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
                    CHelper.asynRequest('/user/edit', $('#editForm').serialize(), {
                        success: function () {
                            layer.msg('提交成功', {time: 1200}, function () {
                                location.href = '/user/list';
                            })
                        }
                    })
                } else {
                    CHelper.asynRequest('/user/create', $('#editForm').serialize(), {
                        success: function () {
                            layer.msg('提交成功', {time: 1200}, function () {
                                location.href = '/user/list';
                            })
                        }
                    })
                }

            })
        });
    </script>
@stop