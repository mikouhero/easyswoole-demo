<!doctype html>
<html lang="zh-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.staticfile.org/amazeui/2.7.2/css/amazeui.min.css">
    <link rel="stylesheet" href="/css/login.css?v=190527">
    <script src="https://cdn.staticfile.org/vue/2.5.17-beta.0/vue.js"></script>
    <script src="https://cdn.staticfile.org/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/layer/2.3/layer.js"></script>
    <title>登录微聊 - EASYSWOOLE CHAT DEMO</title>
</head>
<body>
<!-- 登录框体 -->
<div class="block-box">
    <div class="main-title">微聊</div>
    <div class="sub-title">EASYSWOOLE CHAT DEMO</div>
    <form class="am-form" style="margin-top: 80px;" id ='login'>
        <div class="am-form-group am-input-group">
            <span class="am-input-group-label"><i class="am-icon-at am-icon-fw"></i></span>
            <input type="email" class="am-form-field" placeholder="输入电子邮件" v-model='userAccount'>
        </div>
        <div class="am-form-group am-input-group">
            <span class="am-input-group-label"><i class="am-icon-lock am-icon-fw"></i></span>
            <input type="password" class="am-form-field" placeholder="输入登录密码" v-model='passWord'>
        </div>
        <div class="am-form-group">
            <button type="button" class="am-btn am-btn-primary am-btn-block" style="margin-top: 0px;" @click ='login'>开始畅聊</button>
        </div>
        <div class="am-form-group" style="text-align: center;margin-top: 130px;">
            <a href="/chat/register">没有微聊账号 马上注册一个</a>
        </div>
    </form>
</div>

<script>
    var Vm = new Vue({
        el : '#login',
        data:{
            userAccount:'',
            passWord:'',
            ReconnectBox:null
        },
        mounted:function(){

        },
        methods:{
            login   :function()
            {
                console.log(this.userAccount);
                console.log(this.passWord);
                var info = {
                    'userAccount':this.userAccount,
                    'passWord' :this.userAccount,
                };
                console.log(info);
                var othis = this;
                $.ajax({
                    url: '/Api/User/Auth/login',
                    type: "post",
                    data:info,
                    success: function (resp) {
                       if (resp.code == 200) {
                            othis.ReconnectBox = layer.msg('登录成功，正在进入...', {
                            scrollbar : false,
                            shade     : 0.3,
                            shadeClose: false,
                            time      : 0,
                            offset    : 't'
                        });
                        window.location.href="/chat/index";
                       }else{
                           alert(resp.msg);
                       }
                    },
                    error:function (e) {
                        console.log(e)
                        alert(e.responseJSON.msg);
                    }
                    
                });

            }
        }


    });

</script>
</body>
</html>