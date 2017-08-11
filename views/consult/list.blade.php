@extends('layouts.main')

@section('title')咨询列表@stop

@section('head-css')
    <link rel="stylesheet" href="/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">咨询列表</h3>
        </div>
        <div class="box-body">
            <table id="dataTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>用户名</th>
                    <th>联系方式</th>
                    <th>产品序号</th>
                    <th>问题</th>
                    <th>回答</th>
                    <th>提交时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="modal-default" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left">添加回答</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea class="form-control" name="content" id="content" rows="5"></textarea>
                        <i class="form-group__bar"></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-success waves-effect" id="submit">提交</button>
                    <button type="button" class="btn btn-sm btn-danger waves-effect" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
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
                'searching'   : true,
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
                    url: '/consult/list'
                },
                'columns': [
                    {'data': 'nickname', 'sDefaultContent':''},
                    {'data': 'phone', 'sDefaultContent':''},
                    {'data': 'number', 'sDefaultContent':''},
                    {'data': 'question', 'sDefaultContent':''},
                    {'data': 'answer', 'sDefaultContent':''},
                    {'data': 'create_time', 'sDefaultContent':''},
                    {'data': 'null', 'sDefaultContent':''}
                ],
                'columnDefs' : [
                    {
                        targets: 6,
                        render: function (a, b, c, d) {
                            var html = '<button type="button" class="btn btn-sm btn-info row-answer" data-id="'+c.id+'">回答</button>&nbsp;' +
                                '<button type="button" class="btn btn-sm btn-danger row-delete" data-id="'+c.id+'">删除</button>';
                            return html;
                        }
                    }
                ],
                'initComplete': function(a, b) {

                }
            });

            $('tbody').on('click', '.btn', function () {
                var _this = $(this);
                if(_this.hasClass('row-answer')){
                    var id = _this.attr('data-id');
                    $('#modal-default').modal();
                    $('#submit').on('click', function () {
                        CHelper.asynRequest('/consult/answer',{id: id, content: $('#modal-default').find('#content').val()},{
                            success: function () {
                                layer.msg('操作成功', {time: 1200}, function () {
                                    $('#modal-default').modal('hide');
                                    _table.page(_table.page()).draw(false);
                                });
                            }
                        })
                    })
                } else if(_this.hasClass('row-delete')){
                    layer.confirm('你确定要执行此操作吗?', {
                        btn: ['确定','取消'] //按钮
                    }, function(){
                        CHelper.asynRequest('/consult/delete',{id: _this.attr('data-id')},{
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