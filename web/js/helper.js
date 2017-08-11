/**
 * Created by jun on 2017/5/4.
 */
(function(exports){
    Date.prototype.Format = function(fmt){
        var o = {
            "M+": this.getMonth() + 1, //月份
            "d+": this.getDate(), //日
            "h+": this.getHours(), //小时
            "m+": this.getMinutes(), //分
            "s+": this.getSeconds(), //秒
            "q+": Math.floor((this.getMonth() + 3) / 3), //季度
            "S": this.getMilliseconds() //毫秒
        };
        if(/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
        for(var k in o)
            if(new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    };

    CHelper = {
        //七牛上传
        uploadFile: function(button, callback){
            var callback = callback || {};
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',      // 上传模式，依次退化
                browse_button: button,         // 上传选择的点选按钮，必需
                // 在初始化时，uptoken，uptoken_url，uptoken_func三个参数中必须有一个被设置
                // 切如果提供了多个，其优先级为uptoken > uptoken_url > uptoken_func
                // 其中uptoken是直接提供上传凭证，uptoken_url是提供了获取上传凭证的地址，如果需要定制获取uptoken的过程则可以设置uptoken_func
                // uptoken : 'sChufDBj7K6x_MtkvhrKCj1HjGZ4EEgD7A0VMcsM:5M3pGki9di809gaoUkv0PMP_qLo=:eyJzY29wZSI6ImxlZ2VuZHMiLCJkZWFkbGluZSI6MTQ4NDI4MDk2NCwidXBIb3N0cyI6WyJodHRwOlwvXC91cC5xaW5pdS5jb20iLCJodHRwOlwvXC91cGxvYWQucWluaXUuY29tIiwiLUggdXAucWluaXUuY29tIGh0dHA6XC9cLzE4My4xMzYuMTM5LjE2Il19', // uptoken是上传凭证，由其他程序生成
                uptoken_url: '/file/token',         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
                // uptoken_func: function(file){    // 在需要获取uptoken时，该方法会被调用
                //    // do something
                //    return uptoken;
                // },
                get_new_uptoken: true,             // 设置上传文件的时候是否每次都重新获取新的uptoken
                // downtoken_url: '/downtoken',
                // Ajax请求downToken的Url，私有空间时使用，JS-SDK将向该地址POST文件的key和domain，服务端返回的JSON必须包含url字段，url值为该文件的下载地址
                unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
                // save_key: true,                  // 默认false。若在服务端生成uptoken的上传策略中指定了sava_key，则开启，SDK在前端将不对key进行任何处理
                domain: 'http://img-cdn.suixiangpin.com',     // bucket域名，下载资源时用到，必需
                container: 'container',             // 上传区域DOM ID，默认是browser_button的父元素
                max_file_size: '10mb',             // 最大文件体积限制
                flash_swf_url: '',  //引入flash，相对路径
                max_retries: 3,                     // 上传失败最大重试次数
                dragdrop: true,                     // 开启可拖曳上传
                drop_element: 'container',          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
                // chunk_size: '4mb',                  // 分块上传时，每块的体积
                auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
                multi_selection: false,             //设置一次只能选择一个文件
                filters : {
                    max_file_size : '3mb',
                    prevent_duplicates: true,
                    // Specify what files to browse for
                    mime_types: [
                        {title : "Video files", extensions : "flv,mpg,mpeg,avi,wmv,mov,asf,rm,rmvb,mkv,m4v,mp4"}, // 限定flv,mpg,mpeg,avi,wmv,mov,asf,rm,rmvb,mkv,m4v,mp4后缀格式上传
                        {title : "Image files", extensions : "jpg,gif,png"}, // 限定jpg,gif,png后缀上传
                    ]
                },
                //x_vars : {
                //    查看自定义变量
                //    'time' : function(up,file) {
                //        var time = (new Date()).getTime();
                // do something with 'time'
                //        return time;
                //    },
                //    'size' : function(up,file) {
                //        var size = file.size;
                // do something with 'size'
                //        return size;
                //    }
                //},
                init: {
                    'FilesAdded': function(up, files) {
                        plupload.each(files, function(file) {
                            // 文件添加进队列后，处理相关的事情
                        });
                        // $('#md-3d-sign').addClass('md-show');
                    },
                    'BeforeUpload': function(up, file) {
                        // 每个文件上传前，处理相关的事情
                    },
                    'UploadProgress': function(up, file) {
                        // 每个文件上传时，处理相关的事情
                        if(typeof callback.UploadProgress == 'function') {
                            callback.UploadProgress(up, file);
                        }
                    },
                    'FileUploaded': function(up, file, info) {
                        // 每个文件上传成功后，处理相关的事情
                        // 其中info是文件上传成功后，服务端返回的json，形式如：
                        // {
                        //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                        //    "key": "gogopher.jpg"
                        //  }
                        // 查看简单反馈
                        if(typeof callback.FileUploaded == 'function'){
                            callback.FileUploaded(up,file, info);
                        }
                    },
                    'Error': function(up, err, errTip) {
                        //上传出错时，处理相关的事情

                    },
                    'UploadComplete': function() {
                        //队列文件处理完毕后，处理相关的事情
                        uploader.splice(0,1);
                    },
                    'Key': function(up, file) {
                        // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                        // 该配置必须要在unique_names: false，save_key: false时才生效

                        var key = "";
                        // do something with key here
                        return key;
                    }
                }
            });

        },

        //判断功能键(未包含Mac键盘上的功能键、大写键、win键、打印翻页键、小键盘开关键等)，排除: 退格8、回车13、Delete46
        isFunctionKey: function(keyCode){
            switch(keyCode){
                case 9: //Tab
                case 16: case 17: case 18: //Shift、Ctrl、Alt
                case 20: case 27: //大小写切换、esc
                case 37: case 38: case 39: case 40: //左上右下
                case 33: case 34: case 35: case 36: case 45: //PageUp、PageDown、End、Home、Insert
                case 44: case 145: case 19: case 144: //PrintSc、Scroll Lock、Pause Break、NumLock
                case 91: case 92: //左右win键
                return true; break;
                default:
                    if(keyCode >= 112 && keyCode <= 123) return true; //F1-F12
                    return false;
            }
        },

        dataFormat: function(timestamp, format){
            return (new Date(timestamp)).Format(format);
        },
        //判断是否为对象
        isObject: function(v){
            return typeof v === 'object';
        },
        caches: {},
        doing: {},
        asynRequest: function(url, data, callback, useCache, notMultiple, key){
            notMultiple = notMultiple === false? false : true;
            callback = callback || {};
            key = key || url;
            if(this.doing[key] === true && notMultiple) return;

            //调用回调函数中的初始化函数
            if(typeof callback.init == 'function'){
                callback.init();
            }

            //缓存处理
            if(useCache && this.caches[url]){
                return this.caches[url];
            }else if(this.caches[url]){
                delete this.caches[url];
            }

            this.doing[key] = true;

            if(CHelper.isObject(data)){
                data._csrf = $('meta[name="csrf-token"]').attr('content');
            } else {
                data = data + '&_csrf=' + $('meta[name="csrf-token"]').attr('content');
            }

            $.ajax(url, {
                type: 'POST', dataType: 'json', cache: false, data: data || {},
                beforeSend: function(xhr){
                    if(typeof callback.before == 'function'){
                        callback.before(xhr);
                    }
                },
                success: function(response, status){
                    if(typeof callback.success == 'function'){
                        callback.success(status);
                    }
                },
                complete: function(xhr, status){
                    delete CHelper.doing[key];
                    if(typeof callback.complete == 'function'){
                        callback.complete(status);
                    }
                },
                error: function(xhr, msg, eThrow){
                    if(typeof callback.error == 'function'){
                        callback.error(msg);
                    }
                }
            });
        }
    };

})(window);

(function (token) {
  $('.content').on('change', 'input[type="file"]', function () {
      var file = this.files[0];
      var self = $(this);
      var Qiniu_UploadUrl = "http://up.qiniu.com";
      if(file && file.size > 20*1024*1024){
          alert('上传文件过大');
          return false;
      }
      if(file){
          var reader=new FileReader();
          reader.onload = function(){
              // 通过 reader.result 来访问生成的 base64 DataURL
              base64 = reader.result;
              var bytes = window.atob(base64.split(',')[1]);        //去掉url的头，并转换为byte
              //处理异常,将ascii码小于0的转换为大于0
              var ab = new ArrayBuffer(bytes.length);
              var ia = new Uint8Array(ab);
              for (var i = 0; i < bytes.length; i++) {
                  ia[i] = bytes.charCodeAt(i);
              }
              var blob = new Blob( [ab] , {type : ''});
              var fd = new FormData();
              var key = $('#uniqid').val() + (new Date()).getTime().toString().substr(-5);
              fd.append('file', blob, 'image.png');
              fd.append('token', $('#uptoken').val());
              fd.append('key', key);

              var xhr = new XMLHttpRequest();
              xhr.open('POST', Qiniu_UploadUrl, true);
              xhr.onload = function() {
                  if (xhr.status === 200) {
                      var info = JSON.parse(xhr.responseText);
                      self.parents('.row').find('#picture').val('http://img-cdn.suixiangpin.com/'+info.key);
                      self.parents('.row').find('#key').val(info.key);
                      self.parents('.row').find('#infoHeight').val(info.h);
                      self.parents('.row').find('#infoWidth').val(info.w);
                  }
              };
              xhr.send(fd);
          }
          reader.readAsDataURL(file);
      }
  })
})();