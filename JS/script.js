$(document).ready(function () {
  $('body').on('click', '.plus_item', function () {
    var id_cart_item = $(this).data('id');
    var product_id = $(this).attr('data-product-id');

    let curr_obj_qnt = $(`.quantity_item[data-id='${id_cart_item}']`);
    let curr_obj_total = $(`.total_item[data-id='${id_cart_item}']`);
    let price_item = parseInt(
      $(`.price_item[data-id='${id_cart_item}']`).text()
    );
    var current_qnt = parseInt(curr_obj_qnt.text());

    $.post(
      'server/action.php',
      {
        action: 'add_item_in_cart',
        id_cart_item: id_cart_item,
        product_id: product_id,
      },
      null,
      'json'
    )
      .done(function (res) {
        console.log(res);
        if (res.flag == 1) {
          current_qnt++;
          curr_obj_qnt.text(current_qnt);
          let new_total = current_qnt * price_item;
          $(`.total_item[data-id='${id_cart_item}']`).text(new_total);
          $('#total_amount').val(new_total);
        } else {
          alert(res.msg);
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      });
  });

  $('body').on('click', '.minus_item', function () {
    var id_cart_item = $(this).data('id');

    let curr_obj_qnt = $(`.quantity_item[data-id='${id_cart_item}']`);
    let curr_obj_total = $(`.total_item[data-id='${id_cart_item}']`);
    let price_item = parseInt(
      $(`.price_item[data-id='${id_cart_item}']`).text()
    );
    var current_qnt = parseInt(curr_obj_qnt.text());

    $.post(
      'server/action.php',
      { action: 'erase_item_in_cart', id_cart_item: id_cart_item },
      null,
      'json'
    )
      .done(function (res) {
        console.log(res);
        if (res.flag == 1) {
          current_qnt--;

          if (current_qnt <= 0) {
            $(`.tr-items-cart[data-table-id='${id_cart_item}']`).remove();
            return;
          }

          curr_obj_qnt.text(current_qnt);
          let new_total = current_qnt * price_item;
          $(`.total_item[data-id='${id_cart_item}']`).text(new_total);
          $('#total_amount').val(new_total);
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      });
  });

  //delete product from cart
  $('body').on('click', '.delete_item', function () {
    var id_cart_item = $(this).data('id');

    let curr_obj_qnt = $(`.quantity_item[data-id='${id_cart_item}']`);
    let curr_obj_total = $(`.total_item[data-id='${id_cart_item}']`);
    let price_item = parseInt(
      $(`.price_item[data-id='${id_cart_item}']`).text()
    );
    var current_qnt = parseInt(curr_obj_qnt.text());

    // create ajax requset
    $.post(
      'server/action.php',
      { action: 'delete_item_in_cart', id_cart_item: id_cart_item },
      null,
      'json'
    )
      .done(function (res) {
        if (res.type == 'fail') {
          sweetAlert(
            'error',
            'התרחשה שגיאה',
            'קרתה בעיית שרת, המוצר אינו נמחק מהעגלה. צור קשר עם מנהל האתר'
          );
          return;
        }

        if (res.type == 'success') {
          sweetAlert('success', 'הפעולה הצליחה', 'המוצר הוסר מהעגלה').then(
            (result) => {
              window.location.href =
                'http://localhost/final_project/checkout.php';
              return;
            }
          );
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      });
  });

  //Signing new customer to the system
  $('#login').on('click', function () {
    var email = $('#email_con').val().trim();
    var password = $('#password_con').val().trim();
    var type_user = '';

    var login_user = {
      email: {
        name: 'אימייל משתמש',
        val: email,
      },
      password: {
        name: 'סיסמה',
        val: password,
      },
    };

    var error_message = '';
    //loop for, empty fields
    for (var key in login_user) {
      if (ifEmpty(login_user[key].val)) {
        error_message += login_user[key].name + '<br>';
      }
    }

    if (error_message != '') {
      sweetAlert('error', 'השדות הללו ריקים', error_message);
      return;
    }

    login_user.action = 'check_login_details';
    login_user.key_type_user = type_user;

    // create ajax requset
    $.post('server/action.php', login_user, null, 'json')
      .done(function (res) {
        var strToShow = buildStrToShow(res.msg);
        // אם הפרטים נכונים הצג הודעה של הצלחת התחברות ותעבור לדף הבית או אחרי 5 שניות או אחרי לחיצה על לחצן אוקיי
        if (res.type == 'success') {
          sweetAlert('success', 'הפעולה הצליחה', strToShow).then((result) => {
            if (res.msg[0] == 'המנהל מגיע') {
              window.location.href =
                'http://localhost/final_project/index_html.php';
            } else {
              window.location.href = 'http://localhost/final_project/shop.php';
            }
          });
          // setTimeout(function () {
          //   window.location.href =
          //     'http://localhost/final_project/index_html.php';
          // }, 5000);
        }
        // if (res.type == "success_meneger") {
        //   sweetAlert("success", "הפעולה הצליחה", strToShow).then((result) => {
        //     window.location.href = "http://localhost/finelProjectProgram/manager-control.php";
        //   });
        //   setTimeout(function () {
        //     window.location.href = "http://localhost/finelProjectProgram/manager-control.php";
        //   }, 5000);
        // }
        // אם הפרטים שגויים הצג הודעת שגיאה
        if (res.type == 'error') {
          sweetAlert('error', 'התרחשה שגיאה', strToShow);
          return;
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      });
  });

  // Disconnect user from the system
  $('#disconnect').on('click', function () {
    var logout = {
      action: 'logout',
    };

    // create ajax requset
    $.post('server/action.php', logout, null, 'json')
      .done(function (res) {
        console.log(res);

        var strToShow = buildStrToShow(res.msg);

        if (res.type == 'success') {
          sweetAlert('success', 'הפעולה הצליחה', strToShow).then((result) => {
            window.location.href =
              'http://localhost/final_project/index_html.php';
            return;
          });
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
        console.log('נופל!!!!');
      });
  });

  $('#register').on('click', function () {
    var user_name = $('#full_name').val().trim();
    var email = $('#email').val().trim();
    var password = $('#password').val().trim();
    var verify_password = $('#verify_password').val().trim();
    var mobile = $('#mobile').val().trim();
    var city = $('#city').val().trim();
    var street = $('#street').val().trim();
    var number_house = $('#number_house').val().trim();
    //alert(user_name +"\n"+email+"\n"+password+"\n"+verify_password
    //+"\n"+mobile+"\n"+city+"\n"+street+"\n"+number_house);

    //User object details
    var register_user = {
      user_name: {
        name: 'שם מלא',
        val: user_name,
      },
      email: {
        name: 'אימייל משתמש',
        val: email,
      },
      password: {
        name: 'סיסמה',
        val: password,
      },
      verify_password: {
        name: 'אימות סיסמה',
        val: verify_password,
      },
      mobile: {
        name: 'מספר טלפון',
        val: mobile,
      },
      city: {
        name: 'עיר',
        val: city,
      },
      street: {
        name: 'רחוב',
        val: street,
      },
      number_house: {
        name: 'מספר בית',
        val: number_house,
      },
    };
    var error_message = '';
    //loop for, empty fields
    for (var key in register_user) {
      if (ifEmpty(register_user[key].val)) {
        error_message += register_user[key].name + '<br>';
      }
    }

    //'if'(s) of alerts empty fields
    //console.log(error_message);

    if (password != verify_password) {
      sweetAlert('error', 'הסיסמאות חיבות להיות תואמות', 'הסיסמאות לא תואמות');
      return;
    }

    if (mobile.length != 10) {
      sweetAlert(
        'error',
        'מספר טלפון לא חוקי',
        'מספר טלפון חייב להיות בן 10 ספרות'
      );
      return;
    }

    if (number_house < 1) {
      sweetAlert('error', 'מספר בית לא חוקי', 'מספר בית חייב להיות גדול מ1');
      return;
    }

    if (error_message != '') {
      sweetAlert('error', 'השדות הללו ריקים', error_message);
      return;
    }
    //if there is no empty fields, 'register_user' object translate to action.php
    register_user.action = 'add-new-user';
    // console.log(register_user);
    // create ajax
    $.post('server/action.php', register_user, null, 'json')
      .done(function (res) {
        var strToShow = buildStrToShow(res.msg);
        if (res.type == 'error') {
          sweetAlert('error', 'התרחשה שגיאה', strToShow);
          return;
        }

        if (res.type == 'success') {
          //alert('1');
          var form_data = new FormData();
          // form_data.append('file', file_data);
          $.ajax({
            url: 'server/action.php?action=add-new-user', // point to server-side PHP script
            dataType: 'text', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (php_script_response) {
              sweetAlert('success', 'הפעולה הצליחה', strToShow).then(
                (result) => {
                  window.location.href = 'index_html.php'; //after success of adding object new user, web locate to homepage
                  return;
                }
              );
            },
          });
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log('textStatus= ' + textStatus);
        console.log(errorThrown);
      });
  });

  $('.edit_user').on('click', function () {});

  //delete user
  $('.deleteUser').on('click', function () {
    var user_id = $(this).data('id');
    alert(user_id);
    console.log(user_id);

    var deleteUser = {
      action: 'deleteUser',
      id: user_id,
    };

    // create ajax requset
    $.post('server/action.php', deleteUser, null, 'json')
      .done(function (res) {
        if (res.type == 'fail') {
          sweetAlert(
            'error',
            'התרחשה שגיאה',
            'קרתה בעיית שרת, המשתמש אינו נמחק. צור קשר עם מנהל האתר'
          );
          return;
        }

        if (res.type == 'success') {
          sweetAlert('success', 'הפעולה הצליחה', 'המשתמש הוסר מהמערכת').then(
            (result) => {
              window.location.href =
                'http://localhost/final_project/users_table.php';
              return;
            }
          );
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      });
  });

  $('#edit-component').on('click', function () {
    var component_name = $('#component_name').val().trim();
    var amount = $('#amount').val().trim();
    var id_comp = $(this).attr('data-dbid');

    var add_new_component = {
      component_name: {
        name: 'שם רכיב',
        val: component_name,
      },
      amount: {
        name: 'כמות',
        val: amount,
      },

      id: {
        name: 'מזהה',
        val: id_comp,
      },
    };
    var current_amount = parseInt(amount);
    var error_message = '';
    for (var key in add_new_component) {
      if (ifEmpty(add_new_component[key].val)) {
        error_message += add_new_component[key].name + '<br>';
      }
    }
    console.log(error_message);

    if (current_amount <= 0 || isNaN(current_amount)) {
      sweetAlert('error', 'התרחשה שגיאה', 'כמות במלאי חייבת להיות מעל 0');
      return;
    } else if (error_message != '') {
      sweetAlert('error', 'השדות הללו ריקים', error_message);
      return;
    }

    add_new_component.action = 'edit-component';

    console.log(add_new_component);
    // create ajax
    $.post('server/action.php', add_new_component, null, 'json')
      .done(function (res) {
        var strToShow = buildStrToShow(res.msg);
        if (res.type == 'error') {
          sweetAlert('error', 'התרחשה שגיאה', strToShow);
          return;
        }

        if (res.type == 'success') {
          var form_data = new FormData();
          // form_data.append('file', file_data);
          $.ajax({
            url: 'server/action.php?action=add-new-component', // point to server-side PHP script
            dataType: 'text', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (php_script_response) {
              sweetAlert('success', 'רכיב נערך בהצלחה', strToShow).then(
                (result) => {
                  window.location.href = 'components_table.php';
                  return;
                }
              );
            },
          });
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log('textStatus= ' + textStatus);
        console.log(errorThrown);
      });
  });

  //Add new component
  $('#component').on('click', function () {
    //alert("success");
    var component_name = $('#component_name').val().trim();
    var amount = $('#amount').val().trim();

    var add_new_component = {
      component_name: {
        name: 'שם רכיב',
        val: component_name,
      },
      amount: {
        name: 'כמות',
        val: amount,
      },
    };
    var current_amount = parseInt(amount);
    var error_message = '';
    for (var key in add_new_component) {
      if (ifEmpty(add_new_component[key].val)) {
        error_message += add_new_component[key].name + '<br>';
      }
    }
    console.log(error_message);

    if (current_amount <= 0 || isNaN(current_amount)) {
      sweetAlert('error', 'התרחשה שגיאה', 'כמות במלאי חייבת להיות מעל 0');
      return;
    } else if (error_message != '') {
      sweetAlert('error', 'השדות הללו ריקים', error_message);
      return;
    }

    add_new_component.action = 'add-new-component';
    console.log(add_new_component);
    // create ajax
    $.post('server/action.php', add_new_component, null, 'json')
      .done(function (res) {
        var strToShow = buildStrToShow(res.msg);
        if (res.type == 'error') {
          sweetAlert('error', 'התרחשה שגיאה', strToShow);
          return;
        }

        if (res.type == 'success') {
          var form_data = new FormData();
          // form_data.append('file', file_data);
          $.ajax({
            url: 'server/action.php?action=add-new-component', // point to server-side PHP script
            dataType: 'text', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (php_script_response) {
              sweetAlert('success', 'רכיב נוסף בהצלחה', strToShow).then(
                (result) => {
                  window.location.href = 'components_table.php';
                  return;
                }
              );
            },
          });
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log('textStatus= ' + textStatus);
        console.log(errorThrown);
      });
  });

  //delete component
  $('.deleteComponent').on('click', function () {
    var component_id = $(this).data('id');

    var deleteComponent = {
      action: 'deleteComponent',
      id: component_id,
    };

    // create ajax requset
    $.post('server/action.php', deleteComponent, null, 'json')
      .done(function (res) {
        if (res.type == 'fail') {
          sweetAlert(
            'error',
            'התרחשה שגיאה',
            'קרתה בעיית שרת, המרכיב אינו נמחק. צור קשר עם מנהל האתר'
          );
          return;
        }

        if (res.type == 'success') {
          sweetAlert('success', 'הפעולה הצליחה', 'המרכיב הוסר מהמערכת').then(
            (result) => {
              window.location.href =
                'http://localhost/final_project/components_table.php';
              return;
            }
          );
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      });
  });

  $('body').on('click', '.deleteComponentInNewProduct', function () {
    var comp_id = $(this).data('comp');
    $(".tr-comp[data-comp='" + comp_id + "']").remove();
  });

  $('.btn-remove-compoment-from-product').on('click', function () {
    var id_row = $(this).data('id-row');
    var product_id = $(this).data('product-id');

    $.post('server/action.php', {
      product_id: product_id,
      action: 'remove-compoment-from-exists-product',
      id_row: id_row,
    }).done(function () {
      location.reload();
    });
  });

  $('.add-component-in-product-btn').on('click', function () {
    var value_id = $('#select_component_in_product option:selected').val();
    var amount = $('#count_component_in_product').val();
    var product_id = $(this).data('product-id');

    // להוסיף בדיקה שמדובר במספר, לא גדול מ X לא קטן מ 1

    $.post('server/action.php', {
      product_id: product_id,
      action: 'add-compoment-to-exists-product',
      component_id: value_id,
      component_amount: amount,
    }).done(function () {
      location.reload();
    });
  });

  $('.add-component-btn').on('click', function () {
    var comp_id = $('#select_components option:selected').val();
    var comp_name = $('#select_components option:selected').text();
    var amount = $('#count').val();
    var is_exists = $(`.tr-comp[data-comp="${comp_id}"`).length;
    if (is_exists == 0 && amount > 0) {
      var tr = `<tr class='tr-comp' data-comp='${comp_id}'>
          <td>${comp_name}</td>
          <td class='comp-qnt'>${amount}</td>
          <td><button class="deleteComponentInNewProduct btn btn-danger" data-comp='${comp_id}' type="button">X</button></td>
          </tr>`;
      console.log(tr);
      $('#table-components tbody').append(tr);
    } else {
      var newValue =
        parseInt($(`.tr-comp[data-comp="${comp_id}"] td.comp-qnt`).text()) +
        parseInt(amount);

      if (newValue <= 0) {
        alert('כמות הרכיב לא יכולה להיות קטנה מ 0');
      } else {
        $(`.tr-comp[data-comp="${comp_id}"] td.comp-qnt`).text(newValue);
      }
    }

    $('#table-components').show();
  });

  //Add new product
  $('#add_product_form').on('submit', function (e) {
    console.log('#add_product_form.onSubmit');
    e.preventDefault();
    var product_name = $('#product_name').val().trim();
    var product_price = $('#product_price').val().trim();
    var product_description = $('#product_description').val().trim();
    var product_picture = $('#product_picture').val().trim();
    var comp_obj = [];

    var tr_comp = $('.tr-comp');
    $.each(tr_comp, function (key, value) {
      var comp_id = $(value).data('comp');
      var amount = parseInt(
        $(`.tr-comp[data-comp="${comp_id}"] .comp-qnt`).text()
      );

      comp_obj.push({ comp_id: comp_id, amount: amount });
    });

    if (comp_obj.length == 0) {
      sweetAlert(
        'error',
        'התרחשה שגיאה',
        'לא ניתן להוסיף מוצר חדש כאשר לא הוכנס לפחות רכיב אחד'
      ).then((result) => {
        return;
      });
      return;
    }

    var products = {
      product_name: {
        name: 'שם מוצר',
        val: product_name,
      },
      product_price: {
        name: 'מחיר מוצר',
        val: product_price,
      },
      product_description: {
        name: 'תיאור מוצר',
        val: product_description,
      },
      product_picture: {
        name: 'תמונת מוצר',
        val: product_picture,
      },
    };

    var error_message = '';
    for (var key in products) {
      if (ifEmpty(products[key].val)) {
        error_message += products[key].name + '<br>';
      }
    }
    //console.log(error_message);
    if (error_message != '') {
      sweetAlert('error', 'השדות הללו ריקים', error_message);
      flag = 1;
      return;
    }
    if (product_name.length > 20) {
      sweetAlert(
        'error',
        'בעיה בשם מוצר',
        'השם ארוך מידי, הכנס פחות מ-20 תווים'
      );
      flag = 1;
      return;
    }
    if (product_description.length > 50) {
      sweetAlert(
        'error',
        'בעיה בתיאור מוצר',
        'ההסבר ארוך מידי, הכנס פחות מ-50 תווים'
      );
      flag = 1;
      return;
    }
    if (product_price < 1) {
      sweetAlert('error', 'מחיר לא חוקי', 'הכנס מספר מחיר חיובי גדול מ-0');
      flag = 1;
      return;
    }

    products.action = 'add-new-product';

    // create ajax
    $.ajax({
      url: 'server/action.php?action=add-new-product',
      type: 'POST',
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function (res) {
        let json_res = JSON.parse(res);
        let product_id = json_res.last_id;
        if (json_res.type == 'success') {
          $.post('server/action.php', {
            product_id: product_id,
            action: 'add-compoment-to-product',
            compoment: comp_obj,
          }).done(function () {
            var strToShow = buildStrToShow(json_res.msg);
            sweetAlert('success', 'מוצר נוסף בהצלחה', strToShow).then(
              (result) => {
                window.location.href =
                  'http://localhost/final_project/shop.php';
                return;
              }
            );
          });
        } else {
          sweetAlert('error', 'התרחשה שגיאה', json_res.msg[0]);
        }
      },
    });
    // $.post('server/action.php', products, null, 'json')
    //   .done(function (res) {
    //     var strToShow = buildStrToShow(res.msg);
    //     if (res.type == 'error') {
    //       sweetAlert('error', 'התרחשה שגיאה', strToShow);
    //       return;
    //     }
    //     if (res.type == 'success') {
    //       sweetAlert('success', 'מוצר נוסף בהצלחה', strToShow).then(
    //         (result) => {
    //           window.location.href = 'shop.php';
    //           return;
    //         }
    //       );
    // var form_data = new FormData();
    // form_data.append('file', file_data);
    // $.ajax({
    //   url: 'server/action.php?action=add-new-product', // point to server-side PHP script
    //   dataType: 'text', // what to expect back from the PHP script, if anything
    //   cache: false,
    //   contentType: false,
    //   processData: false,
    //   data: form_data,
    //   type: 'post',
    //   success: function (php_script_response) {
    //     sweetAlert('success', 'מוצר נוסף בהצלחה', strToShow).then(
    //       (result) => {
    //         window.location.href = 'shop.php';
    //         return;
    //       }
    //     );
    //   },
    // });
    // },
    // }).fail(function (jqXHR, textStatus, errorThrown) {
    //   console.log(jqXHR);
    //   console.log('textStatus= ' + textStatus);
    //   console.log(errorThrown);
    // });
  });

  $('#five_cakes_sold').on('click', function () {
    Swal.fire({
      title: '<strong>נא לבחור כמות להצגה</strong>',
      icon: 'info',
      html: `<p>יש לכתוב את הכמות שנדרשה להצגת הסטטיסטיקה</p>
        <input type="number" class="form-control" id="num_to_show_five_cakes">
        `,
      showCloseButton: true,
      showCancelButton: true,
      focusConfirm: false,
      confirmButtonText: 'המשך',
      confirmButtonAriaLabel: 'Thumbs up, great!',
      cancelButtonText: 'ביטול',
      cancelButtonAriaLabel: 'Thumbs down',
    }).then((result) => {
      if (result.isConfirmed) {
        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();
        let num_to_show = $('#num_to_show_five_cakes').val();
        obj = {
          action: 'get_five_cakes_sold',
          start: start_date,
          end: end_date,
          num_to_show: num_to_show,
        };

        $.post('server/action.php', obj, null, 'json')
          .done(function (res) {
            let html = ``;

            $.each(res, function (key, value) {
              html += `<tr>
                      <td>${value.product_name}</td>
                      <td>${value.qnt}</td>
              </tr>`;
            });

            $('#five_cakes_sold_tbl tbody').html(html);
            $('#five_cakes_sold_tbl').show();
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
          });
      }
    });
  });

  //delete product
  $('.deleteProduct').on('click', function () {
    var product_id = $(this).data('id');

    var deleteProduct = {
      action: 'deleteProduct',
      id: product_id,
    };

    // create ajax requset
    $.post('server/action.php', deleteProduct, null, 'json')
      .done(function (res) {
        if (res.type == 'fail') {
          sweetAlert(
            'error',
            'התרחשה שגיאה',
            'קרתה בעיית שרת, המוצר אינו נמחק. צור קשר עם מנהל האתר'
          );
          return;
        }

        if (res.type == 'success') {
          sweetAlert('success', 'הפעולה הצליחה', 'המוצר הוסר מהמערכת').then(
            (result) => {
              window.location.href = 'http://localhost/final_project/shop.php';
              return;
            }
          );
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      });
  });

  //delete order
  $('.deleteOrder').on('click', function () {
    var id_order = $(this).data('id');

    var deleteOrder = {
      action: 'deleteOrder',
      id: id_order,
    };

    // create ajax requset
    $.post('server/action.php', deleteOrder, null, 'json')
      .done(function (res) {
        if (res.type == 'fail') {
          sweetAlert(
            'error',
            'התרחשה שגיאה',
            'קרתה בעיית שרת, ההזמנה אינה נמחקת. צור קשר עם מנהל האתר'
          );
          return;
        }

        if (res.type == 'success') {
          sweetAlert('success', 'הפעולה הצליחה', 'ההזמנה הוסרה מהמערכת').then(
            (result) => {
              window.location.href =
                'http://localhost/final_project/orders.php';
              return;
            }
          );
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      });
  });

  //Add new order
  $('#order').on('click', function () {
    var user_order = $('#user_order').val().trim();
    var address = $('#address').val().trim();
    var tottal_price = $('#tottal_price').val().trim();
    var delivery_date = $('#delivery_date').val().trim();
    var order_status = $('#order_status').val().trim();
    var download_order = $('#download_order').val().trim();

    alert('success');
    var orders = {
      user_order: {
        name: 'שם המזמין',
        val: user_order,
      },
      address: {
        name: 'כתובת',
        val: address,
      },
      tottal_price: {
        name: 'סה"כ מחיר',
        val: tottal_price,
      },
      delivery_date: {
        name: 'תאריך משלוח',
        val: delivery_date,
      },
      order_status: {
        name: 'סטטוס הזמנה',
        val: order_status,
      },
      download_order: {
        name: 'הורד הזמנה',
        val: download_order,
      },
    };

    var error_message = '';
    for (var key in orders) {
      if (ifEmpty(orders[key].val)) {
        error_message += orders[key].name + '<br>';
      }
    }

    console.log(error_message);
    if (error_message != '') {
      sweetAlert('error', 'השדות הללו ריקים', error_message);
      return;
    }
  });

  function ifEmpty(str = '') {
    if (str.length == 0) {
      return true;
    }
    return false;
  }

  //presenting alert
  function sweetAlert(icon, title, text) {
    return Swal.fire({
      icon: icon,
      title: title,
      html: text,
    });
  }

  //function linking string
  function buildStrToShow(arr_msg) {
    var str = '';
    for (var key in arr_msg) {
      str += arr_msg[key] + '<br>';
    }

    return str;
  }
});
