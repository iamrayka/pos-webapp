<?php

include_once 'connectdb.php';

session_start();

if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "") {


    header('location:index.php');
}



function fill_product($pdo)
{

    $output = '';

    $select = $pdo->prepare("select * from tbl_product order by pname asc");
    $select->execute();

    $result = $select->fetchAll();

    foreach ($result as $row) {

        $output .= '<option value="' . $row["pid"] . '">' . $row["pname"] . '</option>';
    }

    return $output;
}

//Inserting data form into tbl_invoice
if (isset($_POST['btnsaveorder'])) {
    //details required for tbl_invoice
    $customer_name = $_POST['txtcustomer'];
    $order_date = date('Y-m-d', strtotime($_POST['orderdate']));
    $subtotal = $_POST["txtsubtotal"];
    $tax = $_POST['txttax'];
    $discount = $_POST['txtdiscount'];
    $total = $_POST['txttotal'];
    $paid = $_POST['txtpaid'];
    $due = $_POST['txtdue'];
    $payment_type = $_POST['rb'];
    ////////////////////////////////
    //details required for tbl_invoice_details
    $arr_productid = $_POST['productid'];
    $arr_productname = $_POST['productname'];
    $arr_stock = $_POST['stock'];
    $arr_qty = $_POST['qty'];
    $arr_price = $_POST['price'];
    $arr_total = $_POST['total'];




    //Insert query for tbl_invoice
    $insert = $pdo->prepare("insert into tbl_invoice(customer_name,order_date,subtotal,tax,discount,total,paid,due,payment_type) values(:cust,:orderdate,:stotal,:tax,:disc,:total,:paid,:due,:ptype)");

    $insert->bindParam(':cust', $customer_name);
    $insert->bindParam(':orderdate', $order_date);
    $insert->bindParam(':stotal', $subtotal);
    $insert->bindParam(':tax', $tax);
    $insert->bindParam(':disc', $discount);
    $insert->bindParam(':total', $total);
    $insert->bindParam(':paid', $paid);
    $insert->bindParam(':due', $due);
    $insert->bindParam(':ptype', $payment_type);

    $insert->execute();



    //2nd insert query for tbl_invoice_details

    $invoice_id = $pdo->lastInsertId();
    if ($invoice_id != null) {

        for ($i = 0; $i < count($arr_productid); $i++) {


            $rem_qty = $arr_stock[$i] - $arr_qty[$i];

            if ($rem_qty < 0) {

                return "Order is not completed";
            } else {

                $update = $pdo->prepare("update tbl_product SET pstock ='$rem_qty' where pid='" . $arr_productid[$i] . "'");

                $update->execute();
            }









            $insert = $pdo->prepare("insert into tbl_invoice_details(invoice_id,product_id,product_name,qty,price,order_date) values(:invid,:pid,:pname,:qty,:price,:orderdate)");

            $insert->bindParam(':invid', $invoice_id);
            $insert->bindParam(':pid', $arr_productid[$i]);
            $insert->bindParam(':pname', $arr_productname[$i]);
            $insert->bindParam(':qty', $arr_qty[$i]);
            $insert->bindParam(':price', $arr_price[$i]);
            $insert->bindParam(':orderdate', $order_date);


            $insert->execute();
        }

        header('location:orderlist.php');
    }
}


if ($_SESSION['role'] == "Admin") {


    include_once 'header.php';
} else {

    include_once 'headeruser.php';
}








?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">





    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Create Order
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Create order</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-info">
            <form action="" method="post" name="">

                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->




                <div class="box-body">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer Name</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>


                                <input type="text" class="form-control" name="txtcustomer" placeholder="Enter Customer Name" required>
                            </div>
                        </div>



                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Date:</label>

                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker" name="orderdate" value="<?php echo date("Y-m-d"); ?>" data-date-format="yyyy-mm-dd">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>




                </div> <!-- this is for customer and date -->

                <div class="box-body">
                    <div class="col-md-12">
                        <div style="overflow-x:auto;">
                            <table class="table table-bordered" id="producttable">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Search product</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        <th>Enter quantity</th>
                                        <th>Total</th>
                                        <th>
                                            <center> <button type="button" name="add" class="btn btn-success btn-sm btnadd"><span class="glyphicon glyphicon-plus"></span></button></center>

                                        </th>

                                    </tr>

                                </thead>


                            </table>
                        </div>



                    </div>



                </div>
                <!-- this for table -->

                <div class="box-body">

                    <div class="col-md-6">

                        <div class="form-group">
                            <label>SubTotal</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-gbp"></i>
                                </div>



                                <input type="text" class="form-control" name="txtsubtotal" id="txtsubtotal" required readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tax (5%)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-gbp"></i>
                                </div>


                                <input type="text" class="form-control" name="txttax" id="txttax" required readonly>
                            </div>
                        </div>


                        <div class="form-group">
                            <label>Discount</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-gbp"></i>
                                </div>
                                <input type="text" class="form-control" name="txtdiscount" id="txtdiscount" required>
                            </div>
                        </div>




                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Total</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-gbp"></i>
                                </div>

                                <input type="text" class="form-control" name="txttotal" id="txttotal" required readonly>
                            </div>
                        </div>



                        <div class="form-group">
                            <label>Paid</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-gbp"></i>
                                </div>

                                <input type="text" class="form-control" name="txtpaid" id="txtpaid" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Due</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-gbp"></i>
                                </div>
                                <input type="text" class="form-control" name="txtdue" id="txtdue" required readonly>
                            </div>
                        </div>





                        <!-- radio -->
                        <label>Payment Method</label>
                        <div class="form-group">

                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="Cash" checked> CASH
                            </label>
                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="Card"> CARD
                            </label>
                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="Check">
                                CHECK
                            </label>
                        </div>



                    </div>



                </div><!-- tax dis. etc -->

                <hr>

                <div align="center">

                    <input type="submit" name="btnsaveorder" value="Create" class="btn btn-warning">

                </div>

                <hr>

            </form>
        </div>




    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
    //Date picker
    $('#datepicker').datepicker({
        autoclose: true
    });


    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
    })


    // btnadd starts here 
    $(document).ready(function() {

        $(document).on('click', '.btnadd', function() {

            var html = '';
            html += '<tr>';

            html +=
                '<td><input type="hidden" class="form-control pname" name="productname[]" readonly></td>';

            html +=
                '<td><select class="form-control productid" name="productid[]" style="width: 250px";><option value="" disabled selected>Select Option</option><?php echo fill_product($pdo); ?> </select></td>';

            html += '<td><input type="text" class="form-control stock" name="stock[]" readonly></td>';
            html += '<td><input type="text" class="form-control price" name="price[]" readonly></td>';
            html += '<td><input type="number" min="1" class="form-control qty" name="qty[]" ></td>';
            html += '<td><input type="text" class="form-control total" name="total[]" readonly></td>';
            html +=
                '<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button><center></td></center>';

            $('#producttable').append(html);


            //Initialize Select2 Elements
            $('.productid').select2()

            $(".productid").on('change', function(e) {

                var productid = this.value;
                var tr = $(this).parent().parent();
                $.ajax({

                    url: "getproduct.php",
                    method: "get",
                    data: {
                        id: productid
                    },
                    success: function(data) {


                        tr.find(".pname").val(data["pname"]);
                        tr.find(".stock").val(data["pstock"]);
                        tr.find(".price").val(data["saleprice"]);
                        tr.find(".qty").val(1);
                        tr.find(".total").val(tr.find(".qty").val() * tr.find(".price")
                            .val());
                        calculate(0, 0);
                    }
                })
            })




        }) // btnadd ends here    

        //btnremove starts here
        $(document).on('click', '.btnremove', function() {

            $(this).closest('tr').remove();
            calculate(0, 0);
            $("#txtpaid").val(0);

        }) // btnremove ends here  


        $("#producttable").delegate(".qty", "keyup change", function() {

            var quantity = $(this);
            var tr = $(this).parent().parent();

            if ((quantity.val() - 0) > (tr.find(".stock").val() - 0)) {

                swal("Oops!", "Quantity entered exceeds the number of items in the stock",
                    "warning");

                quantity.val(1);

                tr.find(".total").val(quantity.val() * tr.find(".price").val());
                calculate(0, 0);
            } else {

                tr.find(".total").val(quantity.val() * tr.find(".price").val());
                calculate(0, 0);
            }



        })

        // function calculate ends here 
        function calculate(dis, paid) {

            var subtotal = 0;
            var tax = 0;
            var discount = dis;
            var net_total = 0;
            var paid_amt = paid;
            var due = 0;


            $(".total").each(function() {

                subtotal = subtotal + ($(this).val() * 1);

            })

            tax = 0.05 * subtotal;
            net_total = tax + subtotal; //50+1000 =1050
            net_total = net_total - discount;
            due = net_total - paid_amt;


            $("#txtsubtotal").val(subtotal.toFixed(2));
            $("#txttax").val(tax.toFixed(2));
            $("#txttotal").val(net_total.toFixed(2));
            $("#txtdiscount").val(discount);
            $("#txtdue").val(due.toFixed(2));



        } // function calculate ends here 

        $("#txtdiscount").keyup(function() {
            var discount = $(this).val();
            calculate(discount, 0);


        })

        $("#txtpaid").keyup(function() {
            var paid = $(this).val();
            var discount = $("#txtdiscount").val();
            calculate(discount, paid);

        })



    });
</script>


<?php

include_once 'footer.php';

?>