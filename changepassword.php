<?php
include_once 'connectdb.php';
session_start();
if ($_SESSION['useremail'] == "") {
    header('location: index.php');
}
if ($_SESSION['role'] == "Admin") {
    include_once 'header.php';
} else {
    include_once 'headeruser.php';
}



//when click on update password button, we retrieve values from user into variables

if (isset($_POST['btnupdate'])) {

    $oldpassword_txt = $_POST['txtoldpass'];
    $newpassword_txt = $_POST['txtnewpass'];
    $confpassword_txt = $_POST['txtconfpass'];
    // echo $oldpassword_txt . "-" . $newpassword_txt . "-" . $confpassword_txt;

    if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $newpassword_txt)) {
        echo '<script type="text/javascript">
        jQuery(function validation(){
        
        
        swal({
          title: "Invalid password format",
          text: "Length = 8-20 -- must contain uppercase, lowercase, number & symbol",
          icon: "warning",
          button: {
            text: "Retry!",
        },
        });
        
        
        });
        
        </script>';
    } else {

        //using select query, we retrieve database record according to useremail

        $email = $_SESSION['useremail'];
        $select = $pdo->prepare("select * from tbl_user where useremail='$email'");
        $select->execute();
        $row = $select->fetch(PDO::FETCH_ASSOC);
        //the following two green lines-to check if we fetch data correctly from the database
        // echo $row['useremail'];
        // echo $row['username'];

        $useremail_db = $row['useremail'];
        $password_db = $row['password'];


        //comparing userinput and database values, and checking userinputs 

        if ($oldpassword_txt === $password_db) {
            if ($newpassword_txt !== $oldpassword_txt) {
                if ($newpassword_txt === $confpassword_txt) {

                    //if values matched, then run update query
                    $update = $pdo->prepare("update tbl_user set password=:pass where useremail=:email");
                    $update->bindParam(':pass', $confpassword_txt);
                    $update->bindParam(':email', $email);

                    if ($update->execute()) {
                        echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title:"Process completed!",
                            text: "Password is updated",
                            icon: "success",
                            button: "OK",
                        });
                    });
                    </script>';
                    } else {
                        echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title:"Process failed!",
                            text: "Query failed. Please contact helpdesk",
                            icon: "error",
                            button: "OK",
                        });
                    });
                    </script>';
                    }
                } else {
                    echo '<script type="text/javascript">
                jQuery(function validation(){
                    swal({
                        title:"Process failed!",
                        text: "New and confirmed passwords are not matched",
                        icon: "warning",
                        button: {
                            text: "Retry!",
                        },
                    });
                });
                </script>';
                }
            } else {
                echo '<script type="text/javascript">
            jQuery(function validation(){
                swal({
                    title:"Process failed!",
                    text: "New and old passwords are the same. Please, enter a new password",
                    icon: "warning",
                    button: {
                        text: "Retry!",
                    },
                });
            });
            </script>';
            }
        } else {
            echo '<script type="text/javascript">
        jQuery(function validation(){
            swal({
                title:"Process failed!",
                text: "Old password entered is wrong",
                icon: "warning",
                button: {
                    text: "Retry!",
                },
            });
        });
        </script>';
        }
    }
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Change Password
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Change Password</a></li>
            <!-- <li class="active"></li> -->
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
          | Page Content Here |
        -------------------------->


        <!-- general form elements -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Change of Password Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="" method="POST">
                <div class="box-body">

                    <div class="form-group">
                        <label for="exampleInputPassword1">Old Password</label>
                        <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Old Password" name="txtoldpass" required>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">New Password</label>
                        <input type="password" class="form-control" id="txtnewpass" placeholder="New Password" name="txtnewpass" data-toggle="password" required>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">Confirm Password</label>
                        <input type="password" class="form-control" id="txtconfpass" placeholder="Confirmed Password" name="txtconfpass" data-toggle="password" required>
                    </div>

                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-warning" name="btnupdate">Update</button>
                    </div>
            </form>
        </div>
        <!-- /.box -->


    </section>
    <!-- /.content -->
    <script type="text/javascript">
        $("#txtnewpass").password('toggle');
    </script>
    <script type="text/javascript">
        $("#txtconfpass").password('toggle');
    </script>
</div>
<!-- /.content-wrapper -->
<?php
include_once 'footer.php';
?>