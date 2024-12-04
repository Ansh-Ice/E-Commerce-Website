
<div >
  <h3>All Vendors</h3>
  <table class="table ">
    <thead>
      <tr>
        <th class="text-center">S.N.</th>
        <th class="text-center">Username </th>
        <th class="text-center">Email</th>
        <th class="text-center">Contact Number</th>
        <th class="text-center">Ending Date</th>
      </tr>
    </thead>
    <?php
      include_once "../config/db.php";
      $sql="SELECT * from tbl_vendor where status='approved'";
      $result=$conn-> query($sql);
      $count=1;
      if ($result-> num_rows > 0){
        while ($row=$result-> fetch_assoc()) {
    ?>
    <tr>
      <td><?=$count?></td>
      <td><?=$row["V_Name"]?></td> 
      <td><?=$row["Email"]?></td> 
      <td><?=$row["Contact_No"]?></td>
      <td><?=$row["Ending_Date"]?></td>
      <!-- <td><button class="btn btn-primary" >Edit</button></td> -->
      <!--<td><button class="btn btn-danger" style="height:40px" onclick="sizeDelete('<?=$row['size_id']?>')">Delete</button></td>-->
      </tr>
      <?php
            $count=$count+1;
          }
        }
      ?>
  </table>
  
</div>
   