<?php include_once "./functions.php"; ?>


<?php


function get_cart_items_by_user_id($user_id) {
    $db  = new dbClass();
    $all_items_in_cart = $db->get_items_in_cart($user_id);
    return $all_items_in_cart;
}








if(isset($_GET['add'])){
   $query = query("SELECT * FROM `products` WHERE product_id=" . escape_string($_GET['add']) . " ");
   confirm($query); 

   while($row = fetch_array($query)){

    if($row['product_quantity'] != $_SESSION['product_' . $_GET['add']]){
        $_SESSION['product_' . $_GET['add']] += 1;
        redirect("/final_project/checkout.php");
    }

    else{
        set_message("We only have " . $row['product_quantity'] . " " . "{$row['product_title']}" . " available");
        redirect("/final_project/checkout.php");
    }
   }
}

if(isset($_GET['remove'])){

    $_SESSION['product_' . $_GET['remove']]--;

    if($_SESSION['product_' . $_GET['remove']] < 1){
        unset($_SESSION['item_total']);
        unset($_SESSION['item_quantity']);
        redirect("/final_project/checkout.php");
    }
    else{
      redirect("/final_project/checkout.php");  
    }
    
}

if(isset($_GET['delete'])){

    $_SESSION['product_' . $_GET['delete']] = '0';
    unset($_SESSION['item_total']);
    unset($_SESSION['item_quantity']);

    redirect("/final_project/checkout.php");
}

function cart(){

    $total = 0;
    $item_quantity = 0; 
    $item_name = 1;//1=default value
    $item_number = 1;
    $amount = 1;
    $quantity = 1;
    foreach($_SESSION as $name => $value){

        if($value > 0){

            if(substr($name, 0, 8) == "product_"){

                $length = strlen($name - 8);
                $id = substr($name, 8, $length);
                $query = query("SELECT * FROM products WHERE productd_id = " . escape_string($id) . " ");
                confirm($query);

                while($row = fetch_array($query)){
                    $sub = $row['product_price'] * $value;
                    $item_quantity += $value;

                    $product = <<<DELIMETER
                    <tr>
                        <td><img src="/final_project/images/cart-white.svg" alt=""></td>
                        <td>{$row['product_title']}</td>
                        <td>&#8362;{$row['product_price']}</td>
                        <td>$value</td>
                        <td>&#8362;{$sub}</td>
                        <td><a class='btn btn-success' href="cart.php?add={$row['product_id']}">svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                      </svg></a></td>
                        <td><a class='btn btn-warning' href="cart.php?remove={$row['product_id']}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                        <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                      </svg></a></td>
                        <td><a class='btn btn-danger' href="cart.php?delete={$row['product_id']}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                      </svg></a></td>
                    </tr>
                    <input type="hidden" name="item_name_{$item_name}" value="{$row['product_title']}"> 
                    <input type="hidden" name="item_number_{$item_number}" value="{$row['product_id']}"> 
                    <input type="hidden" name="amount_{$amount}" value="{$row['product_price']}">
                    <input type="hidden" name="amount_{$quantity}" value="{$value}">
                    DELIMETER; 
    
                    echo $product;

                    $item_name++;
                    $item_number++;
                    $amount++;
                    $quantity++;

                } 
                $_SESSION['item_total'] = $total += $sub;
                $_SESSION['item_quantity'] = $item_quantity;
            }
        }
    }
}
?>