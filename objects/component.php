<?php
Class Component{
    protected $component_id;
    protected $component_name;
    protected $amount;


    public function getAllComponents() {
        $db = new dbClass();
        return $db->selectAllComponents();
    }

    public function getDetailsOfComponent($component_id) {
        $db = new dbClass();
        return $db->getDetailsOfComponent($component_id);
    }

    public function getComponent_id()
    {
        return $this->component_id;
    } 

    public function getComponent_name()
    {
        return $this->component_name;
    } 

    public function setComponent_id($component_id) {
        $this->component_id = $component_id;
    }

    public function setComponent_name($newComponent_name)
    {
        $this->component_name=$newComponent_name;
    }

    public function getAmount()
    {
        return $this->amount;
    } 

    public function setAmount($newAmount)
    {
        $this->amount=$newAmount;
    }
}
?>