<?php
Class Product{
    protected $product_id;
    protected $product_name;
    protected $product_price;
    protected $product_description;
    protected $product_picture;

    public function getDetailsOfProduct($product_id) {

        $db = new DbClass();
        return $db->getDetailsOfProduct($product_id);


    }
    public function getProduct_id()
    {
        return $this->Product_id;
    } 

    public function getProduct_name()
    {
        return $this->product_name;
    } 

    public function setProduct_name($newProduct_name)
    {
        $this->product_name=$newProduct_name;
    }

    public function getProduct_price()
    {
        return $this->product_price;
    } 

    public function setProduct_price($newProduct_price)
    {
        $this->product_price=$newProduct_price;
    }

    public function getProduct_description()
    {
        return $this->product_description;
    } 

    public function setProduct_description($newProduct_description)
    {
        $this->product_description=$newProduct_description;
    }

    public function getProduct_picture()
    {
        return $this->product_picture;
    } 

    public function setProduct_picture($newProduct_picture)
    {
        $this->product_picture=$newProduct_picture;
    }
}
?>