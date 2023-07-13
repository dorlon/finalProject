<?php
Class dbClass{
    private $host;
    private $db;
    private $charset;
    private $user;
    private $pass;
    private $opt = array(
    PDO::ATTR_ERRMODE      => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC);
    private $connection;

    public function __construct(string $host="localhost", string $db="confectionery", string $charset= "utf8", string $user= "root", string $pass= "")
    {
        $this->host = $host;
        $this->db = $db;
        $this->charset = $charset;
        $this->user = $user;
        $this->pass = $pass;
    }

    private function connect(){
        $dns ="mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
    
        $this->connection=new PDO($dns,$this->user,$this->pass,$this->opt);
    }

    public function disconnect(){
        $this->connection = null;
    }

    public function showUserDetails($user_id){
        
    }
	
	    public function getDetailsOfUserByOrder_id($order_id) {

														   
        try{
            $this->connect();
            $sql = "SELECT full_name,email FROM user inner join orders on orders.user_id = user.user_id where id_order = ?";
							  
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$order_id]);

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
         
               return $row;								  
            }
            
            return  false;

        }catch(Exception $e){
           
            return false;
        }


    }

    public function getUserDetailsForMail($user_id) {
        try{
            $this->connect();
            $sql = "SELECT full_name,email FROM user where user_id = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$user_id]);

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
         
               return $row;								  
            }
            
            return  false;

        }catch(Exception $e){
           
            return false;
        }

    }


    public function checkExistsUser($email) {

        try {
            $this->connect();
            $sql = "SELECT count(user_id) as cnt FROM user 
            WHERE email = ? ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$email]);
            $this->disconnect();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $count = $row['cnt'];
                if ($count > 0) return true;
            }
            return false;

        }
        catch(Exception $e) {
            return true;
        }


    }

    public function insertNewUser($register_user){

        if ($this->checkExistsUser($register_user->getEmail())) {
            return["flag"=>-1];
        }

        try{
            $this->connect();
            $sql = "INSERT INTO user (full_name,email,`password`,mobile,city,street,number_house,user_picture,user_type) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$register_user->getFull_name(),$register_user->getEmail(), $register_user->getPassword(),$register_user->getMobile(),$register_user->getCity(),$register_user->getStreet(),$register_user->getHouse_number(), $register_user->getUser_picture(), $register_user->getUser_type()]);
            $last_id = $this->connection->lastInsertId();
            $this->disconnect();
            return["flag"=>1, "lastInsertId" => $last_id];
        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }
    }

    public function insertNewOrder($order_id_paypal, $user_id, $total_price, $delivery_date) {
        try{
            $this->connect();
            $sql = "INSERT INTO orders (order_id_paypal,user_id,total_price,delivery_date) VALUES (?,?,?,?)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$order_id_paypal,$user_id, $total_price,$delivery_date]);
            $last_id = $this->connection->lastInsertId();
            $this->disconnect();
            return["flag"=>1, "last_id" => $last_id];
        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }

    }


    public function selectAllComponents() {


        $all_components = [];
        try{
            $this->connect();
            $sql = "SELECT * FROM components";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([]);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $all_components [] = $row;									  
            }
            
            return  $all_components;

        }catch(Exception $e){
            return false;
        }



    }


    public function deleteCartAfterFinish($user_id) {

        try{
            $this->connect();
            $sql = "DELETE FROM cart_items WHERE user_id = ? ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$user_id]);
            return["flag"=>1];
        

        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }

    }


    public function getQntOfCart($user_id) {
        $qnt = 0;
        try{
            $this->connect();
            $sql = "SELECT sum(quantity) as qnt FROM `cart_items` where user_id = ?";
   
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$user_id]);
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $qnt = $row['qnt'];
            }
            return $qnt;

        }catch(Exception $e){
            return 0;
        }


    }

    public function getAllProductsPerOrderId($paypal_orderid) {

        try{
            $this->connect();
            $sql = "SELECT products.product_name ,orders_parts.price, orders_parts.qnt, orders_parts.total FROM  `orders` 
            INNER JOIN orders_parts ON orders.id_order = orders_parts.order_id 
            INNER JOIN products ON orders_parts.product_id = products.product_id
            WHERE orders.order_id_paypal = ?";
   
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$paypal_orderid]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $total_array [] = $row;
				  
            }
            return $total_array;

        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }

    }

    public function getAllOrdersForAdmin() {

        $total_array = [];
        try{
            $this->connect();
            $sql = "SELECT * FROM `orders` INNER JOIN user ON orders.user_id = user.user_id WHERE order_status != 'X' ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $total_array [] = ["fullname" => $row['full_name'], "address" => $row['city'].', '.$row['street'].
                ' '.$row['number_house'], "total_price" => $row['total_price'], "status" => $row['order_status'] ,
                 "order_date" => $row['delivery_date'], "order_id_paypal" => $row['order_id_paypal'],
                 "id_order" => $row['id_order']  ];
				  
            }
            return $total_array;

        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }


    }

    public function getAllOrdersPerUserId($user_id) {

        $total_array = [];

        try{
            $this->connect();
            $sql = "SELECT * FROM `orders`
            inner join orders_parts on orders.id_order = orders_parts.order_id
            inner join products on orders_parts.product_id = products.product_id
            where user_id = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$user_id]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $id_order = $row['id_order'];
                if (!isset($total_array[$id_order])) {
                    $total_array[$id_order] = [
                            "id_order" => $id_order,
                            "paypal_order_id" => $row['order_id_paypal'],
                            "total_price" => $row['total_price'],
                            "order_status" => $row['order_status'],
                            "items" => []

                    ];
                }

                $total_array[$order_id]["items"][] = [
                    "product_id" => $row['product_id'],
                    "qnt" => $row['qnt'],
                    "price" => $row['price'],
                    "total" => $row['total'],
                    "product_name" => $row['product_name'],
                    "product_picture" => $row['product_picture'],
                ];
               							  
            }
            return $total_array;

        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }
    }

    public function insertNewOrderParts($last_id,$user_id) {

        $items_for_insert = $this->getItemsForInsert($user_id);
        
        try{
            $this->connect();
        
        foreach($items_for_insert as $value) {
            $sql = "INSERT INTO orders_parts (order_id,product_id,qnt,price,total)
                     VALUES (?,?,?,?,?) ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$last_id,$value['product_id'], $value['quantity'],
            $value['product_price'], $value['total'] ]);

        }
           
        return["flag"=>1];


        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }




    }

    
    public function setCancelOrder($order_id) {

        try{
            $this->connect();
           
            $sql = "UPDATE orders SET order_status = 0 WHERE id_order = ? ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$order_id]);
            return ["flag" => 1];

        }catch(Exception $e){
            return["flag"=>0];
        }       

    }

    public function setHandleOrder($order_id) {

        try{
            $this->connect();
           
            $sql = "UPDATE orders SET order_status = 1 WHERE id_order = ? ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$order_id]);
            return ["flag" => 1];

        }catch(Exception $e){
            return["flag"=>0];
        }       

    }

    public function setDoneOrder($order_id) {

        try{
            $this->connect();
           
            $sql = "UPDATE orders SET order_status = 2 WHERE id_order = ? ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$order_id]);
            return ["flag" => 1];

        }catch(Exception $e){
            return["flag"=>0];
        }       

    }

    public function getItemsForInsert($user_id) {
      
        $items_for_insert = [];
      
        try{
            $this->connect();
           
            $sql = "SELECT cart_items.product_id, quantity,product_price
            FROM `cart_items`
            INNER JOIN products ON cart_items.product_id = products.product_id
            WHERE user_id = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$user_id]);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $price = $row['product_price'];
                $qnt = $row['quantity'];
                $row['total'] = $price * $qnt;
                $items_for_insert [] = $row;										  
            }
            
            return $items_for_insert;

        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }

    }

    public function get_items_in_cart($user_id) {

        $all_items = [];
        try{
            $this->connect();
            $sql = "SELECT * FROM `cart_items` 
            INNER JOIN products ON products.product_id = cart_items.product_id
            where user_id = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$user_id]);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $all_items [] = $row;										  
            }

            $this->disconnect();
            return["flag"=>1, "cart_items" => $all_items];
        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }
    }

    public function addToCartItemFromGallery($product_id,$user_id) {

        
        try{
            $this->connect();
            $sql = "INSERT INTO cart_items (user_id,product_id,quantity) VALUES (?,?,1)
                     ON DUPLICATE KEY UPDATE quantity = quantity + 1    ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                $user_id,$product_id
            ]);
            $this->disconnect();
            return["flag"=>1];
        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }




    }

    public function addToCartDb($product_id,$user_id){
        try{
            $this->connect();
            $sql = "UPDATE cart_items SET quantity = quantity + 1 WHERE user_id = ? AND id = ?  ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                $user_id,$product_id
            ]);
            $this->disconnect();
            return["flag"=>1];
        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }
    }

    public function eraseFromCartDb($table_id,$user_id){
        try{
            $this->connect();

            $sql = "SELECT quantity FROM cart_items WHERE user_id = ? AND id = ? ";
         
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$user_id,$table_id]);
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $quantity = $row['quantity'];									  
            }

            if ($quantity > 1) {
                //update
                $sql = "UPDATE cart_items SET quantity = quantity -1 WHERE user_id = ? AND id = ?   ";
            }
            else {
                // delete
                $sql = "DELETE FROM cart_items WHERE user_id = ? AND id = ? ";
            }
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                $user_id,$table_id
            ]);
            $this->disconnect();
            return["flag"=>1];
        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }
    }

    public function deleteFromCartDb($table_id,$user_id){
        try{
            $this->connect();
            $sql = "DELETE FROM cart_items WHERE user_id = ? AND id = ? ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                $user_id,$table_id
            ]);
            $this->disconnect();
            return["flag"=>1];
        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }
    }

    public function removeComponentsExistsProduct($id_row) {

        try{
            $this->connect();
            $sql = "DELETE FROM products_components WHERE id = ? ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                $id_row
            ]);
            $this->disconnect();
            return["flag"=>1];
        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }
        
    }

    public function getDetailsOfProduct($product_id) {

        $arr = [];
        try{
            $sql = "SELECT products_components.id as id_pc, products.*,products_components.component_id,component_name,products_components.amount as amountToProdict FROM products 
                    LEFT JOIN products_components ON products.product_id = products_components.product_id 
                    LEFT JOIN components ON components.component_id = products_components.component_id
                    WHERE products.product_id = ?
                     ";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$product_id]); 
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product_id = $row['product_id'];
                if (!isset($arr[$product_id])) {
                    $arr[$product_id] = ["product_id" =>$product_id , "product_name" => $row['product_name'], "price" => $row['product_price'], "product_description" => $row['product_description'], "product_picture" => $row['product_picture'], "components" => []    ];
                }
                if (!empty($row['component_id'] )) {
                    $arr[$product_id]["components"][] = ["component_id" => $row['component_id'],"id_pc" => $row['id_pc'], "component_name" => $row['component_name'],"amount" => $row['amountToProdict'] ];
                }
             

            }

            $this->disconnect();
            return $arr;

        }
        catch(Exeption $e){
            return ["flag"=> 0];
        }

    }

    public function getDetailsOfUser($user_id) {

        $arr = [];
        try{
            $sql = "SELECT * FROM user WHERE user_id = ?";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$user_id]); 
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user_id = $row['user_id'];
                if (!isset($arr[$user_id])) {
                    $arr[$user_id] = ["user_id" =>$user_id , "full_name" => $row['full_name'], "email" => $row['email'], "password" => $row['password'], "mobile" => $row['mobile'], "city" => $row['city'], "street" => $row['street'], "number_house" => $row['number_house'], "user_picture" => $row['user_picture'] ];
                }
            }

            $this->disconnect();
            return $arr;

        }
        catch(Exeption $e){
            return ["flag"=> 0];
        }
    }

    public function get_five_cakes_sold($start_date, $end_date,$num_to_show) {

        $arr = [];
        try{
            $sql = "SELECT orders_parts.product_id, sum(qnt) as qnt,products.product_name FROM `orders`
            inner join orders_parts on orders.id_order = orders_parts.order_id
            INNER JOIN products ON orders_parts.product_id = products.product_id
            WHERE create_date >= '$start_date 00:00:00' AND create_date <= '$end_date 23:59:59'
            group by orders_parts.product_id ORDER BY sum(qnt) DESC LIMIT $num_to_show";

            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([]); 
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
               $arr[] = $row;
            }
            $this->disconnect();
            return $arr;

        }
        catch(Exeption $e){
            return [];
        }  


    }

    public function cleanStock($arr) {

        try{
            foreach($arr as $component_id => $value_for_reduce) {
                
                $sql = "UPDATE components SET amount = amount - ? WHERE component_id = ?";
                $this->connect();
                $stmt = $this->connection->prepare($sql);
                $res = $stmt->execute([$value_for_reduce,$component_id ]); 
                $this->disconnect();
            }


        }
        catch(Exeption $e){
            return ["flag"=> 0];
        }  

    }

    public function getStockToClean($user_id) {

        $arr = [];
        
        try{
            $sql = "SELECT component_id, (quantity * amount) as need_to_reduce FROM 
            `cart_items`
            INNER JOIN products_components ON cart_items.product_id = products_components.product_id
            where user_id = ?";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$user_id]); 
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $component_id = $row['component_id'];
                if (!isset($arr[$component_id])) {
                    $arr[$component_id] = 0;
                } 
                $amount_for_reduce = $row['need_to_reduce'];
                $arr[$component_id] +=  $amount_for_reduce;
            }
            $this->disconnect();
            return $arr;

        }
        catch(Exeption $e){
            return ["flag"=> 0];
        }  



    }


    public function getRequiredComponentsForProduct($product_id) {
        $arr = [];
        $needed_array = [];
        try{
            $sql = "SELECT products_components.component_id,components.amount,products_components.amount as needed FROM products_components
            inner join components ON components.component_id = products_components.component_id
            where product_id = ? ORDER BY products_components.component_id";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$product_id]); 
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                   $component_id = $row['component_id'];
                   if (!isset($arr[$component_id])) {
                    $arr[$component_id] = 0;
                   } 
                   $amount = $row['amount'];

                   $arr[$component_id]+= $amount;
                   $needed_array[$component_id] = $row['needed'];

            }

            $this->disconnect();
            return ["arr" => $arr, "needed" => $needed_array];

        }
        catch(Exeption $e){
            return ["flag"=> 0];
        }       
      
    }

    public function getCommitedComponentsForProduct($product_id) {


        $arr = [];
        try{
            $sql = "SELECT cart_items.quantity,cart_items.product_id,component_id,amount
                FROM `cart_items` inner join products_components ON cart_items.product_id = products_components.product_id
            where cart_items.product_id = ? ORDER BY component_id";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$product_id]); 
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                   $component_id = $row['component_id'];
                   if (!isset($arr[$component_id])) {
                    $arr[$component_id] = 0;
                   } 
                   $amount = $row['amount'];
                   $quantity = $row['quantity'];
                   $arr[$component_id]+= ($amount * $quantity );

            }

            $this->disconnect();
            return $arr;

        }
        catch(Exeption $e){
            return ["flag"=> 0];
        }       

    }

    public function getDetailsOfComponent($component_id) {

        $arr = [];
        try{
            $sql = "SELECT * FROM components WHERE component_id = ?";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$component_id]); 
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $component_id = $row['component_id'];
                if (!isset($arr[$component_id])) {
                    $arr[$component_id] = ["component_id" =>$component_id , "component_name" => $row['component_name'], "amount" => $row['amount'] ];
                }

            }

            $this->disconnect();
            return $arr;

        }
        catch(Exeption $e){
            return ["flag"=> 0];
        }

    }

    public function updateComponentsToExistsProduct($product_id,$component_id,$amount) {

        try {
            $this->connect();
            // check if exists component in this product
            $sql = "SELECT count(*) cnt FROM products_components WHERE product_id = ? AND component_id = ?  ";
           
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                $product_id,
                $component_id]);   
                
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $count = $row['cnt'];
                if ($count > 0) {
                    $sql = "UPDATE products_components SET amount = ? WHERE product_id = ? AND component_id = ?   ";
                    $stmt = $this->connection->prepare($sql);
                    $stmt->execute([
                        $amount,
                        $product_id,
                        $component_id]);
                        
                    return true;    
                }
            }      


            $sql = "INSERT INTO products_components (product_id,component_id,amount) VALUES (?,?,?)  ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                $product_id,
                $component_id,
                $amount
            ]);


            return true;

        }
        catch(Exception $e) {

        }


    }

    public function updateComponents($components,$last_id) {
        try{
            $this->connect();

            foreach($components as $comp) {
                $sql = "INSERT INTO products_components (product_id, component_id,amount) VALUES (?,?,?) ";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute([
                    $last_id,
                    $comp['comp_id'],
                    $comp['amount'],

                ]);
            }
            $this->disconnect();
            return["flag"=>1];
        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }


    }

    public function checkExistsProduct($product_name) {

        try {
            $this->connect();
            $sql = "SELECT count(product_id) as cnt FROM products 
            WHERE product_name = ? ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$product_name]);
            $this->disconnect();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $count = $row['cnt'];
                if ($count > 0) return true;
            }
            return false;

        }
        catch(Exception $e) {
            return true;
        }


    }

    public function insertNewProduct($add_new_product){
        // echo "<pre>";
        // print_r ($add_new_product);
        // echo "</pre>";
        // die("dbClass");

        if ($this->checkExistsProduct($add_new_product->getProduct_name())) {
            return["flag"=>-1];
        }

        try{
            $this->connect();
            $sql = "INSERT INTO products (product_name,product_price,product_description,product_picture) VALUES (?,?,?,?)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                $add_new_product->getProduct_name(),
                $add_new_product->getProduct_price(),
                $add_new_product->getProduct_description(),
                $add_new_product->getProduct_picture()
            ]);

            $last_id = $this->connection->lastInsertId();

            $this->disconnect();
            return["flag"=>1, "last_id" => $last_id ];
        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }
    }

    public function checkExistsComponent($component_name) {

        try {
            $this->connect();
            $sql = "SELECT count(component_id) as cnt FROM components 
            WHERE component_name = ? ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$component_name]);
            $this->disconnect();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $count = $row['cnt'];
                if ($count > 0) return true;
            }
            return false;

        }
        catch(Exception $e) {
            return true;
        }


    }

    public function updateComponent($component) {

        try{
            $this->connect();
            $sql = "UPDATE components SET component_name = ? , amount = ? WHERE component_id = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$component->getComponent_name(),$component->getAmount(),$component->getComponent_id()]);
            $this->disconnect();
            return["flag"=>1];
        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }


    }
    
    public function insertNewComponent($add_new_Component){
         //echo "<pre>";
         //print_r ($add_new_Component);
         //echo "</pre>";
         //die("dbClass");

        if ($this->checkExistsComponent($add_new_Component->getComponent_name())) {
            return["flag"=>-1];
        }

        try{
            $this->connect();
            $sql = "INSERT INTO components (component_id,component_name,amount) VALUES (?,?)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$add_new_Component->getComponent_name(),$add_new_Component->getAmount()]);
            $this->disconnect();
            return["flag"=>1];
        }catch(Exception $e){
            return["flag"=>0];
            die($e);
        }
    }

    public function selectProductDetails(){

        $products = [];
                $sql = "SELECT * FROM `products` WHERE 1";
                $this->connect();
                $stmt = $this->connection->prepare($sql);
                $res = $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $products [] = $row;										  
                }

                return $products;
    }

    public function addToItemCartDb($id_item_cart, $user_id) {

        try {
            $sql = "UPDATE cart_items SET quantity = quantity + 1 WHERE id = ? AND  user_id = ?  ";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$id_item_cart, $user_id]);
            $this->disconnect();

            return ["flag" => 1];

        }
        catch(Exception $e) {
            return ["flag" => 0];
        }



    }

    public function selectComponentDetails(){

        $components = [];
                $sql = "SELECT * FROM `components` WHERE 1";
                $this->connect();
                $stmt = $this->connection->prepare($sql);
                $res = $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $components [] = $row;										  
                }

                return $components;
    }

    public function selectUsersDetails(){

        $users = [];
        $sql = "SELECT * FROM `user` WHERE 1";
        $this->connect();
        $stmt = $this->connection->prepare($sql);
        $res = $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $users [] = $row;
        }
        return $users;
    }

    public function checkLogin($email, $password){

        try{
            $sql = "SELECT email, `password`, user_type FROM user WHERE email = ? AND is_active = 1";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$email]); 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->disconnect();

            //in case of empty row (user not exists)
            if(empty($row)){
                return ["flag"=>-1];
            }

            $correct_passowrd = password_verify($password, $row["password"]);
            if($correct_passowrd){
                if($row['user_type']== 0)//user is manager
                    return ["flag"=>0];
                
                if($row['user_type']== 1)//user is client
                    return ["flag"=>1];
            }
            return ["flag"==2]; //wrong passord
        }
        catch(Exception $e) {
           return ["flag"=> -2];
        }

    }

    public function remove_user($user_id) {
        $sql = "UPDATE user SET is_active = 0 WHERE user_id = ? ";
        $this->connect();
        $stmt = $this->connection->prepare($sql);
        $res = $stmt->execute([$user_id]);
        $this->disconnect();
    }

    public function getUserDetails($email){
        try{
            $sql = "SELECT `user_id`, full_name, email, user_picture, user_type FROM user WHERE email = ?";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$email]); 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->disconnect();
            return $row;

        }
        catch(Exeption $e){
            return ["flag"=> 0];
        }
    }

    public function deleteComponent($id) {
        try {
            $sql = "DELETE FROM `components` WHERE `component_id`=?";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$id]);
            $this->disconnect();

            return ["flag" => 1];

        }
        catch(Exception $e) {
            return ["flag" => 0];
        }
    }

    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM `user` WHERE `user_id`=?";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$id]);
            $this->disconnect();

            return ["flag" => 1];

        }
        catch(Exception $e) {
            return ["flag" => 0];
        }
    }

    public function deleteProduct($id) {
        try {
            $sql = "DELETE FROM `products` WHERE `product_id`=?";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$id]);
            $this->disconnect();

            return ["flag" => 1];

        }
        catch(Exception $e) {
            return ["flag" => 0];
        }
    }

    public function deleteOrder($id) {
        try {
            $sql = "DELETE FROM `orders` WHERE `id_order`=?";
            $this->connect();
            $stmt = $this->connection->prepare($sql);
            $res = $stmt->execute([$id]);
            $this->disconnect();

            return ["flag" => 1];

        }
        catch(Exception $e) {
            return ["flag" => 0];
        }
    }
}

?>