<?php
    include_once "./header_html.php"; 
   ?>

<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h1>הוספת רכיבים</h1>
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
                  <input type="text" class="form-control" id="component_name">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label label-form" for="amount">כמות במלאי</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="amount">
                </div>
              </div>

              <div class="form-group">        
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="button" id="component" class="btn btn-success">אישור רכיב</button>
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