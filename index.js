//const { builtinModules } = require('module');

let total_amount = $('#total_amount').val();

paypal
  .Buttons({
    style: {
      color: 'blue',
      shape: 'pill',
    },
    createOrder: function (data, actions) {
      return actions.order.create({
        purchase_units: [
          {
            amount: {
              value: total_amount,
            },
          },
        ],
      });
    },
    onApprove: function (data, actions) {
      return actions.order.capture().then(function (details) {
        let status = details.status;
        if (status == 'COMPLETED') {
          let order_id = details.id;
          window.location.replace(
            './thank_you.php?total=' + total_amount + '&order_id=' + order_id
          );
        }
      });
    },
    onCancel: function (data) {
      console.log(data);
      window.location.replace('./onCancel.php');
    },
  })
  .render('#paypal-payment-button');
