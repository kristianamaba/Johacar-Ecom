<?php include('database_connection.php'); session_start();
date_default_timezone_set('Asia/Manila');
	     $user = $_SESSION['username'];
		  $datetime = date('Y-m-d H:i:s');
		  
      if(isset($_POST['addProduct'])){//ADD PRODUCT
		 $pname =  preg_replace("/[^A-Za-z0-9.,  ]/", '', $_POST['product_name']);
		 $pcat =  preg_replace("/[^A-Za-z0-9]/", '', $_POST['product_category']);
		 $pquantity =  preg_replace("/[^0-9 ]/", '', $_POST['available_quantity']);
		 $price =  preg_replace("/[^0-9 ]/", '', $_POST['product_price']);
		 $pdes =  preg_replace("/[^A-Za-z0-9.,  ]/", '', $_POST['product_description']);
		 $pspecs =  preg_replace("/[^A-Za-z0-9.,  ]/", '', $_POST['product_specs']);
		 $ptags =  preg_replace("/[^A-Za-z0-9.,  ]/", '', $_POST['product_tags']);
		 $pbought =  preg_replace("/[^0-9 ]/", '', $_POST['products_bought']);
		 
		 
		  $data = $_POST["photoCROP"];

		 $image_array_1 = explode(";", $data);

		 $image_array_2 = explode(",", $image_array_1[1]);

		 $data = base64_decode($image_array_2[1]);

		 $imageName = time() . '.png';

		 file_put_contents($imageName, $data);

		 $image_file = addslashes(file_get_contents($imageName));
		 
		 
		 $query = "Insert into productstbl (product_name,product_picture,product_description,product_specs,product_tags,product_available,product_price,product_category,product_bought) 
		           values('$pname','$image_file','$pdes','$pspecs','$ptags','$pquantity','$price','$pcat','$pbought')";
         mysqli_query($dbCon, $query);
		 
		 $q= "INSERT INTO reportstbl(report_text, report_date,report_category) VALUES('".$user." added a product named  ".$pname."','$datetime','logs')";
		 mysqli_query($dbCon, $q);
		 header("Location: ../admin?ap&s");
	  }
	  
	  else if(isset($_POST['editProduct'])){//EDIT PRODUCT
	  
		 $pname =  preg_replace("/[^A-Za-z0-9.,  ]/", '', $_POST['product_name']);
		 $pcat =  preg_replace("/[^A-Za-z0-9]/", '', $_POST['product_category']);
		 $pquantity =  preg_replace("/[^0-9 ]/", '', $_POST['available_quantity']);
		 $price =  preg_replace("/[^0-9 ]/", '', $_POST['product_price']);
		 $pdes =  preg_replace("/[^A-Za-z0-9.,  ]/", '', $_POST['product_description']);
		 $pspecs =  preg_replace("/[^A-Za-z0-9.,  ]/", '', $_POST['product_specs']);
		 $ptags =  preg_replace("/[^A-Za-z0-9.,  ]/", '', $_POST['product_tags']);
		 $pbought =  preg_replace("/[^0-9 ]/", '', $_POST['products_bought']);
		 $pID = preg_replace("/[^0-9 ]/", '', $_POST['pid']);
		 
		  $data = $_POST["photoCROP"];

		 $image_array_1 = explode(";", $data);

		 $image_array_2 = explode(",", $image_array_1[1]);

		 $data = base64_decode($image_array_2[1]);

		 $imageName = time() . '.png';

		 file_put_contents($imageName, $data);

		 $image_file = addslashes(file_get_contents($imageName));
		 
		 
		 $query = "UPDATE productstbl SET product_name = '$pname',product_picture='$image_file',product_description='$pdes',product_specs='$pspecs',product_tags='$ptags',product_available='$pquantity',product_price='$price',product_category='$pcat',product_bought='$pbought' WHERE product_id='$pID'";
         mysqli_query($dbCon, $query);
		 $q= "INSERT INTO reportstbl(report_text, report_date,report_category) VALUES('".$user." edited a product named  ".$pname."','$datetime','logs')";
		 mysqli_query($dbCon, $q);
		 header("Location: ../admin?edp&se");
	  }

	  else if(isset($_POST['slideShowChange'])){
		  
		  $slideT = preg_replace("/[^A-Za-z0-9.,  ]/", '', $_POST['slideT']);
		  $slideD = preg_replace("/[^A-Za-z0-9.,  ]/", '', $_POST['slideD']);
		  $wsID = preg_replace("/[^0-9 ]/", '', $_POST['wsID']);
		  
		  
		  
		  $data = $_POST["photoCROP"];
		 $image_array_1 = explode(";", $data);
		 $image_array_2 = explode(",", $image_array_1[1]);
		 $data = base64_decode($image_array_2[1]);
		 $imageName = time() . '.png';
		 file_put_contents($imageName, $data);
		 $image_file = addslashes(file_get_contents($imageName));
		 $query = "UPDATE websitepicturestbl SET slideTitle='$slideT', slideDescription='$slideD', picture_blob='$image_file' WHERE picture_id='$wsID'";
		 mysqli_query($dbCon, $query);
		 header("Location: ../admin/?ws");
	  }
	  
	  ?>