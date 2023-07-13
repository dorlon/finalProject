<?php
    include_once "./header_html.php"; 

    if (!isset($is_manager) || !$is_manager) {
        die("<h2 style='text-align:center;'>מצטערים, אך אין לך גישה לדף זה.</h2>");
      }
?>

<form>
    <div class="form-group">
    <label for="usr">תחילת תאריך</label>
    <input type="date" class="form-control" id="start_date">
    </div>
    <div class="form-group">
    <label for="pwd">סיום תאריך</label>
    <input type="date" class="form-control" id="end_date">
    </div>
</form>
<br>

<button type="button" class="btn btn-secondary" id="five_cakes_sold">עוגות הכי נבחרות</button>
<button type="button" class="btn btn-success" id="general_sold">מכירות כללי</button>
<button type="button" class="btn btn-info" id="component_sold">רכיבים הכי נמכרים</button>




<div class='container'>
    <dic class='row'>
        
        <table class="table" style="display:none;" id="five_cakes_sold_tbl">
        <thead>
        <tr>
            <th>שם מוצר</th>
            <th>כמות שנמכרה</th>

        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    </div>
</div>







<?php
    include_once "./newfooter.php"; 
   ?>