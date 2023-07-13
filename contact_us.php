<?php
    include_once "./header_html.php"; 
?>

<div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
    <form action="/action_page.php" style="text-align:right;">
  <div class="form-group">
    <label for="email">שם מלא</label>
    <input type="text" class="form-control"  id="fullname">
  </div>
  <div class="form-group">
    <label for="pwd">מייל</label>
    <input type="email" class="form-control"  id="email">
  </div>

  <div class="form-group">
  <label for="comment">תוכן ההודעה</label>
  <textarea class="form-control" rows="5" id="comment"></textarea>
</div>
  <button type="button" class="btn btn-primary" id="send_contacts">שלח</button>
</form>
    </div>
    <div class="col-sm-4"></div>
</div>

<script>

$(document).ready(function(){
    $("#send_contacts").on("click",function(){
        let fullname = $("#fullname").val().trim();
        let email = $("#email").val().trim();
        let comment = $("#comment").val().trim();



        $.post(
      'server/action.php',
      { action: 'send_contact', fullname: fullname,email:email,comment:comment },
      null,
      'json'
    )
      .done(function (res) {
        if (res.type) {
            $("#fullname").val('');
            $("#email").val('');
            $("#comment").val('');
            Swal.fire(
            'ההודעה נשלחה בהצלחה',
            'תשובה תמסר תוך מספר ימים לכתובת המייל שהזנת',
            'success'
            )            

        }
        

      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      });


    })
})


</script>



<?php
    include_once "./newfooter.php"; 
?>