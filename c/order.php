<?php include('database_connection.php'); session_start(); 
     function getAccountID(){include('database_connection.php');
		 $data = "";
		 $user = $_SESSION['username'];
		 $query = "SELECT  account_id FROM accountstbl WHERE account_username='$user'";
		 $result = mysqli_query($dbCon, $query);
		 while($row = mysqli_fetch_array($result)){
			 $data = $row['account_id'];
		 }
		 return $data;
	 }
     function setOrderQuantity($pid, $pquan){include('database_connection.php');
	     $user = $_SESSION['username'];
		 $query = "UPDATE orderdetailstbl b  JOIN productstbl a ON b.order_product_id=a.product_id JOIN accountstbl c ON b.account_id=c.account_id SET b.order_quantity='$pquan' WHERE c.account_username='$user' AND order_product_id='$pid' AND b.order_date IS NULL";
		 mysqli_query($dbCon, $query);
	 }
	 function setOrderDeduct($pid, $pquan){include('database_connection.php');
	     $user = $_SESSION['username'];
		 $query1 = "UPDATE orderdetailstbl b  JOIN productstbl a ON b.order_product_id=a.product_id JOIN accountstbl c ON b.account_id=c.account_id SET b.order_quantity='$pquan' WHERE c.account_username='$user' AND order_product_id='$pid' AND b.order_date IS NULL";
		 mysqli_query($dbCon, $query1);
		 $query2 = "UPDATE productstbl set product_available = product_available -'$pquan' where product_id = '$pid'";
		 mysqli_query($dbCon, $query2);
	 }
	 
	 //UPDATE productstbl set product_available = product_available  -'10' where product_id = 11;
	 
	 function setOrderPurchase($date){include('database_connection.php');
	     $user = $_SESSION['username'];
		 $query = "UPDATE orderdetailstbl b  JOIN productstbl a ON b.order_product_id=a.product_id JOIN accountstbl c ON b.account_id=c.account_id SET b.order_date='$date', b.order_status='pending' WHERE c.account_username='$user' AND b.order_date IS NULL AND b.order_quantity!='0'";
		 mysqli_query($dbCon, $query);
	 }
     function getMaxQuantityName($pID){include('database_connection.php');
		 $data = array();
		 $query = "SELECT product_available, product_name FROM productstbl WHERE product_id='$pID' ";
		 $result = mysqli_query($dbCon, $query);
		 while($row = mysqli_fetch_array( $result)){
			 $data[0] = $row['product_available']; $data[1] = $row['product_name'];
		 }
		 return $data;
	 }
     if (isset($_POST['savebtn'])){
		 $error = "";
		 $dataArray = $_POST['data'];
		 for ($count = 0; $count<count($dataArray); $count++){   //$dataArray['quantity'][$count]
			 $quantity = $dataArray['quantity'][$count];
			 $pdetails = getMaxQuantityName($dataArray['pid'][$count]);
			 if ($pdetails[0]< $quantity){
				 $error .= $dataArray[1]." Does not have enought stocks which you reserved!\\n";
				  setOrderQuantity($dataArray['pid'][$count],$pdetails[0] );
			 }
			 else{
				 setOrderQuantity($dataArray['pid'][$count], $quantity);
			 }
		 }
		 
			 if($error==""){
				 header('Location: ../c/');
			 }
			 else{
				 $_SESSION['m'] = $error;
				 header('Location: ../c/?or&m');
			 }
	 }
	 else if(isset($_POST['orderbtn'])){
		 date_default_timezone_set('Asia/Manila');
	     $datetime = date('Y-m-d H:i:s');
		 $error = "";
		 $dataArray = $_POST['data'];
		 for ($count = 0; $count<count($dataArray); $count++){ 
			 $quantity = $dataArray['quantity'][$count];
			 $pdetails = getMaxQuantityName($dataArray['pid'][$count]);
			 if ($pdetails[0]< $quantity){
				 $error .= $dataArray[1]." Does not have enought stocks which you reserved!\\n";
				  setOrderQuantity($dataArray['pid'][$count],$pdetails[0] );
			 }
			 else{
				 setOrderQuantity($dataArray['pid'][$count], $quantity);
			 }
		 }
		 
			 if($error==""){
				 setOrderPurchase($datetime);
				 for ($count = 0; $count<count($dataArray); $count++){ 
					 $quantity = $dataArray['quantity'][$count];
						 setOrderDeduct($dataArray['pid'][$count], $quantity);
					 
				 }
				 
				 $_SESSION['m'] = 'Order Successful';
				 header('Location: ../c/?or&m');
			 }
			 else{
				 $_SESSION['m'] = $error;
				 header('Location: ../c/?or&m');
			 }
	 }
	 else if(isset($_POST['addbtn'])){
		 $accountID = getAccountID();
		 $orNum = preg_replace('/\D/', '',  $_POST['orderNum']);
		 $pid = preg_replace('/\D/', '',  $_POST['pid']);
		 $queryCheck = "SELECT * FROM orderdetailstbl WHERE account_id='$accountID' AND order_product_id='$pid' AND order_date IS NULL";
		 $result = mysqli_query($dbCon, $queryCheck);
			 if(mysqli_num_rows($result)>=1){
				 $q = "UPDATE orderdetailstbl SET order_quantity = order_quantity + '$orNum' WHERE account_id='$accountID' AND order_product_id='$pid' AND order_date IS NULL";
			      mysqli_query($dbCon, $q);
				  $_SESSION['messagealert'] = "Product Added on your Cart!";
			 }
			 else{
				 $q = "INSERT INTO orderdetailstbl(account_id,order_product_id,order_quantity) VALUES ('$accountID','$pid','$orNum')";
			      mysqli_query($dbCon, $q);
				  $_SESSION['messagealert'] = "Product Added on your Cart!";
			 }
		 
		 header("Location: ../c/?p='.$pid.'");
	  }
	 else{
		 header('Location: ../c/');
	 }
?>