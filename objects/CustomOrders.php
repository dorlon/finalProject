<?php
Class CustomOrders {
    protected $user_id;

    function __construct($user_id = false) {
        $this->user_id = $user_id;
    }

    public function getAllOrdersPerUserId() {

        $db = new DbClass();
        return $db->getAllOrdersPerUserId($this->user_id);
    }

    public function getAllOrdersForAdmin() {

        $db = new DbClass();
        return $db->getAllOrdersForAdmin();
    }
}
?>