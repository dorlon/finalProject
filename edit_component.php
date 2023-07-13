<?php
    include_once "./header_html.php"; 

    $component_id = $_GET['component_id'];

    $component = new Component();


    $details = $component->getDetailsOfComponent($component_id);


    echo "<pre>";
    print_r($details);
    echo "</pre>";
   ?>

<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h1>עריכת רכיב</h1>
        </div>
    </div>
</div>

<div class="row">
        <div class="col-6">
          <div class="container">
            <form class="form-horizontal text-right">
              <div class="form-group">
                <label class="control-label label-form" for="component_name">שם רכיב</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="component_name" value="<?=$details[$component_id]["component_name"]?>">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label label-form" for="amount">כמות במלאי</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="amount" value="<?=$details[$component_id]["amount"]?>">
                </div>
              </div>

              <div class="form-group">        
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="button" id="edit-component" data-dbid="<?=$component_id?>" class="btn btn-success">אישור רכיב</button>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-danger">מחק רכיב</button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>
</div>



<?php
    include_once "./newfooter.php"; 
   ?>