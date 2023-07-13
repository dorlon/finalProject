<?php
    include_once "./header_html.php"; 
    include_once "./objects/dbClass.php";

    if (!isset($is_manager) || !$is_manager) {
      die("<h2 style='text-align:center;'>מצטערים, אך אין לך גישה לדף זה.</h2>");
    }

    $components = [];

    $db = new dbClass();

    $components=$db->selectComponentDetails();
     //$components['components_id'] = 3;
     //echo "<pre>";
     //print_r ($components);
     //echo "</pre>";
     //die("dfsd");
?>

<div class="container page-container">
  <div class="content-wrap">
    <div class="row">
        <div class="col-12 text-center">
            <h1>טבלת רכיבים</h1>
        </div>
    </div>

    <a class="btn" href="/final_project/add_component.php" role="button">
      <button type="button" id="add_component" class="btn btn-primary">הוסף רכיב</button>
    </a>

    <table class="table table-striped text-center">
      <thead>
        <tr>
          <th scope="col">מחק רכיב</th>
          <th scope="col">שם הרכיב</th>
          <th scope="col">כמות</th>
          <th scope="col">ערוך</th>
        </tr>
      </thead>

      <tbody>
      <?php
          foreach($components as $value){
        ?>
          <tr>
            <td><button class="deleteComponent btn btn-danger" data-id="<?php echo $value["component_id"]?>" type="button">X</button></td>
            <td><?php echo $value["component_name"] ?></td>
            <td><?php echo $value["amount"] ?></td>
            <td>
              <a href="/final_project/edit_component.php?component_id=<?=$value["component_id"]?>">
                <button type="button" class="btn btn-link" >
                  עריכת רכיב
                </button>
              </a>
            </td>
          </tr>
        <?php
          }
        ?>
      </tbody>
    </table>
  </div>
</div>


<?php
    include_once "./newfooter.php"; 
   ?>