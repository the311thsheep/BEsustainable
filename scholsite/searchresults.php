<?php
  
  $search = $_POST['search'];

// selects search query from database
  $result_sql = "SELECT * FROM products WHERE products.name LIKE '%$search%' OR products.barcode LIKE '%$search%' OR cart.name LIKE '%$search%'JOIN cert ON products.certID=cert.certID;";

  $result_qry = mysqli_query($dbconnect, $result_sql);

  if(mysqli_num_rows($result_qry)==0) {
    // no results error message
      echo "<h1>No results found</h1>";
    } else {
      $result_aa = mysqli_fetch_assoc($result_qry);
// displays result name, photo
?>
<!-- all results are in a row -->
<div class="row">
<?php
      do {
        $firstname = $result_aa['firstname'];
        $lastname = $result_aa['lastname'];
        $photo = $result_aa['photo'];
        ?>

<!-- student card -->
        <div class="card col-3" style="">
          <!-- img -->
          <img class="card-img-top" src="images/<?php echo $photo; ?>" alt="Card image cap">
          <div class="card-body">
            <!-- name -->
            <h5 class="card-title"><?php echo "$firstname $lastname"; ?></h5>

          </div>
        </div>
      <?php
        } while ($result_aa = mysqli_fetch_assoc($result_qry));
?></div><?php

  }

 ?>
