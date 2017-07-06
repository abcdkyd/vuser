<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
</head>
<body>
<form name="myForm" method="post">

    <div>
        手机号<input type="text" name="name">
        密码<input type="password" name="password" id="password">
        <input type="button" id="sub_btn" value="登录">
    </div>

    <div>
        手机号<input type="text" name="name2">
        密码<input type="password" name="password2" id="password2">
        验证码<input type="text" name="verifycode" id="verifycode">
        <input type="button" value="获取验证码" id="getcode">
        <input type="button" id="sub_btn2" value="注册">
    </div>


    <script>

        jQuery(function ($) {

            $('#sub_btn').click(function () {

                $.ajax({
                    type: "POST",
                    url : '/api/vuser/token',
                    contentType:'application/json;charset=UTF-8',
                    data: JSON.stringify({
                        name: $("input[name='name']").val(),
                        password: $('#password').val(),
                    }),
                    success: function (json) {
                        alert('登录成功，access_token = '+json.data.access_token);
                    },
                    error: function (back) {

                        alert('登录失败');
                        console.log(back);

                    }
                });

            });

            $('#getcode').click(function () {
                $.ajax({
                    type: "POST",
                    url : '/api/vcaptcha/get',
                    contentType:'application/json;charset=UTF-8',
                    data: JSON.stringify({
                        name: $("input[name='name2']").val(),
                    }),
                    success: function (json) {
                        alert(json.message[0]+'本地获取验证码为：'+json.data.message);
                    },
                    error: function (back) {
                        alert(back.responseJSON.message[0]);
                    }
                });
            });

            $('#sub_btn2').click(function () {
                $.ajax({
                    type: "POST",
                    url : '/api/vuser/register',
                    contentType:'application/json;charset=UTF-8',
                    data: JSON.stringify({
                        name: $("input[name='name2']").val(),
                        password: $('#password2').val(),
                        verifycode: $('#verifycode').val(),
                    }),
                    success: function (json) {
                        console.log(json);
                    },
                    error: function (back) {

                        console.log(back);

                    }
                });
            });

        });
    </script>
</form>
</body>
</html>