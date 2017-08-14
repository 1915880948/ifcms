@extends('layouts.main-login')

@section('title')用户登录@stop

@section('content')
    <div class="login-logo">
        <a href="javascript:;"><b>IF</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">后台管理系统</p>
        <form action="/site/login" method="post">
            <div class="form-group has-feedback">
                <input type="text" name="username" class="form-control" placeholder="用户名">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="密码">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck"></div>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">登录</button>
                </div>
            </div>
        </form>
    </div>
@stop

@section('foot-script')
    <script src="/js/helper.js"></script>
    <script>
        $(function () {
            $('button[type="submit"]').click(function (e) {
                e.preventDefault();
                var username = $('input[name="username"]').val(),
                    password = $('input[name="password"]').val();
                if(!username || !password){
                    layer.msg('输入不能为空', {time: 1200});
                } else {
                    CHelper.asynRequest('/admin/login', {
                        username: username,
                        password: password
                    }, {
                        success: function (response) {
                            layer.msg('登录成功', {time: 1200}, function () {
                                location.href = '/product/list';
                            });
                        },
                        failure: function () {
                            layer.msg('用户名或密码错误');
                        },
                        error: function () {
                            layer.msg('系统异常');
                        }
                    });
                }
            });
        });
    </script>
@stop
