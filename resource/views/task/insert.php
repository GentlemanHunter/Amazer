<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <style type="text/css">
    .layui-table-view .layui-table[lay-size=lg] .layui-table-cell {
      height: auto !important;
    },
  </style>
  <?= $this->include('layouts/common/header', ['title' => '新建任务']) ?>
</head>
<body>

<div class="layui">
  <blockquote class="layui-elem-quote layui-quote-nm layui-md" style="color: #cc0000;text-align: center">
    猿强，则国强。国强，则猿更强！            ——孟子（好囖。。其实这特喵的是我说的）
  </blockquote>
</div>

<form class="layui-form" action="">

  <div class="layui-form-item">
    <label class="layui-form-label">任务名称： *</label>
    <div class="layui-input-block">
      <input type="text" name="names" lay-verify="required" lay-reqtext="任务名称是必填项，岂能为空？" placeholder="请输入任务名称"
             autocomplete="off" class="layui-input" value="测试任务">
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">执行时间： *</label>
    <div class="layui-input-block">
      <input type="text" name="execution" lay-verify="required" id="date" lay-reqtext="执行时间是必填项，岂能为空？"
             placeholder="请输入 执行时间 可以精确到秒" autocomplete="off" class="layui-input" value="2020-09-30 17:00:00">
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">任务描述：</label>
    <div class="layui-input-block">
      <input type="text" name="describe" placeholder="请输入任务描述" autocomplete="off" class="layui-input">
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">回调地址： *</label>
    <div class="layui-input-block">
      <input type="tel" name="url" lay-verify="url" placeholder="请输入回调地址" autocomplete="off" class="layui-input" value="http://baidu.com">
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">请求方式：</label>
    <div class="layui-input-block">
      <select name="method">
        <option value="GET" selected="selected">GET</option>
        <option value="POST">POST</option>
        <option value="PUT">PUT</option>
        <option value="DELETE">DELETE</option>
        <option value="OPTION">OPTION</option>
      </select>
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">重试次数：</label>
    <div class="layui-input-block">
      <select name="retry">
        <option value="1" selected="selected">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
      </select>
    </div>
  </div>

  <div class="layui-form-item">
    <div class="layui-input-block">
      <button type="submit" class="layui-btn" lay-filter="fromData">立即提交</button>
      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
  </div>
</form>
</body>

<script>
  layui.use(['form', 'layedit', 'laydate'], function () {
    var form = layui.form
      , layer = layui.layer
      , layedit = layui.layedit
      , laydate = layui.laydate;

    //日期
    laydate.render({
      elem: '#date'
      , type: 'datetime'
      , format: 'yyyy-MM-dd HH:mm:ss'
    });
    laydate.render({
      elem: '#date1'
    });

    //创建一个编辑器
    var editIndex = layedit.build('LAY_demo_editor');

    //自定义验证规则
    form.verify({
      title: function (value) {
        if (value.length < 5) {
          return '标题至少得5个字符啊';
        }
      }
      , pass: [
        /^[\S]{6,12}$/
        , '密码必须6到12位，且不能出现空格'
      ]
      , content: function (value) {
        layedit.sync(editIndex);
      }
    });

    //监听指定开关
    form.on('switch(switchTest)', function (data) {
      layer.msg('开关checked：' + (this.checked ? 'true' : 'false'), {
        offset: '6px'
      });
      layer.tips('温馨提示：请注意开关状态的文字可以随意定义，而不仅仅是ON|OFF', data.othis)
    });

    //监听提交
    form.on('submit(fromData)', function (data) {

      var param = data.field;

      var sendData = {
          'names' : param.names,
          'bodys' : JSON.stringify({'method':param.method,'url':param.url}),
          'describe' : (param.describe == '') ? param.names + '任务没有描述' : param.describe,
          'retry' : param.retry,
          'execution' : param.execution
      };

      layer.alert(JSON.stringify(sendData), {
        title: '最终的提交信息'
      })

      return false;
    });
  });
</script>

</html>
