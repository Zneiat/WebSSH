<?php require_once ('core/core.php');use app\UserTmpData; ?>
<!DOCTYPE html>
<html>
<head>
  <title>AirOS</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <script src="./assets/js/jquery.min.js"></script>
  <?php if(!$CoreUsers->IsGuest()): ?>
  <script>
    window.username = "<?= $CoreUsers -> GetData()['username'] ?>";
    window.working_directory = "<?= addslashes(basename((new UserTmpData())->Data('Directory'))) ?>";
  </script>
  <link href="./assets/css/terminal.css" rel="stylesheet">
  <script src="./assets/js/terminal.js"></script>
  <link href="./user-style.php" rel="stylesheet">
  <?php else: ?>
  <link href="./assets/css/login.css" rel="stylesheet">
  <script src="./assets/js/login.js"></script>
  <?php endif; ?>
  <link href="./assets/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
  <?php if(!$CoreUsers->IsGuest()): ?>
  <div class="terminal">
    <div class="terminal-output">
      <div class="row"><span>Welcome to AirOS (Build 0003)</span></div>
      <div class="row"><span>Today is <?= date("D M d Y") ?></span></div>
    </div>
    <div class="terminal-input">
      <span class="prompt"><span></span></span>
      <span class="input" style="float: inherit">
        <span id="input-content"></span>
        <span class="cursor-blink empty"></span>
        <span id="cursor-blink-after"></span>
        <textarea class="clipboard" autofocus></textarea>
      </span>
    </div>
  </div>
  <?php else: ?>
  <div class="main-container">
    <div class="header">
      <ul>
        <li><a href="javascript:void(0);" onclick="ContentOpen('login')">登录</a></li>
        <li><a href="javascript:void(0);" onclick="ContentOpen('setting')">配置</a></li>
        <li><a href="javascript:void(0);" onclick="ContentOpen('about')">关于</a></li>
        <li class="slider"></li>
      </ul>
    </div>
    <div class="content">
      <div class="login-form" id="login">
        <div class="form-group">
          <input class="form-control" type="text" id="username" required="required" autocomplete="off" spellcheck="false">
          <label class="form-label">用户名</label>
        </div>
        <div class="form-group">
          <input class="form-control" type="password" id="password" required="required">
          <label class="form-label">密码</label>
        </div>
        <div class="form-msg"></div>
        <button class="form-btn" onclick="Login()">登录</button>
      </div>
      <div class="setting-form" id="setting" style="display: none">
        <div class="setting-group">
          <label class="setting-label">主机</label>
          <div class="setting-content">127.0.0.1:22</div>
        </div>
        <div class="setting-group">
          <label class="setting-label">背景颜色</label>
          <div class="setting-content">
            <ul class="color-select" id="BgColorSetting">
              <li style="background: #F7F7F7" onclick="BgColorSetting($(this))"></li>
              <li style="background: black" class="active" onclick="BgColorSetting($(this))"></li>
              <li style="background: lightsalmon;" onclick="BgColorSetting($(this))"></li>
              <li style="background: lightseagreen;" onclick="BgColorSetting($(this))"></li>
              <li style="background: lightblue;" onclick="BgColorSetting($(this))"></li>
            </ul>
          </div>
        </div>
        <div class="setting-group">
          <label class="setting-label">字体颜色</label>
          <div class="setting-content">
            <ul class="color-select" id="FontColorSetting">
              <li style="background: #F7F7F7" class="active" onclick="FontColorSetting($(this))"></li>
              <li style="background: black" onclick="FontColorSetting($(this))"></li>
              <li style="background: lightsalmon;" onclick="FontColorSetting($(this))"></li>
              <li style="background: lightseagreen;" onclick="FontColorSetting($(this))"></li>
              <li style="background: lightblue;" onclick="FontColorSetting($(this))"></li>
            </ul>
          </div>
        </div>
        <div class="setting-group">
          <label class="setting-label">字体大小</label>
          <div class="setting-content">
            <ul class="font-size-select" id="FontSizeSetting">
              <li class="big" onclick="FontSizeSetting('big',$(this))">大</li>
              <li class="middle active" onclick="FontSizeSetting('middle',$(this))">中</li>
              <li class="small" onclick="FontSizeSetting('small',$(this))">小</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="about" id="about" style="display: none">
        <img src="./assets/img/logo.svg">
        <div style="margin-top: 40px;text-align: center">Designed By AiRlind.net · 不忘初心 方得始終</div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</body>
</html>