<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('DDM_Model.php');

class Model_Product_Activation extends DDM_Model
{
    /**
     * For AN_Model
     */
    public $_table              = 'product_activation';

    /**
     * Initialize table
     */
    var $activation             = TBL_PREFIX."product_activation";
    var $member                 = TBL_PREFIX."member";

    /**
     * Initialize primary field
     */
    var $primary                = "id";

    /**
     * Constructor - Sets up the object properties.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Save data of Product Activation
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of Product Activation
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_product_activation($data)
    {
        if (empty($data)) return false;

        if ( $id = $this->insert($data)) {
            return $id;
        };
        return false;
    }

    /**
     * Update data of Product Activation
     *
     * @author  Iqbal
     * @param   Int     $omzet_id   (Required)  Product Activation ID
     * @param   Array   $data       (Required)  Array data of Product Activation
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_product_activation($omzet_id, $data)
    {
        if (empty($omzet_id) || empty($data))
            return false;

        if ($this->update($omzet_id, $data))
            return true;

        return false;
    }

    /**
     * Delete Product Activation data
     * 
     * @author  Iqbal
     * @param   Int     $id     (Required)  Product Activation ID
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function delete_product_activation($id)
    {
        if (empty($id))
            return false;

        if ($this->delete($id))
            return true;

        return false;
    }

    // ---------------------------------------------------------------------------------
}
/* End of file Model_Product_Activation.php */
/* Location: ./ddmapp/models/Model_Product_Activation.php */
