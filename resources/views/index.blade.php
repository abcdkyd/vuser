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
    <?php echo csrf_field();?>
    <input type="button" id="sub" value="提交">

    <script>
        jQuery(function ($) {

            $('#sub').click(function () {

                var token = $("input[name='_token']").val();

                $.ajax({
                    type: "POST",
                    url : 'http://phjr.alpha/vuser/token',
                    headers: {
                        'X-XSRF-TOKEN': $("input[name='_token']").val(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        name: $("input[name='name']").val(),
                        password: $('#password').val(),
                        '_token': $("input[name='_token']").val()
                    },
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