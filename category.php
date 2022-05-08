<?php
//connect to the db
//new instance of PDO was created in connectdb.php
include_once 'connectdb.php';
//using a cookies to keep user specific details
session_start();
//only Admin can update && delete a category 
if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "") {

  header('location:index.php');
}


if ($_SESSION['role'] == "Admin") {
  include_once 'header.php';
} else {
  include_once 'headeruser.php';
}




//btnsave - adding process starts here
if (isset($_POST['btnsave'])) {

  $category = $_POST['txtcategory'];

  //case analysis
  //first case: if the category field is empty (before pressing save button)
  if (empty($category)) {
    //using sweetalert.js to inform the user using a pop-up
    $error = '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process failed!",
  text: "Please fill in the field",
  icon: "error",
  button: {
    text: "Retry!",
},
});


});

</script>';

    echo $error;
  }

  //second case: if the category field is not empty (before pressing save button)
  //!isset($error) equates to $error not occuring, which means the category field isn't empty
  if (!isset($error)) {

    $insert = $pdo->prepare("insert into tbl_category(category) values(:category)");

    $insert->bindParam(':category', $category);

    if ($insert->execute()) {
      //using sweetalert.js to inform the user using a pop-up
      echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process successful!",
  text: "New category is added!",
  icon: "success",
  button: "OK",
});


});

</script>';
    } else {
      //just in the case if the query goes wrong
      //using sweetalert.js to inform the user using a pop-up
      echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process failed!",
  text: "Your query has failed",
  icon: "error",
  button: {
    text: "Retry!",
},
});


});

</script>';
    }
  }
} //btnsave - adding process ends here

//btnupdate - updating process starts here
//the process is quite similar to the adding process
if (isset($_POST['btnupdate'])) {

  $category = $_POST['txtcategory'];
  $id = $_POST['txtid'];
  //case analysis
  //first case: check if there exists any corresponding value:=$category to update
  //if empty then the following command will be executed
  if (empty($category)) {
    //using sweetalert.js to inform the user using a pop-up
    $errorupdate = '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process failed!",
  text: "The field is empty: please enter a category",
  icon: "error",
  button: {
    text: "Retry!",
},
});


});

</script>';


    echo $errorupdate;
  }
  //second case: if there exists a corresponding value=:$category
  //!isset($error) equates to $errorupdate not occuring, which means there exists corresponding value=:$category to update
  if (!isset($errorupdate)) {

    $update = $pdo->prepare("update tbl_category set category=:category where catid=" . $id);

    $update->bindParam(':category', $category);

    if ($update->execute()) {
      //using sweetalert.js to inform the user using a pop-up
      echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process successful!",
  text: "Your category is updated",
  icon: "success",
  button: "OK",
});


});

</script>';
    } else {
      //just in the case if the query goes wrong
      //using sweetalert.js to inform the user using a pop-up
      echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process failed!",
  text: "Your Category is not updated",
  icon: "error",
  button: {
    text: "Retry!",
},
});


});

</script>';
    }
  }
} //btnupdate - updating process ends here


//btndelete - deleting process starts here
//checking if the delete button is pressed
if (isset($_POST['btndelete'])) {
  //deleting a category with catid corresponding the delete button pressed by the Admin/User
  $delete = $pdo->prepare("delete from tbl_category where catid=" . $_POST['btndelete']);
  if ($delete->execute()) {
    //If query goes as expected 
    //using sweetalert.js to inform the user using a pop-up
    echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process successful!",
  text: "Your category is deleted",
  icon: "success",
  button: "OK",
});


});

</script>';
  } else {
    //just in the case if the query goes wrong
    //using sweetalert.js to inform the user using a pop-up
    echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process failed!",
  text: "Your category is not deleted",
  icon: "success",
  button: {
    text: "Retry!",
},
});


});

</script>';
  }
}
//btndelete - deleting process ends here
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Category
      <small></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active">Category</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">

    <!--------------------------
        | Your Page Content Here |
        -------------------------->

    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Category Form</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->




      <div class="box-body">
        <form role="form" action="" method="post">
          <?php
          if (isset($_POST['btnedit'])) {

            $select = $pdo->prepare("select * from tbl_category where catid=" . $_POST['btnedit']);
            $select->execute();
            if ($select) {
              $row = $select->fetch(PDO::FETCH_OBJ);
              echo ' <div class="col-md-4">
                                 
                   <div class="form-group">
                  <label >Category</label>
<input type="hidden" class="form-control" value="' . $row->catid . '" name="txtid"  placeholder="Enter Category" >
                  
                  
<input type="text" class="form-control" value="' . $row->category . '" name="txtcategory"  placeholder="Enter Category" >
                </div>
                
    <button type="submit" class="btn btn-info" name="btnupdate">Update</button>
                   
              </div>';
            }
          } else {

            echo ' <div class="col-md-4">
                                 
                   <div class="form-group">
                  <label >Category</label>
                  <input type="text" class="form-control" name="txtcategory" placeholder="Enter Category" >
                </div>
                
                 <button type="submit" class="btn btn-warning" name="btnsave">Save</button>
                   
              </div>';
          }


          ?>






          <div class="col-md-8">
            <div style="overflow-x:auto;">
              <table id="categorytable" class="table table-striped">
                <thead>
                  <tr>
                    <th>CATEGORY_ID</th>
                    <th>CATEGORY</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
                  </tr>

                </thead>
                <tbody>
                  <?php
                  $select = $pdo->prepare("select * from tbl_category order by catid desc");
                  $select->execute();
                  while ($row = $select->fetch(PDO::FETCH_OBJ)) {

                    echo ' <tr>
    <td>' . $row->catid . '</td>
    <td>' . $row->category . '</td>
    
    <td>
      <button type="submit" value=' . $row->catid . ' class="btn btn-success" name="btnedit">Edit</button>
    </td>
    
    <td>
        <button type="submit" value="' . $row->catid . '" class="btn btn-danger" name="btndelete">Delete</button>
    </td>
   
     </tr>';
                  }

                  ?>

                </tbody>
              </table>
            </div>
        </form>
      </div>
      <!-- /.box-body -->

      <div class="box-footer">

      </div>

    </div>





  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
  $(document).ready(function() {
    $('#categorytable').DataTable();
  });
</script>




<?php

include_once 'footer.php';

?>