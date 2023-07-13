<?php
Class Order{
    protected $order_id;
    protected $order_id_paypal;
    protected $user_id;
    protected $total_price;
    protected $delivery_date;
    protected $order_status;



    public function getUserDetails() {
        $db = new dbClass();
        return $db->getUserDetailsForMail($this->user_id);
    }

    function __construct($order_id_paypal = false, $user_id = false, $total_price = false , $delivery_date = false) {
        $this->order_id_paypal = $order_id_paypal;
        $this->user_id = $user_id;
        $this->total_price = $total_price;
        $this->delivery_date = $delivery_date;
      }


    public function createNewOrder(){

        $db = new dbClass();
        $answer = $db->insertNewOrder($this->order_id_paypal, $this->user_id, $this->total_price, $this->delivery_date);
        return $answer;
    }

    public function cleanStock() {

        $db = new dbClass();
        $stock_to_clean = $db->getStockToClean($this->user_id);
        $db->cleanStock($stock_to_clean);

    }

    public function createOrdersParts($last_id) {
        $db = new dbClass();
        $answer = $db->insertNewOrderParts($last_id,$this->user_id);
        return $answer;

    }

    public function removeCartAfterFinish() {
        $db = new dbClass();
        $answer = $db->deleteCartAfterFinish($this->user_id);
    }


    public function getOrder_id()
    {
        return $this->order_id;
    } 

    public function getUser_order()
    {
        return $this->user_order;
    } 

    public function setUser_order($newUser_order)
    {
        $this->user_order=$newUser_order;
    }

    public function getAddress()
    {
        return $this->address;
    } 

    public function setAddress($newAddress)
    {
        $this->address=$newAddress;
    }

    public function getTottal_price()
    {
        return $this->tottal_price;
    } 

    public function setTottal_price($newTottal_price)
    {
        $this->tottal_price=$newTottal_price;
    }

    public function getDelivery_date()
    {
        return $this->delivery_date;
    } 

    public function getOrder_status()
    {
        return $this->order_status;
    } 

    public function setOrder_status($newOrder_status)
    {
        $this->order_status=$newOrder_status;
    }

    public function getDownload_order()
    {
        return $this->download_order;
    } 

    public function setDownload_order($newDownload_order)
    {
        $this->download_order=$newDownload_order;
    }
}
?>