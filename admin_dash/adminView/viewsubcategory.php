
<div >
  <h2>Sub Category's</h2>
  <table class="table ">
    <thead>
      <tr>
        <th class="text-center">S.N.</th>
        <th class="text-center">Sub Category Name</th>
        <th class="text-center" colspan="2">Action</th>
      </tr>
    </thead>
    <?php
      include_once "../config/db.php";
      $sql="SELECT * from tbl_sub_category";
      $result=$conn-> query($sql);
      $count=1;
      if ($result-> num_rows > 0){
        while ($row=$result-> fetch_assoc()) {
    ?>
    <tr>
      <td><?=$count?></td>
      <td><?=$row["Name"]?></td>     
      <td><button class="btn btn-primary" style="height:40px" onclick="subcatEditForm('<?=$row['ID']?>')">Edit</button></td>
      <td><button class="btn btn-danger" style="height:40px"  onclick="subcatDelete('<?=$row['ID']?>')">Delete</button></td>
      </tr>
      <?php
            $count=$count+1;
          }
        }
      ?>
  </table>

  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
    Add sub category
  </button>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New sub category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form  enctype='multipart/form-data' action="./controller/addsubcatController.php" method="POST">
            
            <div class="form-group">
              <label>Category:</label>
              <select name="category" >
                <option disabled selected>Select category</option>
                <?php

                  $sql="SELECT * from tbl_category";
                  $result = $conn-> query($sql);

                  if ($result-> num_rows > 0){
                    while($row = $result-> fetch_assoc()){
                      echo"<option value='".$row['ID']."'>".$row['Name']."</option>";
                    }
                  }
                ?>
              </select>
            </div>
              
           <div class="form-group">
              <label>Sub Category:</label> 
              <input type="text" id="sub_cat" name="sub_name" placeholder="Enter sub category" required>
           </div>
              
            <div class="form-group">
              <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Add Sub Category</button>
            </div>
          </form>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px">Close</button>
        </div>
      </div>
      
    </div>
  </div>

  
</div>
   