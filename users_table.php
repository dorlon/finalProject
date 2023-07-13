<?php
    include_once "./header_html.php";
    include_once "./objects/dbClass.php";

  $users = [];

  if (!isset($is_manager) || !$is_manager) {
    die("<h2 style='text-align:center;'>מצטערים, אך אין לך גישה לדף זה.</h2>");
  }

  $db = new dbClass();

  $users=$db->selectUsersDetails();
?>

<div class="container page-container">
  <div class="content-wrap">
    <div class="row">
        <div class="col-12 text-center">
            <h1>טבלת משתמשים</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-10 text-center">
          <table class="table table-striped text-center">
            <thead>
              <tr>
                <th scope="col">מחק משתמש</th>
                <th scope="col">שם משתמש</th>
                <th scope="col">אימייל</th>
                <th scope="col">מספר טלפון</th>
                <th scope="col">כתובת</th>
                <th scope="col">תמונה</th>
                <th scope="col">תפקיד</th>
              </tr>
            </thead>

            <tbody>
            <?php
                foreach($users as $value){
              ?>
                <tr>
                  <td><button class="deleteUser btn btn-danger" data-id="<?php echo $value["user_id"]?>" type="button">X</button></td>
                  <td><?php echo $value["full_name"] ?></td>
                  <td><?php echo $value["email"] ?></td>
                  <td><?php echo $value["mobile"] ?></td>
                  <td><?php echo $value["city"]  ?></td>
                  <td><?php echo $value["user_picture"] ?></td>
                </tr>
              <?php
                }
              ?>
            </tbody>
          </table>
        </div>
    </div>
    
  </div>
</div>

<?php
    include_once "./newfooter.php"; 
?>