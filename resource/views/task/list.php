<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <style type="text/css">
    /*.layui-table-view .layui-table[lay-size=lg] .layui-table-cell{height: auto !important;}*/
  </style>
  <?= $this->include('layouts/common/header', ['title' => '任务管理']) ?>
  <link rel="stylesheet" href="/lib/jsoneditor/normalize.css">
</head>
<body>
<table class="layui-hide" id="table" lay-filter="table"></table>
</body>


<script type="text/html" id="operation">
  <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">取消</a>
</script>

<script type="text/html" id="panel">
  <form class="layui-form layui-form-pane" action="" lay-filter="panel-form">
    <div class="layui-form-item">
      <label class="layui-form-label">任务ID:</label>
      <div class="layui-input-block">
        <input type="text" name="taskId" autocomplete="off" placeholder="请输入任务ID" class="layui-input">
      </div>
    </div>

    <div class="layui-form-item">
      <div class="layui-inline">
        <label class="layui-form-label">任务名称:</label>
        <div class="layui-input-block">
          <input type="text" name="names" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-inline">
        <label class="layui-form-label">任务状态:</label>
        <div class="layui-input-inline">
          <input type="text" name="status" autocomplete="off" class="layui-input">
        </div>
      </div>
    </div>

    <div class="layui-form-item">
      <div class="layui-inline">
        <label class="layui-form-label">执行时间:</label>
        <div class="layui-input-block">
          <input type="text" name="execution" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-inline">
        <label class="layui-form-label">重试次数:</label>
        <div class="layui-input-inline">
          <input type="text" name="retry" autocomplete="off" class="layui-input">
        </div>
      </div>
    </div>

    <div class="layui-form-item layui-form-text">
      <label class="layui-form-label">任务描述:</label>
      <div class="layui-input-block">
        <textarea placeholder="请输入内容" class="layui-textarea" name="describe"></textarea>
      </div>
    </div>

    <div class="layui-form-item layui-form-text">
      <label class="layui-form-label">任务配置:(说明文档)</label>
      <div class="layui-input-block" style="width: auto">
        <div id="jsoneditor" style="width: 50%;height: 400px;float: left"></div>
        <div id="jsondesc" style="width: 50%;height: 400px; float: left">
          <h3 style="text-align: center">全部请求数据参数</h3>

          <pre class="layui-code" lay-skin="notepad" lay-encode="true">
url =&gt; &#39;https://localhost/test/redis&#39;,// string
method =&gt; &#39;请求方式&#39;,// string
connect_timeout =&gt; &#39;表示等待服务器响应超时的最大值&#39;, // float 0
verify =&gt; &#39;请求时验证SSL证书行为&#39;, // boole
cookies =&gt; &#39;cookie 数据’, // string
body =&gt; &#39;body 选项用来控制一个请求(比如：PUT, POST, PATCH)的主体部分。&#39;,
headers =&gt; &#39;要添加到请求的报文头的关联数组，每个键名是header的名称，每个键值是一个字符串或包含代表头字段字符串的数组。&#39;, // array
form_params =&gt; &#39;用来发送一个 application/x-www-form-urlencoded POST请求.&#39;,// array
timeout =&gt; &#39;请求超时的秒数。使用 0 无限期的等待(默认行为)&#39;,// float
version =&gt; &#39;请求要使用到的协议版本&#39;,// string, float
          </pre>

          <p>上述 大部分 参数 都是为了 更加满足使用 </p>

          <blockquote><p>注意： <b>timeout</b> 默认 为 1 既 等待 1秒 如果 1秒无法 执行完毕您的程序 必须 设定 禁止为空</p></blockquote>
        </div>
      </div>
    </div>

  </form>
</script>

<script type="module">
  import {getCookie} from '/service/js/util.js';
  import {task_delete} from '/service/js/api.js';
  import {postRequest} from '/service/js/request.js';

  layui.use(['table', 'code', 'util', 'form', 'jquery'], function () {
    let $ = layui.$;
    var table = layui.table;
    var util = layui.util;
    var form = layui.form;
    var jQuery = layui.jquery;

    table.render({
      elem: '#table'
      , url: '/task/list'
      , parseData: function (res) { //res 即为原始返回的数据
        return {
          "code": res.code, //解析接口状态
          "msg": res.message, //解析提示文本
          "count": res.data.total, //解析数据长度
          "data": res.data.data //解析数据列表
        };
      }
      , size: 'lg'
      , even: true
      , headers: {
        'Authorization': 'Bearer ' + getCookie("TOKEN_WHARF")
      }
      , defaultToolbar: ['filter', 'exports', 'print', { //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
        title: '提示'
        , layEvent: 'LAYTABLE_TIPS'
        , icon: 'layui-icon-tips'
      }]
      , height: 'full'
      , page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
        layout: ['limit', 'count', 'prev', 'page', 'next', 'skip'] //自定义分页布局
        , curr: 1 //设定初始在第 5 页
        , groups: 1 //只显示 1 个连续页码
        , first: false //不显示首页
        , last: false //不显示尾页

      }
      , cellMinHeight: 100
      , cols: [[
        {fixed: 'left', title: '操作', toolbar: '#operation', rowspan: 2, width: 120}
        , {field: 'taskId', minWidth: 80, title: 'taskId', sort: true, rowspan: 2}
        , {field: 'names', minWidth: 100, title: '任务名称', rowspan: 2}
        , {field: 'describe', minWidth: 100, title: '任务描述', rowspan: 2}
        , {
          field: 'execution', minWidth: 80, title: '执行时间', sort: true, rowspan: 2, templet: function (d) {
            return util.toDateString(d.execution * 1000);
          }
        }
        , {field: 'retry', minWidth: 80, title: '重试次数', sort: true, rowspan: 2}
        , {title: '请求体', align: 'center', minWidth: 80, colspan: 12}
        , {field: 'status', minWidth: 80, title: '状态', sort: true, rowspan: 2}
        , {
          field: 'createdAt', minWidth: 80, title: '创建时间', rowspan: 2, sort: true, templet: function (d) {
            return util.toDateString(d.createdAt * 1000);
          }
        }
        , {
          field: 'updatedAt', minWidth: 80, title: '更新时间', rowspan: 2, sort: true, templet: function (d) {
            return util.toDateString(d.updatedAt * 1000);
          }
        }
      ], [
        {field: 'body.url', title: "URL", templet: '<div>{{d.bodys.url}}</div>'}
        , {field: 'bodys.method', title: "Method", templet: '<div>{{d.bodys.method}}</div>'}
        , {field: 'bodys.cookies', title: "Cookie", templet: '<div>{{d.bodys.cookies??"没有传参"}}</div>'}
        , {field: 'bodys.body', title: "Body", templet: '<div>{{d.bodys.body??"没有传参"}}</div>'}
        , {field: 'bodys.headers', title: "Headers", templet: '<div>{{d.bodys.headers??"没有传参"}}</div>'}
        , {field: 'bodys.form_params', title: "FormParams", templet: '<div>{{d.bodys.form_params??"没有传参"}}</div>'}
        , {field: 'bodys.timeout', title: "Timeout", templet: '<div>{{d.bodys.timeout??"没有传参"}}</div>'}
        , {field: 'bodys.version', title: "Version", templet: '<div>{{d.bodys.version??"没有传参"}}</div>'}
        , {
          field: 'bodys.connect_timeout',
          title: "ConnectTimeout",
          templet: '<div>{{d.bodys.connect_timeout??"没有传参"}}</div>'
        }
        , {field: 'bodys.verify', title: "Verify", templet: '<div>{{d.bodys.verify??"没有传参"}}</div>'}
      ]]
    });

    //监听行工具事件
    table.on('tool(table)', function (obj) {
      var data = obj.data;
      if (obj.event === 'del') {
        layer.confirm('真的要取消任务吗？', function (index) {
          postRequest(task_delete, {'taskId': data.taskId}, function (result) {
            obj.update({
              status: "执行取消!(:<"
            });
          });
          layer.close(index);
        });
      } else if (obj.event === 'detail') {
        layer.msg('TASKID：' + data.taskId + ' 的查看操作');
        layer.open({
          type: 1
          , title: "任务:" + data.taskId //不显示标题栏
          , closeBtn: false
          , area: '1000px'
          , shade: 0.8
          , id: 'LAY_layuipro' //设定一个id，防止重复弹出
          , btn: ['查看日志', '关闭页面']
          , btnAlign: 'c'
          , moveType: 1 //拖拽模式，0或者1
          , content: $('#panel').html()
          , success: function (layero) {
            console.log(data);
            form.val("panel-form", data);
            var editor = new JsonEditor($('#jsoneditor'), data.bodys, {});
            editor.load(data.bodys);
            try {
              editor.get();
            } catch (ex) {
              //   Trigger an Error when JSON invalid
              alert(ex);
            }
            console.log(editor);

            /*var btn = layero.find('.layui-layer-btn');
            btn.find('.layui-layer-btn0').attr({
              href: 'http://www.layui.com/'
              ,target: '_blank'
            });*/

          }
        });
      }
    });
  });
</script>
<script src="/lib/jquery/jquery-1.11.0.min.js"></script>
<script type="application/javascript" src="/lib/jsoneditor/jquery.json-editor.min.js"></script>
</html>
