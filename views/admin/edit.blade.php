@extends('layouts.main')

@section('title')管理员编辑@stop

@section('head-css')
@stop

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">管理员编辑</h3>
        </div>
        <form class="form-horizontal" id="editForm">
            <input type="hidden" name="id" value="{{ $data['id'] }}" id="id">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">用户名</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="username" value="{{ $data['username'] }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">密码</label>
                    <div class="col-sm-4">
                        <input type="password" class="form-control" name="password" value="{{ $data['password'] }}" required>
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
<script>
    $(function(){
        $('#editForm').submit(function (e) {
            e.preventDefault();
            if($('#id').val()){
                CHelper.asynRequest('/admin/edit', $('#editForm').serialize(), {
                    success: function () {
                        layer.msg('提交成功', {time: 1200}, function () {
                            location.href = '/admin/list';
                        })
                    }
                })
            } else {
                CHelper.asynRequest('/admin/create', $('#editForm').serialize(), {
                    success: function () {
                        layer.msg('提交成功', {time: 1200}, function () {
                            location.href = '/admin/list';
                        })
                    }
                })
            }

        })
    });
</script>
@stop