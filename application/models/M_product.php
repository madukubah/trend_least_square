<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_product extends MY_Model
{
  protected $table = "product";

  function __construct() {
      parent::__construct( $this->table );
      parent::set_join_key( 'product_id' );
  }

  /**
   * create
   *
   * @param array  $data
   * @return static
   * @author madukubah
   */
  public function create( $data )
  {
      // Filter the data passed
      $data = $this->_filter_data($this->table, $data);

      $this->db->insert($this->table, $data);
      $id = $this->db->insert_id($this->table . '_id_seq');
    
      if( isset($id) )
      {
        $this->set_message("berhasil");
        return $id;
      }
      $this->set_error("gagal");
          return FALSE;
  }
  /**
   * update
   *
   * @param array  $data
   * @param array  $data_param
   * @return bool
   * @author madukubah
   */
  public function update( $data, $data_param  )
  {
    $this->db->trans_begin();
    $data = $this->_filter_data($this->table, $data);

    $this->db->update($this->table, $data, $data_param );
    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();

      $this->set_error("gagal");
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil");
    return TRUE;
  }
  /**
   * delete
   *
   * @param array  $data_param
   * @return bool
   * @author madukubah
   */
  public function delete( $data_param  )
  {
    //foreign
    //delete_foreign( $data_param. $models[]  )
    if( !$this->delete_foreign( $data_param ) )
    {
      $this->set_error("gagal");//('product_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();

    $this->db->delete($this->table, $data_param );
    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();

      $this->set_error("gagal");//('product_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil");//('product_delete_successful');
    return TRUE;
  }

    /**
   * product
   *
   * @param int|array|null $id = id_products
   * @return static
   * @author madukubah 
   */
  public function product( $id = NULL  )
  {
      if (isset($id))
      {
        $this->where($this->table.'.id', $id);
      }

      $this->limit(1);
      $this->order_by($this->table.'.id', 'desc');

      $this->products(  );

      return $this;
  }

  /**
   * product
   *
   * @param int|array|null $id = id_products
   * @return static
   * @author madukubah 
   */
  public function product_by_code( $code )
  {
      $this->db->where($this->table.'.code', $code);
      $this->db->limit(1);
      return $this->db->get($this->table);
  }
  /**
   * products
   *
   *
   * @return static
   * @author madukubah
   */
  public function products(  )
  {
      
      $this->order_by($this->table.'.id', 'asc');
      return $this->fetch_data();
  }

}
?>
