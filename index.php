    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <!-- sweetAlert -->
    <script src="bower_components/sweetalert/sweetalert.js"></script>
    <!-- Password Toggle -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-show-password/1.0.3/bootstrap-show-password.min.js"></script>

    <?php
    include_once 'connectdb.php';
    session_start();

    if (isset($_POST['btn_login'])) {

        $useremail = $_POST['txt_email'];
        $password = $_POST['txt_password'];

        $select = $pdo->prepare("select * from tbl_user where useremail='$useremail' AND password='$password'");
        $select->execute();

        $row = $select->fetch(PDO::FETCH_ASSOC);

        if ($row > 0 && $row['useremail'] === $useremail && $row['password'] === $password && $row['role'] === "Admin") {
            $_SESSION['userid'] = $row['userid'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['useremail'] = $row['useremail'];
            $_SESSION['role'] = $row['role'];

            echo '<script type="text/javascript">
        jQuery(function validation(){
            swal({
                title:"Hi, ' . $_SESSION['username'] . '!",
                text: "Logging in ...",
                icon: "success",
                button: false,
            });
        });
        </script>';
            header('refresh:3;dashboard.php');
        } else if ($row > 0 && $row['useremail'] === $useremail && $row['password'] === $password && $row['role'] === "User") {
            $_SESSION['userid'] = $row['userid'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['useremail'] = $row['useremail'];
            $_SESSION['role'] = $row['role'];

            echo '<script type="text/javascript">
        jQuery(function validation(){
            swal({
                title:"Hi, ' . $_SESSION['username'] . '!",
                text: "Logging in ...",
                icon: "success",
                button: false,
            });
        });
        </script>';
            header('refresh:3;user.php');
        } else {
            echo '<script type="text/javascript">
        jQuery(function validation(){
            swal({
                title:"Login failed!",
                text: "Invalid e-mail or password!",
                icon: "error",
                button: {
                    text: "Retry!",
                },
            });
        });
        </script>';
        }
    }
    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Inventory-POS | Log in</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="index.php"><b>Inventory</b>-POS</a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="" method="post">
                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" placeholder="Email" name="txt_email" required>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" id="txtpass" placeholder="Password" name="txt_password" data-toggle="password" required>
                        <!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <a href="#" id="email">Forgot password?</a><br>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat" name="btn_login">Login</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

        <script>
            $(function() {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' /* optional */
                });
            });
        </script>

        <script type="text/javascript">
            $("#txtpass").password('toggle');
        </script>

        <script>
            $("#email").on("click", function() {
                swal({
                        closeOnClickOutside: false,
                        title: 'For Password Retrieval',
                        text: 'Please contact helpdesk',
                        icon: 'warning',
                        button: 'Contact',
                    })
                    .then(function() {
                        window.location.href = 'mailto:helpdesk@ymc.com';
                    })
            })
        </script>
    </body>

    </html>