## trend least square
kuadrat terkecil atau yang biasa disebut sebagai metode least square adalah metode peramalan yang menggunakan persamaan linear untuk menemukan garis paling sesuai untuk kumpulan data lampau guna meramalkan data di masa depan.
## rumus

![Screenshot](OVERVIEW/5.PNG)

![Screenshot](OVERVIEW/6.PNG)
# Penggunaan
1. login dengan menggunakan Username : `manager@gmail.com`,  pass : `manager`
![Screenshot](OVERVIEW/1.PNG)

2. klik menu data/penjualan kemudian import data penjualan ( terdapat pada folder DATA IMPORT )
![Screenshot](OVERVIEW/2.PNG)

3. setelah berhasil import, klik menu prediksi/penjualan kemudian klik bulan yang ingin di prediksi
![Screenshot](OVERVIEW/3.PNG)

# hasil
![Screenshot](OVERVIEW/4.PNG)

studi kasus : PT Pinus Merah Abadi ( http://pinusmerahabadi.co.id/ )


# potongan kode
query sql ( code igniter ) : 
```
  public function get_sales_prediction( $product_id, $start_date, $end_date )
  {
      $this->select( $this->table.'.*');
      $this->select( 'product.name as product_name');
      $this->select( 'CONCAT( '.$this->table.'.month, " ", '.$this->table.'.year ) as month_year');
      $this->select( 'sale.quantity as _y');
      $this->select( '0 as _x');
      $this->select( '0 as _xx');
      $this->select( '0 as _xy');
      $this->join( 
        'product',
        'product.id = sale.product_id',
        'inner'
      );
      $this->where( $this->table.'.product_id', $product_id);

      $this->where( $this->table.'.date BETWEEN "'.$start_date.'" AND "'.$end_date.'" ', "");
      $this->order_by( "sale.year asc , sale.month asc", "");
      return $this->fetch_data();
  }
```

kode prediksi : 
```
  public function odd_prediction( $data )
	{
    $_n = count( $data );
		$_mid = ( ( $_n - 1 ) / 2 ) + 1;

		$sum_x 		= 0;
		$sum_y 		= 0;
		$sum_xx 	= 0;
		$sum_xy 	= 0;

		for( $i =0 , $_x = ( $_mid - 1 ) * -1; $_x <= ( $_mid - 1 ), $i < $_n ; $_x++, $i++ )
		{
			$sum_x += $_x;
			$sum_y += $data[$i]->_y;
			$sum_xx += ( $_x*$_x );
			$sum_xy += ( $_x*$data[$i]->_y );

			$data[$i]->_x = $_x;
			$data[$i]->_xx = ( $_x*$_x );
			$data[$i]->_xy = ( $_x*$data[$i]->_y );
		}

		$data []= ( object ) 
			array(
				"id" => 0,
				"product_id" => 0,
				"month" => "Total",
				"year" => 0,
				"quantity" => 0,
				"product_name" => 0,
				"next_x" => $_mid,
				"_x" => $sum_x,
				"_y" => $sum_y,
				"_xx" => $sum_xx,
				"_xy" => $sum_xy,

			);
		return $data;
	}

	public function even_prediction( $data )
	{
    $_n = count( $data );
		$_mid = $_n / 2;

		$sum_x 		= 0;
		$sum_y 		= 0;
		$sum_xx 	= 0;
		$sum_xy 	= 0;

		for( $i =0 , $_x = ( $_mid * 2 - 1 ) * -1; $_x <= ( $_mid * 2 - 1 ) , $i < $_n ; $_x+= 2 , $i++ )
		{
			$sum_x += $_x;
			$sum_y += $data[$i]->_y;
			$sum_xx += ( $_x*$_x );
			$sum_xy += ( $_x*$data[$i]->_y );

			$data[$i]->_x = $_x;
			$data[$i]->_xx = ( $_x*$_x );
			$data[$i]->_xy = ( $_x*$data[$i]->_y );
		}

		$data []= ( object ) 
			array(
				"id" => 0,
				"product_id" => 0,
				"month" => "Total",
				"year" => 0,
				"quantity" => 0,
				"product_name" => 0,
				"next_x" => $_mid*2 +1,
				"_x" => $sum_x,
				"_y" => $sum_y,
				"_xx" => $sum_xx,
				"_xy" => $sum_xy,

			);
		return $data;
	}
```
```
$prediction = $this->services->even_prediction( HASIL_QUERY );
$result = $prediction[ count( $prediction  ) -1 ];
$_n = count( $prediction  ) -1  ;
$x = $result->next_x;
$a = $result->_y / $_n;
$b = $result->_xy / $result->_xx ;
$_y_accent = $a +( $b * $result->next_x ) ;

```

