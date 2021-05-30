<!DOCTYPE html>
<html lang="en">
<?= $this->include('layouts/common/header', ['title' => '重置密码']) ?>
<body>

<div class="father">
  <div class="login-main">
    <p style="color:#009688;font-size:25px;text-align:center;">重置密码
      <a href="/" style="color:#009688;font-size:25px;text-align:center;">返回首页</a>
    </p>
    <form class="layui-form">
      <div class="layui-input-inline">
        <input type="text" class="layui-input" name="account" placeholder="请输入账号" autocomplete="off"
               class="layui-input" value="<?= $data['account'] ?>">
      </div>
      <br>
      <div class="layui-input-inline">
        <input type="password" name="password" placeholder="请输入密码" autocomplete="off"
               class="layui-input">
      </div>
      <br>
      <div class="layui-input-inline login-btn">
        <button lay-submit lay-filter="login" class="layui-btn">重置密码</button>
      </div>
      <hr/>
    </form>
  </div>
</div>

<script type="module">
  import {user_login, user_home} from '/service/js/api.js';
  import {postRequest} from '/service/js/request.js';

  layui.use(['form', 'layer', 'jquery'], function () {
    var form = layui.form;
    form.on('submit(login)', function (data) {
      postRequest(user_login, data.field, function (data) {
        console.log(data);
        setTimeout(function () {
          location.href = user_home;
        }, 1000);
      });
      return false;
    })
  });
</script>
</body>
</html>
