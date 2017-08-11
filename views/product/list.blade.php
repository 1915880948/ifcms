@extends('layouts.main')

@section('title')产品列表@stop

@section('head-css')
    <link rel="stylesheet" href="/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">产品列表</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="dataTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>序号</th>
                    <th>风格</th>
                    <th>标签</th>
                    <th>收藏数</th>
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
                    url: '/product/list'
                },
                'columns': [
                    {'data': 'number', 'sDefaultContent':''},
                    {'data': 'style_name_cn', 'sDefaultContent':''},
                    {'data': 'tag', 'sDefaultContent':''},
                    {'data': 'collect_count', 'sDefaultContent':''},
                    {'data': 'null', 'sDefaultContent':''}
                ],
                'columnDefs' : [
                    {
                        targets: 4,
                        render: function (a, b, c, d) {
                            var html = '<button type="button" class="btn btn-primary row-edit" data-id="'+c.id+'">编辑</button>&nbsp;' +
                                '<button type="button" class="btn btn-danger row-delete" data-id="'+c.id+'">删除</button>';
                            return html;
                        }
                    }
                ],
                'initComplete': function(a, b) {
                    $(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions"><a href="/product/create" class="btn btn-info waves-effect fright">添加</a></div>'),
                        $(".dataTables_filter input[type=search]").focus(function() {
                            $(this).closest(".dataTables_filter").addClass("dataTables_filter--toggled")
                        }),
                        $(".dataTables_filter input[type=search]").blur(function() {
                            $(this).closest(".dataTables_filter").removeClass("dataTables_filter--toggled")
                        })
                }
            });

            $('tbody').on('click', '.btn', function () {
                var _this = $(this);
                if(_this.hasClass('row-edit')){
                    location.href = '/product/edit/' + _this.attr('data-id');
                } else if(_this.hasClass('row-delete')){
                    layer.confirm('你确定要执行此操作吗?', {
                        btn: ['确定','取消'] //按钮
                    }, function(){
                        CHelper.asynRequest('/product/delete',{id: _this.attr('data-id')},{
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