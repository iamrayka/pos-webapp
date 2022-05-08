<?php
include_once 'connectdb.php';
session_start();

if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "User") {


    header('location:index.php');
}


include_once 'header.php';


error_reporting(0);

$id = $_GET['id'];


$delete = $pdo->prepare("delete from tbl_user where userid=" . $id);

if ($delete->execute()) {
    echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process completed!",
  text: "Account is deleted!",
  icon: "success",
  button: "Ok",
});


});

</script>';
}







if (isset($_POST['btnsave'])) {

    $username = $_POST['txtname'];
    $useremail = $_POST['txtemail'];
    $password = $_POST['txtpassword'];
    $userrole = $_POST['txtselect_option'];

    //We validate username, password and email in the three following if statements
    if (!preg_match("/^[a-zA-Z-' ]*$/", $username)) {
        echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Invalid username format",
  text: "Only letters & white space allowed",
  icon: "warning",
  button: {
    text: "Retry!",
},
});


});

</script>';
    } elseif (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $password)) {
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
    } elseif (!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
        echo '<script type="text/javascript">
        jQuery(function validation(){
        
        
        swal({
          title: "Invalid email format",
          text: "Acceptable format example: anything@serviceprovider.domain",
          icon: "warning",
          button: {
            text: "Retry!",
        },
        });
        
        
        });
        
        </script>';
    } else {



        //following line checks if the our $_POST and isset method are working fine
        //echo $username ."-".$useremail."-".$password."-".$userrole;
        //this line checks to see if the e-mail already exists in the database

        if (isset($_POST['txtemail'])) {

            $select = $pdo->prepare("select useremail from tbl_user where useremail='$useremail'");
            $select->execute();

            if ($select->rowCount() > 0) {

                echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Email already exists",
  text: "Try with another email address",
  icon: "warning",
  button: {
    text: "Retry!",
},
});


});

</script>';
            } else {


                $insert = $pdo->prepare("insert into tbl_user(username,useremail,password,role) values(:name,:email,:pass,:role)");

                $insert->bindParam(':name', $username);
                $insert->bindParam(':email', $useremail);
                $insert->bindParam(':pass', $password);
                $insert->bindParam(':role', $userrole);


                if ($insert->execute()) {

                    echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process completed",
  text: "Registration was successful",
  icon: "success",
  button: "Ok",
});


});

</script>';
                } else {

                    echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process failed",
  text: "Registration was failed",
  icon: "error",
  button: "Ok",
});


});

</script>';
                }
            }
        } // end if txtemail





    }
}

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Registration Panel
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Registration</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Registration Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="" method="post">
                <div class="box-body">



                    <div class="col-md-4">

                        <!--Username input-->
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" name="txtname" placeholder="Enter name" required>
                        </div>


                        <!--Email input-->
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="txtemail" placeholder="Enter email" required>
                        </div>

                        <!--Password input-->
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" id="txtpassword" name="txtpassword" placeholder="Enter password" data-toggle="password" required>
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" name="txtselect_option" required>
                                <option value="" disabled selected>Select role</option>
                                <option>User</option>
                                <option>Admin</option>

                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning" name="btnsave">Save</button>




                    </div>
                    <div class="col-md-8">

                        <div style="overflow-x:auto;">
                            <table id="usertable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>USER_ID</th>
                                        <th>USERNAME</th>
                                        <th>EMAIL</th>
                                        <th>PASSWORD</th>
                                        <th>ROLE</th>
                                        <th>DELETE</th>
                                    </tr>

                                </thead>



                                <tbody>

                                    <?php
                                    $select = $pdo->prepare("select * from tbl_user  order by userid asc");

                                    $select->execute();

                                    while ($row = $select->fetch(PDO::FETCH_OBJ)) {

                                        echo '
    <tr>
    <td>' . $row->userid . '</td>
    <td>' . $row->username . '</td>
    <td>' . $row->useremail . '</td>
    <td>' . $row->password . '</td>
    <td>' . $row->role . '</td>
    <td>
<a href="registration.php?id=' . $row->userid . '" class="btn btn-danger" role="button"><span class="glyphicon glyphicon-trash"  title="delete"></span></a>   
    
    </td>
     </tr>
     ';
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">

                    </div>
            </form>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
        $('#usertable').DataTable();
    });
</script>

<script type="text/javascript">
    $("#txtpassword").password('toggle');
</script>

<?php

include_once 'footer.php';

?>