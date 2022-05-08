<?php
include_once 'connectdb.php';

session_start();


if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "") {


  header('location:index.php');
}



if ($_SESSION['role'] == "Admin") {
  include_once 'header.php';
} else {
  include_once 'headeruser.php';
}

if (isset($_POST['btnaddproduct'])) {

  $productname = $_POST['txtpname'];

  $category = $_POST['txtselect_option'];

  $purchaseprice =  $_POST['txtpprice'];

  $saleprice =  $_POST['txtsaleprice'];

  $stock = $_POST['txtstock'];

  $description = $_POST['txtdescription'];


  $f_name = $_FILES['myfile']['name'];



  $f_tmp = $_FILES['myfile']['tmp_name'];


  $f_size =  $_FILES['myfile']['size'];

  $f_extension = explode('.', $f_name);
  $f_extension = strtolower(end($f_extension));

  $f_newfile =  uniqid() . '.' . $f_extension;

  $store = "productimages/" . $f_newfile;


  if ($f_extension == 'jpg' || $f_extension == 'jpeg' ||  $f_extension == 'png' || $f_extension == 'gif') {

    if ($f_size >= 1000000) {



      $error = '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process failed!",
  text: "Max. file size is 1MB!",
  icon: "warning",
  button: "OK",
});


});

</script>';

      echo $error;
    } else {

      if (move_uploaded_file($f_tmp, $store)) {

        $productimage = $f_newfile;
        if (!isset($error)) {

          $insert = $pdo->prepare("insert into tbl_product(pname,pcategory,purchaseprice,saleprice,pstock,pdescription,pimage) values(:pname,:pcategory,:purchaseprice,:saleprice,:pstock,:pdescription,:pimage)");

          $insert->bindParam(':pname', $productname);
          $insert->bindParam(':pcategory', $category);
          $insert->bindParam(':purchaseprice', $purchaseprice);
          $insert->bindParam(':saleprice', $saleprice);
          $insert->bindParam(':pstock', $stock);
          $insert->bindParam(':pdescription', $description);
          $insert->bindParam(':pimage', $productimage);


          if ($insert->execute()) {

            echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process Successfull!",
  text: "New product added",
  icon: "success",
  button: "OK",
});


});

</script>';
          } else {

            echo '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Process failed!",
  text: "Product was not added",
  icon: "error",
  button: "OK",
});


});

</script>';
          }
        }
      }
    }
  } else {



    $error = '<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "Wrong file format!",
  text: "Only jpg ,jpeg, png & gif can be uploaded!",
  icon: "error",
  button: "OK",
});


});

</script>';

    echo $error;
  }
}






?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add Product
      <small></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active">Add Product</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">

    <!--------------------------
        | Your Page Content Here |
        -------------------------->

    <div class="box box-info">

      <!-- This is just a back-link to the product list, uncomment it if you want to use it; 
             <div class="box-header with-border">
        <h3 class="box-title"> <a href="productlist.php" class="btn btn-primary" role="button">Back To Product
            List</a></h3>
      </div> -->
      <!-- /.box-header -->
      <!-- form start -->

      <form action="" method="post" name="formproduct" enctype="multipart/form-data">


        <div class="box-body">



          <div class="col-md-6">

            <div class="form-group">
              <label>Product Name</label>
              <input type="text" class="form-control" name="txtpname" placeholder="Enter product name" required>
            </div>


            <div class="form-group">
              <label>Category</label>
              <select class="form-control" name="txtselect_option" required>
                <option value="" disabled selected>Select Category</option>
                <?php
                $select = $pdo->prepare("select * from tbl_category order by catid asc");
                $select->execute();
                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                  extract($row);
                ?>
                  <option><?php echo $row['category']; ?></option>

                <?php

                }


                ?>





              </select>
            </div>



            <div class="form-group">
              <label>Purchase price</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-gbp"></i>
                </div>
                <input type="number" min="1" step="1" class="form-control" name="txtpprice" placeholder="Enter a price..." required>
              </div>
            </div>

            <div class="form-group">
              <label>Sale price</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-gbp"></i>
                </div>
                <input type="number" min="1" step="1" class="form-control" name="txtsaleprice" placeholder="Enter a price..." required>
              </div>
            </div>



          </div>



          <div class="col-md-6">


            <div class="form-group">
              <label>Stock</label>
              <input type="number" min="1" step="1" class="form-control" name="txtstock" placeholder="Enter a number..." required>
            </div>


            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control" name="txtdescription" placeholder="Product description..." rows="4"></textarea>
            </div>


            <div class="form-group">
              <label>Product image</label>
              <input type="file" class="input-group" name="myfile" required>
            </div>


          </div>





        </div>


        <div class="box-footer">




          <button type="submit" class="btn btn-warning" name="btnaddproduct">Add product</button>

        </div>

      </form>




    </div>






  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php

include_once 'footer.php';

?>