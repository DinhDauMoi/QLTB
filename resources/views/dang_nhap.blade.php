<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @extends('fw')
    <title>Đăng nhập</title>
</head>

<body>
    <style>
        /* From Uiverse.io by kennyotsu-monochromia */
        .container {
            width: 100%;
            height: 100%;
            --color: #E1E1E1;
            background-color: #F3F3F3;
            background-image: linear-gradient(0deg, transparent 24%, var(--color) 25%, var(--color) 26%, transparent 27%, transparent 74%, var(--color) 75%, var(--color) 76%, transparent 77%, transparent),
                linear-gradient(90deg, transparent 24%, var(--color) 25%, var(--color) 26%, transparent 27%, transparent 74%, var(--color) 75%, var(--color) 76%, transparent 77%, transparent);
            background-size: 55px 55px;
        }
    </style>

    <body class="container">
        <div>
            <div class="text-center p-3">
                <img width="35%" src="/img/logo_lcnb.png" alt="" srcset="">
            </div>
            <div class="w-50" style="margin: 0 auto;">
                <form action="" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Tên đăng nhập</label>
                        <input type="text" name="ten_dang_nhap" class="form-control border border-black" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Mật khẩu</label>
                        <input name="mat_khau" type="password" class="form-control border border-black" id="exampleInputPassword1">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                </form>
            </div>
        </div>

    </body>
</body>

</html>