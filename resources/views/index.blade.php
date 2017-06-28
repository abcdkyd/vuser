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

    <input type="text" name="name">
    <input type="password" name="password" id="password">
    <input type="button" id="sub_btn" value="提交">

    <script>

        jQuery(function ($) {

            $('#sub_btn').click(function () {

                $.ajax({
                    type: "POST",
                    url : '/vuser/token',
                    contentType:'application/json;charset=UTF-8',
                    data: JSON.stringify({
                        name: $("input[name='name']").val(),
                        password: $('#password').val(),
                    }),
                    success: function (json) {
                        console.log(json)
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