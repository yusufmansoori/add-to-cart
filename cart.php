<?php
$conn = mysqli_connect('localhost','root','Mansoori@216','tblproduct');

if(isset($_POST["add_to_cart"]))
{
  if(isset($_COOKIE["shopping_cart"]))
  {
    $cookie_data = stripslashes($_COOKIE['shopping_cart']);

    $cart_data = json_decode($cookie_data, true);
  }
  else
  {
    $cart_data = array();
  }

  $item_id_list = array_column($cart_data, 'item_id');

  if(in_array($_POST["hidden_id"], $item_id_list))
  {
    foreach($cart_data as $keys => $values)
    {
      if($cart_data[$keys]["item_id"] == $_POST["hidden_id"])
      {
        $cart_data[$keys]["item_quantity"] = $cart_data[$keys]["item_quantity"] + $_POST["quantity"];
      }
    }
  }
  else
  {
    $item_array = array(
      'item_id'     =>  $_POST["hidden_id"],
      'item_name'     =>  $_POST["hidden_name"],
      'item_price'    =>  $_POST["hidden_price"],
      'item_image'    =>  $_POST["hidden_image"],
      'item_quantity'   =>  $_POST["quantity"]
    );
    $cart_data[] = $item_array;
  }

  
  $item_data = json_encode($cart_data);
  setcookie('shopping_cart', $item_data, time() + (86400 * 30));
  header("location:cart.php?success=1");
}

if(isset($_GET["action"]))
{
  if($_GET["action"] == "delete")
  {
    $cookie_data = stripslashes($_COOKIE['shopping_cart']);
    $cart_data = json_decode($cookie_data, true);
    foreach($cart_data as $keys => $values)
    {
      if($cart_data[$keys]['item_id'] == $_GET["id"])
      {
        unset($cart_data[$keys]);
        $item_data = json_encode($cart_data);
        setcookie("shopping_cart", $item_data, time() + (86400 * 30));
        header("location:cart.php?remove=1");
      }
    }
  }
  if($_GET["action"] == "clear")
  {
    setcookie("shopping_cart", "", time() - 3600);
    header("location:index.php?clearall=1");
  }
}

if(isset($_GET["success"]))
{
  $message = '
  <div class="alert alert-success alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      Item Added into Cart
  </div>
  ';
}

if(isset($_GET["remove"]))
{
  $message = '
  <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    Item removed from Cart
  </div>
  ';
}
if(isset($_GET["clearall"]))
{
  $message = '
  <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    Your Shopping Cart has been clear...
  </div>
  ';
}

?>

<section style="padding: 20px 0 30px 0;">
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
          
      <div class="table-responsive">
      <h3 style="float:left;margin-bottom:16px;">Order Details</h3>
      <?php //echo $message; ?>
      <div align="right" style="float:right; margin-top:15px;">
        <a href="cart.php?action=clear" class="btn btn-danger btn-skin"><b>Clear Cart</b></a>
      </div>
        <br><br>
      <form method="post" action="checkout.php">
      <table border="1" cellpadding="10" cellspacing="0">
        <tr>
          <th width="40%">Item Name</th>
          <th width="10%">Image</th>
          <th width="20%">Quantity</th>
          <th width="20%">Price</th>
          <th width="15%">Total</th>
          <th width="5%">Action</th>
        </tr>
      <?php
      if(isset($_COOKIE["shopping_cart"]))
      {
        $total = 0;
        $cookie_data = stripslashes($_COOKIE['shopping_cart']);
        $cart_data = json_decode($cookie_data, true);
        foreach($cart_data as $keys => $values)
        {
      ?>
        <tr>
          <td><?php echo $values["item_name"]; ?></td>
          <td><img src="images/<?php echo $values["item_image"]; ?>" style="height:60px;"></td>
          <td><?php echo $values["item_quantity"]; ?></td> 
          <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $values["item_price"]; ?></td>
          <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($values["item_quantity"] * $values["item_price"], 2);?></td>
          <td><a href="cart.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>
        </tr>
      <?php 
          $total = $total + ($values["item_quantity"] * $values["item_price"]);
        }
      ?>
        <tr>
          <th colspan="4" align="right">Total</th>
          <th align="right"><i class="fa fa-inr" aria-hidden="true"></i>  <?php echo number_format($total, 2); ?></th>
          <td></td>
        </tr>
      <?php
      }
      else
      {
        echo '
        <tr>
          <td colspan="5" align="center">No Item in Cart</td>
        </tr>
        ';
      }
      ?>
      </table>

      
      <p class="text-right">
        <input type="submit" value="Proceed to Checkout" name="checkout" class="btn btn-blue">
      </p>
    </form>

      </div>
    </div>  
      </div>
    </div>
  </div>
</section>
