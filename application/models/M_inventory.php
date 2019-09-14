<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_inventory extends MY_Model
{
  protected $table = "inventory";

  function __construct() {
      parent::__construct( $this->table );
      parent::set_join_key( 'inventory_id' );
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
      $id = $this->db->insert_id( $this->table . '_id_seq');
    
      if( isset($id) )
      {
        $this->set_message("berhasil");
        return $id;
      }
      $this->set_error("gagal");
          return FALSE;
  }

  /**
   * create
   *
   * @param array  $data
   * @return static
   * @author madukubah
   */
  public function create_batch( $entries )
  {
      $this->db->trans_begin();
      
      $this->db->insert_batch( $this->table , $entries);

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
      $this->set_error("gagal");//('inventory_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();

    $this->db->delete($this->table, $data_param );
    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();

      $this->set_error("gagal");//('inventory_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil");//('inventory_delete_successful');
    return TRUE;
  }

  /**
   * quantity_sum
   *
   * @return bool
   * @author madukubah
   */
  public function quantity_sum(   )
  {
    
    $sql = "
      SELECT SUM( inventory.quantity ) as sum from inventory
    ";

    return $this->db->query( $sql );
  }

    /**
   * inventory
   *
   * @param int|array|null $id = id_inventories
   * @return static
   * @author madukubah
   */
  public function inventory( $id = NULL  )
  {
      if (isset($id))
      {
        $this->where($this->table.'.id', $id);
      }

      $this->limit(1);
      $this->order_by($this->table.'.id', 'desc');

      $this->inventories(  );

      return $this;
  }
  /**
   * inventories
   *
   *
   * @return static
   * @author madukubah
   */
  public function inventories( $start = 0 , $limit = NULL )
  {
      if (isset( $limit ))
      {
        $this->limit( $limit );
      }
      $this->offset( $start );

      $this->select( $this->table.'.*');
      $this->select( 'product.name as product_name');
      $this->join( 
        'product',
        'product.id = inventory.product_id',
        'inner'
      );
      // $this->order_by($this->table.'.id', 'asc');
      $this->order_by( "inventory.year asc , inventory.month asc", "");
      return $this->fetch_data();
  }

  /**
   * last_n_months
   *
   *
   * @return static
   * @author madukubah
   */
  public function last_n_months( $limit )
  {
      if (isset( $limit ))
      {
        $this->limit( $limit );
      }

      $this->select( $this->table.'.*');
      $this->select( 'product.name as product_name');
      $this->join( 
        'product',
        'product.id = inventory.product_id',
        'inner'
      );
      $this->order_by($this->table.'.date', 'desc');
      // $this->order_by( "inventory.year asc , inventory.month asc", "");
      return $this->fetch_data();
  }

  /**
   * inventories
   *
   *
   * @return static
   * @author madukubah
   */
  public function get_inventories_prediction( $product_id, $start_date, $end_date )
  {
      $this->select( $this->table.'.*');
      $this->select( 'product.name as product_name');
      $this->select( 'inventory.quantity as _y');
      $this->select( '0 as _x');
      $this->select( '0 as _xx');
      $this->select( '0 as _xy');
      $this->join( 
        'product',
        'product.id = inventory.product_id',
        'inner'
      );
      // $this->where( $this->table.'.month >= ', $start_month);
      // $this->where( $this->table.'.month <= ', $end_month);
      $this->where( $this->table.'.product_id', $product_id);

      $this->where( $this->table.'.date BETWEEN "'.$start_date.'" AND "'.$end_date.'" ', "");
      // $this->order_by($this->table.'.id', 'asc');
      $this->order_by( "inventory.year asc , inventory.month asc", "");
      return $this->fetch_data();
  }

  /**
   * inventories
   *
   *
   * @return static
   * @author madukubah
   */
  public function filters( $product_id, $year  )
  {
      $this->where($this->table.'.product_id', $product_id);
      $this->where($this->table.'.year', $year);

      $this->order_by($this->table.'.id', 'asc');

      $this->inventories(  );

      return $this;
  }

}
?>
