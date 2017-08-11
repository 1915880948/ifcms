@extends('layouts.main')

@section('title')意见列表@stop

@section('head-css')
    <link rel="stylesheet" href="/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">询问列表</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="dataTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>用户名</th>
                    <th>联系方式</th>
                    <th>标题</th>
                    <th>内容</th>
                    <th>提交时间</th>
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
                    [10, 30, 50],
                    ["10条", "30条", "50条"]
                ],
                "language"    : {
                    "searchPlaceholder": "Search for records..." ,
                    "info": "显示 _START_ 至 _END_ 总数 _TOTAL_ 条",
                    "infoEmpty": "",
                    "emptyTable": "暂无数据",
                    "lengthMenu": "每页显示 _MENU_ 条记录"
                },
                "ajax" : {
                    url: '/contact/list'
                },
                'columns': [
                    {'data': 'nickname', 'sDefaultContent':''},
                    {'data': 'phone', 'sDefaultContent':''},
                    {'data': 'title', 'sDefaultContent':''},
                    {'data': 'message', 'sDefaultContent':''},
                    {'data': 'create_time', 'sDefaultContent':''},
                    {'data': 'null', 'sDefaultContent':''}
                ],
                'columnDefs' : [
                    {
                        targets: 5,
                        render: function (a, b, c, d) {
                            var html = '<button type="button" class="btn btn-danger row-delete" data-id="'+c.id+'">删除</button>';
                            return html;
                        }
                    }
                ],
                'initComplete': function(a, b) {

                }
            });

            $('tbody').on('click', '.btn', function () {
                var _this = $(this);
                if(_this.hasClass('row-delete')){
                    layer.confirm('你确定要执行此操作吗?', {
                        btn: ['确定','取消'] //按钮
                    }, function(){
                        CHelper.asynRequest('/contact/delete',{id: _this.attr('data-id')},{
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