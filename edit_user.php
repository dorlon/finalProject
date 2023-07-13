<?php
    include_once "./header_html.php"; 
    $user_id = $_SESSION['user_id'];
    $user = new User();
    $details = $user->getDetailsOfUser($user_id);

    


?>

<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h1>עריכת פרטי משתמש</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
          <div class="row">
            <div class="col-12">
              <div class="cake-photo-upload" >
                <img  id="box-img" src="<?=$details[$user_id]["user_picture"]?>" alt="" style="    max-width: 245px;">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <label>להעלאת תמונה</label>
              <input class="form-control" type="file" id="user_picture" multiple />
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="container">
            <form class="form-horizontal text-right" action="/action_page.php">
              <div class="form-group">
                <label class="control-label label-form" for="userName">שם מלא</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="userName" value="<?=$details[$user_id]["full_name"]?>">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label label-form" for="email">אימייל</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control disabled" disabled value="<?=$details[$user_id]["email"]?>" id="email">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label label-form" for="password">סיסמה</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" value="" id="password">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label label-form" for="city">עיר</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" value="<?=$details[$user_id]["city"]?>" id="city">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label label-form" for="street">רחוב</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" value="<?=$details[$user_id]["street"]?>" id="street">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label label-form" for="numberOfHouse">מספר בית</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" value="<?=$details[$user_id]["numberOfHouse"]?>" id="numberOfHouse">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label label-form" for="mobile">מספר להתקשרות</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" value="<?=$details[$user_id]["mobile"]?>" id="mobile">
                </div>
              </div>
              <div class="form-group">        
              <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-success">אשר שינויים</button>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="button" class="btn btn-danger" id="delete_my_user">הסר את המשתמש שלי</button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>
</div>

<script>


$('#user_picture').change( function(event) {

              var tmppath = URL.createObjectURL(event.target.files[0]);
              $("#box-img").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
              //$("#disp_tmp_path").html("Temporary Path(Copy it and try pasting it in browser address bar) --> <strong>["+tmppath+"]</strong>");
              });



$("#delete_my_user").on("click",function(){


  $.post(
      'server/action.php',
      { action: 'remove_my_account'},
      null,
      'json'
  ).done(function(){

    window.location.href = "index_html.php";

  })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      });




});


</script>

<?php
    include_once "./newfooter.php"; 
   ?>