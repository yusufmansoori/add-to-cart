<?php 
  $conn = mysqli_connect('localhost','root','Mansoori@216','tblproduct');
?>
<div class="row">
  <?php 
      $s1 = "select * from tblproduct order by id desc limit 4";
      $sq1 = mysqli_query($conn,$s1) ;
      while($row1 = mysqli_fetch_array($sq1)) {
  ?>
  <div class="col-md-3">
    <form method="post" action="cart.php?id=<?php echo $row1['id'];?>">
    <div class="panel panel-default">
      <img src="images/<?=$row1['image'];?>" class="img-responsive">              
      <div class="panel-body">
        <h4><?=$row1['name'];?></h4>
        <p><?=substr($row1['code'],0,100);?></p>
        <input type="number" value="1" name="quantity" style="width:50px;">
        <h4><i class="fa fa-inr" aria-hidden="true"></i> <?=$row1['price'];?></h4>
        <br>
        <input type="hidden" name="hidden_id" value="<?php echo $row1['id']; ?>">
        <input type="hidden" name="hidden_name" value="<?php echo $row1['name']; ?>">
        <input type="hidden" name="hidden_price" value="<?php echo $row1['price']; ?>">
        <input type="hidden" name="hidden_image" value="<?php echo $row1['image']; ?>">
        <!-- <input type="hidden" name="quantity" value="1"> -->
        <input type="submit" class="btn btn-danger" name="add_to_cart" value="Add To Cart">
        <!-- <a href="#" class="btn btn-danger">Add to Cart</a> -->
      </div>
    </div>
  </form>
  </div>
  <?php } ?>

</div>