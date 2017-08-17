@extends('layouts.main')

@section('title')管理员列表@stop

@section('head-css')
    <link rel="stylesheet" href="/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
@stop
@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">用户管理列表</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="dataTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>用户名</th>
                    <th>头像</th>
                    <th>性别</th>
                    <th>手机号</th>
                    <th>邮箱</th>
                    <th>公司</th>
                    <th>省</th>
                    <th>市</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
@stop

@section('foot-script')
    <script src="/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script>
        $(function(){
            var _table = $('#dataTable').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : false,
                'ordering'    : false,
                'info'        : true,
                "serverSide"  : true,
                "lengthMenu"  : [
                    [15, 30, 50],
                    ["15条", "30条", "50条"]
                ],
                "language"    : {
                    "searchPlaceholder": "Search for records..." ,
                    "info": "显示 _START_ 至 _END_ 总数 _TOTAL_ 条",
                    "infoEmpty": "",
                    "emptyTable": "暂无数据"
                },
                "ajax" : {
                    url: '/user/list'
                },
                'columns': [
                    {'data': 'nickname', 'sDefaultContent':''},
                    {'data': 'portrait', 'sDefaultContent':''},
                    {'data': 'gender', 'sDefaultContent':''},
                    {'data': 'phone', 'sDefaultContent':''},
                    {'data': 'email', 'sDefaultContent':''},
                    {'data': 'company', 'sDefaultContent':''},
                    {'data': 'province', 'sDefaultContent':''},
                    {'data': 'city', 'sDefaultContent':''},
                    {'data': 'create_time', 'sDefaultContent':''},
                    {'data': 'null', 'sDefaultContent':''}
                ],
                'columnDefs' : [
                    {
                        targets: 1,
                        render: function (a, b, c, d) {
                            if( c.portrait ){
                                var html = '<img src="'+c.portrait+'" style="width: 20px; height: 20px;"/>';
                                return html;
                            }
                            return ;
                        }
                    },
                    {
                        targets: 2,
                        render: function (a, b, c, d) {
                            if( c.gender == 1){
                                return '男';
                            }
                            return '女';
                        }
                    },
                    {
                        targets: 9,
                        render: function (a, b, c, d) {
                            var html = '<button type="button" class="btn btn-primary row-edit" data-id="'+c.id+'">编辑</button>&nbsp;' +
                                '<button type="button" class="btn btn-danger row-delete" data-id="'+c.id+'">删除</button>';
                            return html;
                        }
                    },
                ],
                'initComplete': function(a, b) {
                }
            });

            $('tbody').on('click', '.btn', function () {
                var _this = $(this);
                if(_this.hasClass('row-edit')){
                    location.href = '/user/edit/' + _this.attr('data-id');
                } else if(_this.hasClass('row-delete')){
                    layer.confirm('你确定要执行此操作吗?', {
                        btn: ['确定','取消'] //按钮
                    }, function(){
                        CHelper.asynRequest('/user/delete',{id: _this.attr('data-id')},{
                            success: function () {
                                layer.msg('删除成功', {time: 1200}, function(){
                                    _table.page(_table.page()).draw(false);
                                });
                            }
                        })
                    }, function(){

                    });
                }
            })
        });
    </script>
@stop