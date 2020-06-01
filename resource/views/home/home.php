<!DOCTYPE html>
<html>
<?= $this->include('layouts/common/header', ['title' => '工作面板']) ?>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo">Wharf-S</div>
    <ul class="layui-nav layui-layout-right">
      <li class="layui-nav-item">
        <a href="javascript:;">
          <span class="layui-layim-user"><?= $userInfo['username'] ?></span>
        </a>
        <dl class="layui-nav-child">
          <dd><a href="javascript:;" class="userInfo">个人资料</a></dd>
        </dl>
      </li>
      <li class="layui-nav-item"><a href="/user/signout">退出</a></li>
    </ul>
  </div>


  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree" lay-filter="test">
        <?php foreach ($menus as $menu) {
          echo <<<EOF
        <li class="layui-nav-item">
          <a href="javascript:;">{$menu['title']}</a>
EOF;
          foreach ($menu['child'] as $child) {
            echo <<<EOF
          <dl class="layui-nav-child">
            <dd><a href="javascript:;" class="addIframe" im-width="{$child['width']}" im-height="{$child['height']}" im-title="{$child['title']}" im-id="{$child['id']}" im-url="{$child['url']}">{$child['title']}</a></dd>
          </dl>
EOF;
          }
          echo "
        </li>";
        }
        ?>
      </ul>
    </div>
  </div>


  <div class="layui-body">
    <!-- 内容主体区域 -->
    <div style="padding: 15px;">
      <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
        <legend>OS信息</legend>
      </fieldset>
      <div class="layui-collapse" lay-filter="test">
        <div class="layui-colla-item">
          <h2 class="layui-colla-title">Task Total</h2>
          <div class="layui-colla-content">
            <div id="main"></div>
          </div>
        </div>
      </div>
    </div>

    <div style="padding: 20px; background-color: #F2F2F2;">
      <div class="layui-row layui-col-space15">
        <div class="layui-col-md6">
          <div class="layui-card">
            <div class="layui-card-header">帮助文档</div>
            <div class="layui-card-body">
              <p align="center">
                暂无：
              </p>
            </div>
          </div>
        </div>
        <div class="layui-col-md6">
          <div class="layui-card">
            <div class="layui-card-header">联系开发人员</div>
            <div class="layui-card-body">
              <p align="center">
                暂无：
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="layui-footer">
    <!-- 底部固定区域 -->
    <span style="font-size:16px;">© </span>2020
    <span id="onlineNumber"></span>
  </div>
</div>
<script type="module">
  import {static_user_info} from '/service/js/api.js';
  layui.use(['layer', 'jquery', 'element', 'code'], function () {
    var layer = layui.layer;
    var $ = layui.jquery;
    var element = layui.element;
    layui.code(); //引用code方法
    $(".userInfo").click(function () {
      layer.open({
        title: '用户资料',
        type: 2,
        closeBtn: 1,
        area: ['400px', '300px'],
        id: 'userInfo',
        maxmin: true,
        zIndex: layer.zIndex,
        shade: 0,
        content: static_user_info,
        success: function (layero) {
          layer.setTop(layero);
        }
      });
    });

    $(".addIframe").click(function (e) {
      let title = $(this).attr('im-title');
      let id = $(this).attr('im-id');
      let url = $(this).attr('im-url');
      let width = $(this).attr('im-width');
      let height = $(this).attr('im-height');
      layer.open({
        title: title,
        type: 2,
        closeBtn: 1,
        area: [width, height],
        id: id,
        maxmin: true,
        shade: 0,
        content: url,
        success: function (layero) {
        }
      });
    });
  });
</script>
</body>
</html>
