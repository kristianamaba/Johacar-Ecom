<!DOCTYPE html>
<?php  session_start();   include('database_connection.php'); ob_start();
date_default_timezone_set('Asia/Manila');

	//if ( $_SESSION['user']=="admin"||$_SESSION['user']=="staff"){				   
		//header("Location: ../c/admin");
		//ob_end_flush();
						 
	//}

function getUsername($username){include('database_connection.php');
	
	
	    $data = "";
	
	$q = "SELECT account_username  from accountstbl WHERE account_username='$username' OR account_email='$username'"; 
	$result=$dbCon->query($q);
	 while($row = $result->fetch_assoc()) {
		$data = $row["account_username"];

		
    } 

	return $data ;
}


	function getReviewsData($rid){include('database_connection.php');
		$data = array();
		$query = "SELECT a.account_username, a.account_category, a.account_photo, b.rating_description FROM accountstbl a JOIN ratingstbl b ON a.account_number_ratings=b.account_number WHERE a.account_number_ratings='$rid'";
	    $result = mysqli_query($dbCon, $query);
		while($row = mysqli_fetch_array($result)){
			$data[0]=$row['account_username'];$data[1]=$row['account_category'];$data[2]=$row['account_photo'];$data[3]=$row['rating_description'];
		}
		return $data;
	}

function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}


function getUserPhoto($username){include('database_connection.php');
	     $q = $connect->query("SELECT account_photo FROM accountstbl WHERE account_username='$username'");
		 $f = $q->fetch(); $result = $f[0];
     return 'data:image/png;base64,'.base64_encode($result);
}

function getUserCat($username){include('database_connection.php');
	
	
	    $data = "";
	
	$q = "SELECT account_category, account_username  from accountstbl WHERE account_username='$username'"; 
	$result=$dbCon->query($q);
	 while($row = $result->fetch_assoc()) {
		$data = $row["account_category"];

		
    } 

	return $data ;
}

function getSlideDetails($id){include('database_connection.php');
	    $data = array();
	
	$q = "SELECT slideTitle,slideDescription from websitepicturestbl WHERE picture_id='".$id."'"; 
	$result=$dbCon->query($q);
	 while($row = $result->fetch_assoc()) {
		$data[0] = $row["slideTitle"];
        $data[1] = $row["slideDescription"];
    } 

	return $data ;
}

function bulletForm($stringT){
	$bullet ="<ul style='padding-left:15px;'><li>".str_replace("</li></ul>",".</li></ul>",str_replace(".",".</li><li>",rtrim(trim($stringT),"."))."</li></ul>");
	return $bullet;
}

function checkCharacter(){
	$arrayValid = array("\0","\'","\b","\n","\r","\t","\z","\\","\%","\_",";");
}

function checkValid($stringT){
  $checker = false;
  $arrayInvalid = array("\\0","\\'","\\b","\\n","\\r","\\t","\\z","\\","\%","\\_",";");
	  foreach($arrayInvalid as $value){
		  if (strpos($stringT,$value)>(-1)){
			  $checker = true;
		  }
	  }
	  return $checker;
  }
  
function errorPage(){
	echo '  <section class="mbr-section content4 cid-riwGzKkYW3" id="content4-1d">

    

    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2" style="font-size:300px;">
                    404</h2>
                <h3 class="mbr-section-subtitle align-center mbr-light mbr-fonts-style display-5">
                    This is somewhat embarrasing, isn\'t it?<br><br>It looks like nothing is found at this location.</h3>
                
            </div>
        </div>
    </div>
</section>';
}  

function showResult(&$pdetails){
	echo '<div class="card p-3 col-12 col-md-6 col-lg-3" >
                <div class="card-wrapper" >
                    <div class="card-img" >
                        <img class="border border-secondary"   onclick="location.href=\'?p='.$pdetails[0].'\';return false;" src="';  echo productPicture($pdetails[0]);  echo '" alt="product title">
                    </div>
                    <div class="card-box" style="min-height:195px;" >
                        <h4 class="card-title mbr-fonts-style display-7">
                            '.$pdetails[1].'
                        </h4>
						<b class="mbr-text mbr-fonts-style display-7" style="color: orange;">
                           ₱'.$pdetails[6].'.00
                        </b><br>
                        <p style="padding:0px;" >
                            '.substr( ((empty(trim($pdetails[2]))) ? bulletForm($pdetails[3]) : $pdetails[2]),0,90).'...
                        </b>
                    </div>
                    <div class="mbr-section-btn text-center" >
                        <a onclick="location.href=\'?p='.$pdetails[0].'\';return false;"  class="btn btn-primary display-4" >
                            Learn More
                        </a>
                    </div>
                </div>
            </div>';
}
  
function slideshowPicture($key){include('database_connection.php');
     $q = $connect->query("SELECT picture_blob FROM websitepicturestbl WHERE picture_id='".$key."'"); $f = $q->fetch(); $result = $f[0];
     echo '"data:image/png;base64,'.base64_encode($result).'"';
}

function productPicture($pID){include('database_connection.php');
     $q = $connect->query("SELECT product_picture FROM productstbl WHERE product_id='".$pID."'"); $f = $q->fetch(); $result = $f[0];
	 
     echo 'data:image/png;base64,'.base64_encode($result);
}

function getProductsFromCategory($category){include('database_connection.php');
    $results1 = array();
	$query="SELECT DISTINCT product_id from  productstbl where product_category='".$category."' ";
	
	$count=mysqli_query($dbCon,$query);
	$resultNum = mysqli_num_rows($count);
	   for($count = 0; $count<$resultNum; $count++){
		   $q = "SELECT product_id from  productstbl where product_category='".$category."'  LIMIT ".$count.", 1"; 
			$result=$dbCon->query($q);
			while($row = $result->fetch_assoc()) {
			$results1[$count] = $row['product_id'];	
		    }
	   }
	   
	return $results1;
}

function searchProduct($searchText){include('database_connection.php');
    $results1 = array();
	$query="SELECT product_id from productstbl where product_name LIKE '%".$searchText."%' OR product_description LIKE '%".$searchText."%' OR product_tags LIKE '%".$searchText."%' OR product_specs LIKE '%".$searchText."%' ";
	
	$count=mysqli_query($dbCon,$query);
	$resultNum = mysqli_num_rows($count);
	   for($count = 0; $count<$resultNum; $count++){
		   $q = "SELECT product_id from productstbl where product_name LIKE '%".$searchText."%' OR product_description LIKE '%".$searchText."%' OR product_tags LIKE '%".$searchText."%' OR product_specs LIKE '%".$searchText."%' ORDER BY product_bought LIMIT ".$count.", 1"; 
			$result=$dbCon->query($q);
			while($row = $result->fetch_assoc()) {
			$results1[$count] = $row['product_id'];	
		    }
	   }
	   
	return $results1;
}

function getProductDetails($pID){include('database_connection.php');
    
	
	
    $data = array();
	
	$q = "SELECT product_id, product_name, product_description, product_specs, product_tags, product_available, product_price, product_category from productstbl where product_id='".$pID."'"; 
	$result=$dbCon->query($q);
	 while($row = $result->fetch_assoc()) {
		$data[0] = $row["product_id"];
        $data[1] = $row["product_name"];
		$data[2] = $row["product_description"];
        $data[3] = $row["product_specs"];
		$data[4] = $row["product_tags"];
        $data[5] = $row["product_available"];
		$data[6] = $row["product_price"];
        $data[7] = $row["product_category"];
		
    } 

	return $data ;
}

function errorLoginRegister($error){
	echo " $('#LOGINREGISTER').modal('show');"; echo "alert('".$error."');";
}

function searchResultView($text, $count){	
}

?>
<html style="">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="generator" content="Mobirise v4.9.3, mobirise.com">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
  <meta name="description" content="">
  <title>Johancar Enterprise</title>
  <link rel="stylesheet" href="assets/web/assets/mobirise-icons/mobirise-icons.css">
  <link rel="stylesheet" href="assets/tether/tether.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-reboot.min.css">
  <link rel="stylesheet" href="assets/socicon/css/styles.css">
  <link rel="stylesheet" href="assets/dropdown/css/style.css">
  <link rel="stylesheet" href="assets/theme/css/style.css">
  <link rel="stylesheet" href="assets/mobirise/css/mbr-additional.css" type="text/css">
  <script src="js/jquery.min.js" rel="stylesheet"></script>
  <script src="js/bootstrap.min.js" rel="Stylesheet"></script>
  <script src="assets/web/assets/jquery/jquery.min.js"></script>
  <script src="assets/popper/popper.min.js"></script>
  <script src="assets/tether/tether.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/smoothscroll/smooth-scroll.js"></script>
  <script src="assets/touchswipe/jquery.touch-swipe.min.js"></script>
  <script src="assets/parallax/jarallax.min.js"></script>
  <script src="assets/viewportchecker/jquery.viewportchecker.js"></script>
  <script src="assets/dropdown/js/script.min.js"></script>
  <script src="assets/theme/js/script.js"></script>
    <script src="admin/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="admin/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="admin/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
      <script src="admin/js/bootstrap.min.js" rel="Stylesheet"></script>
	  <link rel="stylesheet" href="admin/croppie.css" />
	  <script src="admin/croppie.js"></script>
  

  
    <script src="admin/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="admin/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="admin/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	  
  <style>
    .panel-order .row {
	border-bottom: 1px solid #ccc;
}
.panel-order .row:last-child {
	border: 0px;
}
.panel-order .row .col-md-1  {
	text-align: center;
	padding-top: 15px;
}
.panel-order .row .col-md-1 img {
	width: 50px;
	max-height: 50px;
}
.panel-order .row .row {
	border-bottom: 0;
}
.panel-order .row .col-md-11 {
	border-left: 1px solid #ccc;
}
.panel-order .row .row .col-md-12 {
	padding-top: 7px;
	padding-bottom: 7px; 
}
.panel-order .row .row .col-md-12:last-child {
	font-size: 11px; 
	color: #555;  
	background: #efefef;
}
.panel-order .btn-group {
	margin: 0px;
	padding: 0px;
}
.panel-order .panel-body {
	padding-top: 0px;
	padding-bottom: 0px;
}
.panel-order .panel-deading {
	margin-bottom: 0;
}       
  
  
.modal-open[style] {
   padding-right: 0px !important;
}
  
  
    body,html{
    height: 100%;
    width: 100%;
    margin: 0;
    padding: 0;
    background: #e74c3c !important;
    }

    .searchbar{
    margin-bottom: auto;
    margin-top: auto;
    height: 60px;
    background-color: #353b48;
    border-radius: 30px;
    padding: 10px;
	color: white;
    }

    .search_input{
    color: white;
    border: 0;
    outline: 0;
    background: none;
    width: 0;
    caret-color:transparent;
    line-height: 40px;
    transition: width 0.4s linear;
    }

    .searchbar:hover > .search_input{
    padding: 0 10px;
    width: 200px;
    caret-color:white;
    transition: width 0.4s linear;
    }

    .searchbar:hover > .search_icon{
    background: grey;
    color: white;
    }


    .search_icon{
    height: 40px;
    width: 40px;
    float: right;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    color:white;
    }
	
	  .preview {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
      -ms-flex-direction: column;
          flex-direction: column; }
  @media screen and (max-width: 996px) {
    .preview { } }

.preview-pic {
  -webkit-box-flex: 1;
  -webkit-flex-grow: 1;
      -ms-flex-positive: 1;
          flex-grow: 1; }



.tab-content {
  overflow: hidden; }
  .tab-content img {
    width: 100%;
  min-height:100%;
    -webkit-animation-name: opacity;
            animation-name: opacity;
    -webkit-animation-duration: .3s;
            animation-duration: .3s; }

.card {
  margin-top: 50px;
  line-height: 1.5em; }

@media screen and (min-width: 997px) {
  .wrapper {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex; } }

.details {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
      -ms-flex-direction: column;
          flex-direction: column; }

.colors {
  -webkit-box-flex: 1;
  -webkit-flex-grow: 1;
      -ms-flex-positive: 1;
          flex-grow: 1; }

.product-title, .price, .sizes, .colors {
  text-transform: UPPERCASE;
  font-weight: bold; }

.checked, .price span {
  color: #ff9f1a; }

.product-title, .rating, .product-description, .price, .vote, .sizes {
 }

.product-title {
  margin-top: 0; }

.size {
  margin-right: 10px; }
  .size:first-of-type {
    margin-left: 40px; }

.color {
  display: inline-block;
  vertical-align: middle;
  margin-right: 10px;
  height: 2em;
  width: 2em;
  border-radius: 2px; }
  .color:first-of-type {
    margin-left: 20px; }

.add-to-cart, .like {
  background: #ff9f1a;
  padding: 1.2em 1.5em;
  border: none;
  text-transform: UPPERCASE;
  font-weight: bold;
  color: #fff;
  -webkit-transition: background .3s ease;
          transition: background .3s ease; }
  .add-to-cart:hover, .like:hover {
    background: #b36800;
    color: #fff; }

.not-available {
  text-align: center;
  line-height: 2em; }
  .not-available:before {
    font-family: fontawesome;
    content: "\f00d";
    color: #fff; }

.orange {
  background: #ff9f1a; }

.green {
  background: #85ad00; }

.blue {
  background: #0076ad; }

.tooltip-inner {
  padding: 1.3em; }

@-webkit-keyframes opacity {
  0% {
    opacity: 0;
    -webkit-transform: scale(3);
            transform: scale(3); }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
            transform: scale(1); } }

@keyframes opacity {
  0% {
    opacity: 0;
    -webkit-transform: scale(3);
            transform: scale(3); }
  100% {
    opacity: 1;
    -webkit-transform: scale(1);
            transform: scale(1); } }
  </style>

 <script language="javascript" type="text/javascript">

 
     function doit_onkeypress(event){
        if (event.keyCode == 13 || event.which == 13){
            searchT();
        }
     }
 
 
    function searchT(){
      var search1 = document.getElementById("searchText").value; 
	  if (search1!=""){
	  location.href="?s="+search1;return false;}
   }
   

   
   
   
   document.addEventListener("DOMContentLoaded", function() {
	   var invalidCharList = "\\\0 \\\' \\b \\n \\r \\t  \\\z  \\\% \\\\ \\\_ ;" ;
	   
	   
	   
	   
	   
	 <?php 
	 if(isset($_GET['ohdelete'])){
		 $user = $_SESSION['username'];
		 $datetime = date('Y-m-d H:i:s');
		 $orID = preg_replace('/\D/', '',  $_GET['ohdelete']);
		 $quantity = 0;
		 $productname = "";
		 $qselect = "SELECT * FROM orderdetailstbl a JOIN productstbl b ON a.order_product_id=b.product_id  WHERE a.order_id='$orID'";
		 $result = mysqli_query($dbCon, $qselect);
		 while($row = mysqli_fetch_array($result)){
			  $quantity = $row['order_quantity'];
			  $productname= $row['product_name'];
			  
		 }
		 $query = "INSERT INTO reportstbl(report_username, report_action, report_product, report_quantity, report_date, report_category) VALUES('$user','cancelled','$productname','$quantity','$datetime','products')";
	     mysqli_query($dbCon, $query);
		 
		 $q = "UPDATE orderdetailstbl b JOIN accountstbl c ON b.account_id=c.account_id SET b.order_status='cancelled' WHERE b.account_id=c.account_id AND c.account_username='$user' AND b.order_date IS NOT NULL AND b.order_id='$orID'";
		 mysqli_query($dbCon, $q);
		 $q2 = "UPDATE orderdetailstbl a JOIN productstbl b ON a.order_product_id=b.product_id SET b.product_available = b.product_available + a.order_quantity WHERE a.order_id='$orID' ";
		 mysqli_query($dbCon, $q2);
		
		 $_SESSION['messagealert'] = "Order Cancelled!";
		 header("Location: ../c/?orhistory");
		 
	 }
	 
	 if(isset($_SESSION['messagealert'])){
		 echo 'alert("'.$_SESSION['messagealert'].'");';
		 $_SESSION['messagealert']=null;

	 }
	 		 if(isset($_GET['orhistory'])){
			 echo " $('#orderHistory').modal('show');";
		 }
	 if(isset($_GET['ror'])){
		 $orderID = preg_replace("/[^0-9]/", '', $_GET['ror']);
		 $query = "DELETE FROM orderdetailstbl WHERE order_id='$orderID'";
		 mysqli_query($dbCon, $query);
		 $_SESSION['m'] = "Order Deleted!\\n";
		 echo 'location.href="?or&m";return false;';
	 }
		
        if(isset($_GET['or'])){
			echo " $('#orders').modal('show');";
		 if(isset($_GET['m'])){
			echo "alert('".$_SESSION['m']."');";
	     }
	 }
		
		if(isset($_GET['pSI'])&&isset($_GET['uSI'])){
			$_SESSION['passSI'] = trim($_GET['pSI']);
			$_SESSION['userSI'] = trim($_GET['uSI']);
			$_SESSION['signINBOOL'] = true;
			$_SESSION['signUPBOOL'] = false;
			echo 'location.href="?login";return false;';
		}
		else if(isset($_GET['eSU'])&&isset($_GET['uSU'])&&isset($_GET['pSU'])){
			$_SESSION['gendSU'] = trim($_GET['gSU']);
			$_SESSION['passSU'] = trim($_GET['pSU']);
			$_SESSION['passSU2'] = trim($_GET['pSU2']);
			$_SESSION['userSU'] = trim($_GET['uSU']);
			$_SESSION['emaiSU'] = trim($_GET['eSU']);
			$_SESSION['roboSU'] = trim($_GET['rSU']);
			$_SESSION['signINBOOL'] = false;
			$_SESSION['signUPBOOL'] = true;
			echo 'location.href="?register";return false;';
		}
		if($_SESSION['signINBOOL']==true&&(isset($_GET['login']))){
			$error = "";
			$userSI = $_SESSION['userSI'];
			$passSI = $_SESSION['passSI'];
			if(empty($userSI)){
				$error += "Empty Username Field\\n";
			}
			if(empty($passSI)){
				$error += "Empty Password Field\\n";
			}
			$checkForInvalidChar = checkValid(($passSI.$userSI));
			if($checkForInvalidChar==true){
				$error .= "Invalid Characters Used!\\n Please do not Use\\n '+invalidCharList+'";
			}
			
			
			if(empty($error)){//if Input NOT EMPTY!
			    $encryptedPass = crypt($passSI,'$2a$09$qpwoeirutyalskdj3adasd$');
				$query = "SELECT * FROM accountstbl WHERE (account_username='$userSI' OR account_email='$userSI') AND account_password='$encryptedPass'";
		        $results = mysqli_query($dbCon, $query);
				  if (mysqli_num_rows($results) >= "1") {
                      echo "alert('Logged in!');";
					  $_SESSION['username'] = getUsername($userSI) ;
					  $_SESSION['loggedIN'] = true;
					  $_SESSION['user'] = getUserCat(getUsername($userSI));
					  if ( $_SESSION['user']=="admin"||$_SESSION['user']=="staff"){
						   
						  header("Location: ../c/admin");
						  ob_end_flush();
						 
					  }
				  }
				  else{
					  $error .= "User does not Exist or Invalid Password!";
					  errorLoginRegister($error);
				  }
			}
			else{errorLoginRegister($error);}//Error Message
			}
		
			
		else if($_SESSION['signUPBOOL'] == true&&(isset($_GET['register']))){
			$error = "";
			

			//Sessions to PHP variables
			$gendSU = $_SESSION['gendSU'];
			$passSU = $_SESSION['passSU'];
			$passSU2 = $_SESSION['passSU2'];
			$userSU = $_SESSION['userSU'];
			$emaiSU = $_SESSION['emaiSU'];
			$roboSU = $_SESSION['roboSU'];
			
			//Error Checking 
			try{
				
			   $stringT = "";
			   for ($x = strlen($emaiSU); $x != strlen($emaiSU)-5; $x--){
				   $stringT =  $emaiSU[$x] + $stringT;
			   }
			   if ($stringT != ".com"){
				   $error += "Invalid Email\\n";
			   }
			if(empty($userSU)){$error += "Empty Username Field\\n";}
			if ($roboSU=="robot"){$error .= "You have selected you are a robot!\\n";}
			if ($passSU!=$passSU2){$error .= "Password is not the same!\\n";}
			if (empty($passSU)&&empty($passSU2)){$error .= "Password is Empty Spaces as Password aren't allowed!\\n";}
			else if (strlen($passSU)<=7){$error .= "Your password is too short!\\n";}
			$checkForInvalidChar = checkValid(($passSU.$userSU.$emaiSU.$roboSU.$gendSU));
			if($checkForInvalidChar==true){
				$error .= "Invalid Characters Used!\\n Please do not Use\\n '+invalidCharList+'";
			}

			//If no Error, Procceed
			if(empty($error)){
				$query = "SELECT * FROM accountstbl WHERE account_username='$userSU' OR account_username='$emaiSU'";
		        $results = mysqli_query($dbCon, $query);
				  if (mysqli_num_rows($results) >= "1") {
					  $error .= "Username/Email already exist!\\n";
					  errorLoginRegister($error);
				  }
				  else{
					  $encryptedPass = crypt($passSU,'$2a$09$qpwoeirutyalskdj3adasd$');
					  $insertQ = "INSERT INTO accountstbl(account_username,account_gender,account_email,account_password,account_category) 
					  VALUES('".$userSU."','".$gendSU."','".$emaiSU."','".$encryptedPass."','customer')";
					   mysqli_query($dbCon, $insertQ );
					   //Picture of username default per Gender
					   mysqli_query($dbCon,"UPDATE accountstbl t1 INNER JOIN websitepicturestbl t2 ON t1.account_gender=t2.gender SET t1.account_photo=t2.picture_blob WHERE t1.account_username='".$userSU."'");
					  unset($_SESSION['signUPBOOL']);
					  echo " $('#LOGINREGISTER').modal('show');";
					  echo "alert('Account Created! Login Now!');";
				  }
				
			}//Shows Error
			else{  errorLoginRegister($error);}
		}catch (Exception $e){
	        echo "alert('".$e."');";
		}
		}
		
		
		
	 ?>
	 
	
	});


 

</script>

<script> jQuery.noConflict();  </script><!--Remove Modal related conflict-->

</head >
<body >


<!-- Navigation BAR -->
<section class="menu cid-riwmjbztZl" once="menu" id="menu1-k">   

    <nav class="navbar navbar-expand beta-menu navbar-dropdown align-items-center navbar-fixed-top navbar-toggleable-sm">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>
        <div class="menu-logo">
            <div class="navbar-brand">
                <span class="navbar-logo">
                    <a href="">
                         <img src="assets/images/logo.png" alt="Mobirise" style="height: 3.8rem;">
                    </a>
                </span>
                <span class="navbar-caption-wrap"><a class="navbar-caption text-white display-4" href="?au">Johacar Enterprise</a></span>
            </div>
        </div>
		
		
		
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
		
		<div class="d-flex justify-content-center h-100" style="padding-right:5px;">
        <div class="searchbar">
          <input class="search_input" onkeypress="doit_onkeypress(event)" type="text" name="searchText" id="searchText" placeholder="Search...">
          <a onclick="searchT()" class="mbri-search search_icon"><i  onclass="fas fa-search"></i></a>
        </div></div>
            <ul class="navbar-nav nav-dropdown" data-app-modern-menu="true" ><li class="nav-item" >
                    <a class="nav-link link text-white display-4" onclick="location.href='?'+search;return false;"><span class="mbri-home mbr-iconfont mbr-iconfont-btn"></span>
                        Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link text-white display-4" onclick="location.href='?pl'+search;return false;"><span class="mbri-shopping-bag mbr-iconfont mbr-iconfont-btn"></span>
                        
                        Products
                    </a>
                </li>
				<?php if($_SESSION['loggedIN'] == true&&$_SESSION['username']!=null){
					     echo '<li class="nav-item dropdown open"><a class="nav-link link text-white dropdown-toggle display-4"  data-toggle="dropdown-submenu" aria-expanded="true">
                         '.ucfirst((strlen($_SESSION['username'])>10)? substr(strtolower($_SESSION['username']),0,10).'...': strtolower($_SESSION['username'])).'
						<img class="img-profile rounded-circle" style="width:50px;height:50px;margin-left:10px;" src="'.getUserPhoto($_SESSION['username']).'" >
                    </a><div class="dropdown-menu" style="border-radius: 5px 5px 5px 5px ;  ">
					<a class="text-white dropdown-item display-8" data-toggle="modal" data-target="#accountSettings" aria-expanded="true">Account Settings</a>
					<a class="text-white dropdown-item display-8" data-toggle="modal" data-target="#orders" aria-expanded="true">Orders</a>
					<a class="text-white dropdown-item display-8" data-toggle="modal" data-target="#orderHistory" aria-expanded="true">Order History</a>
					<a class="text-white dropdown-item display-8" data-toggle="modal" data-target="#logoutModal" aria-expanded="true">Logout</a></div></li></ul>';
				     } 
				      else{
						  echo '</ul><div class="navbar-buttons mbr-section-btn"> <a class="btn btn-sm btn-primary display-4"  data-toggle="modal" data-target=".bs-modal-sm"><span class="mbri-user mbr-iconfont mbr-iconfont-btn"></span>Login/Register</a></div>';
					  }
				?>
            
        </div>
    </nav>
</section>


<?php   //SHOWS WEBSITE CONTENT
if(isset($_GET['logout'])){
		session_destroy();
		header("Location: ../c/");
	}
   if(isset($_GET['s'])&&!empty(preg_replace("/[^A-Za-z0-9 ]/", '', $_GET['s']))){//Search 
	   $searchItem = preg_replace("/[^A-Za-z0-9 ]/", '', $_GET['s']);
	   $rowResults = searchProduct($searchItem);

	   $resultNum = count($rowResults);
	   $productNum = 0;
	   
	   echo '<section class="counters1 counters cid-riwERvnl7f" id="counters1-1c" style="padding-bottom:30px;background-color: #272727;">
        <div class="container" style="background-color:#272727; ">
        <h2 class="mbr-section-title pb-3 align-center mbr-fonts-style display-2" style="padding-top:50px;color:white;">
            '.$resultNum.' Search results for:</h2>
        <h3 class="mbr-section-subtitle mbr-fonts-style display-5" >
            '.$searchItem.'</h3></div></section>';
	   
	   
	      if($resultNum!='0'){
				   while($resultNum != 0){
					   $columnPerRow = 4;
					   
					   
					   if($resultNum<4){ $columnPerRow=$resultNum;}
					   echo '<section class="features3 cid-riwtsf9ebG" id="features3-t" style="'. (($productNum==0)? "padding:10px 0px 0px 0px;":(($resultNum<4)? "padding: 0px 0px 80px 0px;":"padding:0px 0px 30px 0px;")).'"><div class="container"><div class="media-container-row" >';
					   
					   $resultNum-=$columnPerRow;
					   for ($counter = 0; $counter<$columnPerRow; $counter++){
						   $pID = $rowResults[$productNum];
						   $details = getProductDetails($pID);
						   $productNum++;

						   
						   
						   showResult($details);
						   
						   
						   
					   }
					   
					   echo '</div></div></section></section>';
				   }
		  }
		  else{
			  echo '<section class="mbr-section content4 cid-riwGzKkYW3" id="content4-1d" style="background-color: #272727;"> <div class="container" style="background-color: #272727;"> <div class="media-container-row"> <div class="title col-12 col-md-8"><h2 class="align-center pb-3 mbr-fonts-style display-2" style="font-size:300%;color:white;">  NO SEARCH RESULT!</h2></div></div> </div></section>';
		  }
   }
   else if(isset($_GET['p'])&&!empty(preg_replace("/[^A-Za-z0-9 ]/", '', $_GET['p']))){//Per Products VIEW
   $productID = preg_replace("/[^A-Za-z0-9 ]/", '', $_GET['p']);
   $pID = "";
	   $query="SELECT product_id from productstbl where product_id='".$productID."' ";
		   
			$result=$dbCon->query($query);
			while($row = $result->fetch_assoc()) {
			$pID = $row['product_id'];	
		    }
			if (!empty($pID)){
				$details = getProductDetails($pID);

					echo '<div class="container-fluid" style="padding-top:100px;padding-bottom:80px;background-color: #ddd;">
							<div class="container" >
									<div class="card" style="background-color: #ddd;">
										<div class="container-fluid" style="background-color: #ddd;width:80%;">
											<div class="wrapper row" >
													
													<div class="preview-pic tab-content" >
													  <img style="height:60%; " src="';  echo productPicture($details[0]);  echo '" />
													 
													</div>

													
												<div class="details col-md-6">
													<h2 class="product-title">
													'.$details[1].'
													</h2>

													
													<h3 class="price"><span>
													₱'.$details[6].'.00  <b style="color:red;font-size:60%">'. (($details[5]>0)? $details[5].' Stocks Available' : 'No Stocks available').'</b>
													</span></h3>
													<p class="product-description">
													'.$details[2].'
													</p>
													'.((empty($details[3]))? '': '<p class="product-description">
													'.bulletForm($details[3]).'
													</p>').'
													'.
													(($_SESSION['loggedIN']== true&&$details[5]>0) ?  '<form action="order.php" method="POST"><input type="number" min="1"  style="width: 100px;margin-left:10px;" value="1" max="'.$details[5].'" name="orderNum">
													<input type="hidden" value="'.$productID.'" name="pid">
													<div class="action">
														<button type="submit" class="add-to-cart btn btn-default"  name="addbtn">add to cart</button></form>
														
													</div>' : '').'
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>';
			}
			else{
				errorPage();
			}
   }
   else if(isset($_GET['pl'])){//Product List
       $categories = array('wellness','beauty','beverage','beaute');
	    
	      for($counterCat = 0; $counterCat<4; $counterCat++){
			  echo '<span id="'.$categories[$counterCat].'"></span> <section class="counters1 counters cid-riNEpKKqj0"  style="'.(($counterCat==0)? "padding:110px 0px 20px 0px;":"padding:20px;").'"  >
			         <div class="container"  >
						<h2 class="mbr-section-title pb-3 align-center mbr-fonts-style display-2"  >
							'.ucfirst($categories[$counterCat]).' Products
						</h2></div></section>';
							   $rowResults = getProductsFromCategory($categories[$counterCat]);
							   $resultNum = count($rowResults);
							   $productNum = 0;
							   $num = $resultNum;
							   while($resultNum != 0){
							   $columnPerRow = 4;
							   
							   
							   if($resultNum<4){ $columnPerRow=$resultNum;}
							   echo '<section class="features3 cid-riwtsf9ebG" id="features3-t" style="'. (($productNum==0&&$num<=4)? "padding:5px 0px 40px 0px;":(($resultNum<4)? "padding: 0px 0px 30px 0px;":"padding:0px 0px 30px 0px;")).'"><div class="container"><div class="media-container-row" >';
							   
							   $resultNum-=$columnPerRow;
							   for ($counter = 0; $counter<$columnPerRow; $counter++){
								   $pID = $rowResults[$productNum];
								   $details = getProductDetails($pID);
								   $productNum++;

								   
								   
								   showResult($details);
								   
								   
								   
							   }
							   
							   echo '</div></div></section>';
				}
		  }
   }
   else if(isset($_GET['au'])){//About us
	   print '
<section class="counters4 counters cid-riNDCwSUv1" id="counters4-1e">

    

    

    <div class="container pt-4 mt-2">
        <h2 class="mbr-section-title pb-3 align-center mbr-fonts-style display-2">Why Choose Us</h2>
        <h3 class="mbr-section-subtitle pb-5 align-center mbr-fonts-style display-5">
            Here\'s our Reasons Why 
        </h3>
        <div class="media-container-row">
            <div class="media-block m-auto" style="width: 49%;">
                <div class="mbr-figure">
                    <img src="assets/images/background6.jpg" alt="" title="">
                </div>
            </div>
            <div class="cards-block">
                <div class="cards-container">
                    <div class="card px-3 align-left col-12">
                        <div class="panel-item p-4 d-flex align-items-start">
                            <div class="card-img pr-3">
                                <h3 class="img-text d-flex align-items-center justify-content-center">
                                    1
                                </h3>
                            </div>
                            <div class="card-text">
                                <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">Royalè is a 100% Filipino-owned corporation</h4>
                                <p class="mbr-content-text mbr-fonts-style display-7">
                                    Let\'s support each other in becoming wealthy.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card px-3 align-left col-12">
                        <div class="panel-item p-4 d-flex align-items-start">
                            <div class="card-img pr-3">
                                <h3 class="img-text d-flex align-items-center justify-content-center">
                                    2
                                </h3>
                            </div>
                            <div class="card-text">
                                <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">Lifetime Membership</h4>
                                <p class="mbr-content-text mbr-fonts-style display-7">
                                    You can choose beetween 3 choices whether you\'ll be a Sub-distributor ,Independent Distributor, or full pledged distributor.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card px-3 align-left col-12">
                        <div class="panel-item p-4 d-flex align-items-start">
                            <div class="card-img pr-3">
                                <h3 class="img-text d-flex align-items-center justify-content-center">
                                    3
                                </h3>
                            </div>
                            <div class="card-text">
                                <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">Our Products our Certified</h4>
                                <p class="mbr-content-text mbr-fonts-style display-7">
                                        Royale products are certified/approved by lots of certifying bodies.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card px-3 align-left col-12">
                        <div class="panel-item p-4 d-flex align-items-start">
                            <div class="card-img pr-3">
                                <h3 class="img-text d-flex align-items-center justify-content-center">
                                    4
                                </h3>
                            </div>
                            <div class="card-texts">
                                <h4 class="mbr-content-title mbr-bold mbr-fonts-style display-7">
                                    Trainings are Available
                                </h4>
                                <p class="mbr-content-text mbr-fonts-style display-7">
                                      Yes, free trainings are given in Royale branches.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>';
   }
   else { //Website Home
   
	   print '<!-- Slide Show -->
<div class="container-fluid" style="padding:0px;background-color: white;">
  <h2>Carousel Example</h2>
  <div id="myCarousel" class="carousel slide" data-ride="carousel" >
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" >';
	
	for ($x = 1; $x <=3; $x++){
		$details = getSlideDetails($x);
		echo '<div class="item '.(($x==1)? "active" : "").' img-responsive">
        <img src=';   slideshowPicture($x);   print ' alt="'.$details[0].'" style="width:100%;" >
        <div class="carousel-caption">
          <h1>'.$details[0].'</h1>
          <p>'.$details[1].'</p><br>
        </div>
      </div>';
	}
	
  
   echo' </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>

<!-- 4 Categories -->
<section class="counters1 counters cid-riNEpKKqj0" id="counters1-1g">

    

    

    <div class="container">
        <h2 class="mbr-section-title pb-3 align-center mbr-fonts-style display-2" >
            Product Categories
        </h2>
        <h3 class="mbr-section-subtitle mbr-fonts-style display-5">
            We\'ve got Everything you need
        </h3>
        
        <div class="container pt-4 mt-2">
            <div class="media-container-row">
                <div class="card p-3 align-center col-12 col-md-6 col-lg-3">
                    <div class="panel-item p-3">
                        <div class="card-img pb-3" >
						  <a onclick="location.href=\'?pl#wellness\'+search;return false;">
                            <img style="height:120px;width:auto;" style= "display: block;" src="assets/images/category1.PNG" alt="New York" style="width:100%;">
                           </a>
						</div>

                        <div class="card-text">
						
                            
							<h4 class="count mbr-content-title mbr-fonts-style display-2">
                                '.count(getProductsFromCategory('wellness')).'
                            </h4>
							<b class="pt-3 pb-3 mbr-fonts-style display-2">
                                  Wellness
                            </b>
							<p class="mbr-content-text mbr-fonts-style display-7">
                                 Products
                            </p>
                            
                            <p class="mbr-content-text mbr-fonts-style display-7">
                                 There are two kinds of people in this world: those who take antioxidants now and those who take chemotherapy later.
                            </p>
                        </div>
                    </div>
                </div>


                <div class="card p-3 align-center col-12 col-md-6 col-lg-3">
                    <div class="panel-item p-3">
                        <div class="card-img pb-3">
						<a onclick="location.href=\'?pl#beauty\'+search;return false;">
                              <img style="height:120px;width:auto;" style= "display: block;" src="assets/images/category2.PNG" alt="New York" style="width:100%;">
                        </a>
						</div>
                        <div class="card-text">
                            
							<h4 class="count mbr-content-title mbr-fonts-style display-2">
                                 '.count(getProductsFromCategory('beauty')).'
                            </h4>
							<b class="pt-3 pb-3 mbr-fonts-style display-2">
                                Beauty
                            </b>
							<p class="mbr-content-text mbr-fonts-style display-7">
                                 Products 
                            </p>
                            
                            <p class="mbr-content-text mbr-fonts-style display-7">
                                 Range of scientifically formulated NUTRIENT AND ANTI OXIDANT-ENRICHED skin care products.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card p-3 align-center col-12 col-md-6 col-lg-3">
                    <div class="panel-item p-3">
                        <div class="card-img pb-3">
						<a  onclick="location.href=\'?pl#beverage\'+search;return false;">
                            <img style="height:120px;width:auto;" style= "display: block;" src="assets/images/category3.PNG" alt="New York" style="width:100%;">
                        </a>
						</div>
                        <div class="card-text">
                            
							<h4 class="count mbr-content-title mbr-fonts-style display-2">
                                 '.count(getProductsFromCategory('beverage')).'
                            </h4>
							<b class="pt-3 pb-3 mbr-fonts-style display-2">
                                Beverage
                            </b>
							<p class="mbr-content-text mbr-fonts-style display-7">
                                 Products 
                            </p>
                            
                            <p class="mbr-content-text mbr-fonts-style display-7">
                                 Give yourself a snack to fill-in.
                            </p>
                        </div>
                    </div>
                </div>


                <div class="card p-2 align-center col-12 col-md-6 col-lg-3">
                    <div class="panel-item p-3">
                        <div class="card-img pb-3">
						<a  onclick="location.href=\'?pl#beaute\'+search;return false;">
                            <img style="height:120px;width:auto;"onclick="location.href=\'?pl#beaute\'+search;return false;" style= "display: block;" src="assets/images/category4.PNG" alt="New York" style="width:100%;">
                        </a>
						</div>

                        <div class="card-texts">
                            
							<h4 class="count mbr-content-title mbr-fonts-style display-2">
                                 '.count(getProductsFromCategory('beaute')).'
                            </h4>
							<b class="pt-3 pb-3 mbr-fonts-style display-2">
                                Beaute
                            </b>
							<p class="mbr-content-text mbr-fonts-style display-7">
                                 Products 
                            </p>
                            
                            <p class="mbr-content-text mbr-fonts-style display-7">
                                 Your body deserves a make-over.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
</section>

<!-- Ratings PART -->
<section class="testimonials1 cid-riwuZdgUJz" id="testimonials1-w"  style="text-align:center;">

    
    
    
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 align-center">
                <h2 class="pb-3 mbr-fonts-style display-2" style="color:white;">
                    WHAT OUR FANTASTIC USERS SAY
                </h2>

            </div>
        </div>
    </div>';


	
   $results = mysqli_query($dbCon, "SELECT * FROM ratingstbl");
	$reviewCount = mysqli_num_rows($results);
    $randReview = UniqueRandomNumbersWithinRange('1', $reviewCount, '3');	
    
	$dataR1 = getReviewsData($randReview[0]);
	$dataR2 = getReviewsData($randReview[1]);
	$dataR3 = getReviewsData($randReview[2]);
	
    echo '<div class="container pt-3 mt-2">
        <div class="media-container-row">
            <div class="mbr-testimonial p-3 align-center col-12 col-md-6 col-lg-4">
                <div class="panel-item p-3">
                    <div class="card-block" style="height:300px;">
                        <div class="testimonial-photo">
                            <img src="data:image/png;base64,'.base64_encode($dataR1[2]).'">
                        </div>
                        <p class="mbr-text mbr-fonts-style display-7">
                           '.$dataR1[3].'
                        </p>
                    </div>
                    <div class="card-footer">
                        <div class="mbr-author-name mbr-bold mbr-fonts-style display-7">
                            '.$dataR1[0].'
                        </div>
                        <small class="mbr-author-desc mbr-italic mbr-light mbr-fonts-style display-7">
                             '.ucfirst($dataR1[1]).'
                        </small>
                    </div>
                </div>
            </div>
			


            <div class="mbr-testimonial p-3 align-center col-12 col-md-6 col-lg-4">
                <div class="panel-item p-3">
                    <div class="card-block" style="height:300px;">
                        <div class="testimonial-photo">
                            <img src="data:image/png;base64,'.base64_encode($dataR2[2]).'">
                        </div>
                        <p class="mbr-text mbr-fonts-style display-7">
                           '.$dataR2[3].'
                        </p>
                    </div>
                    <div class="card-footer">
                        <div class="mbr-author-name mbr-bold mbr-fonts-style display-7">
                            '.$dataR2[0].'
                        </div>
                        <small class="mbr-author-desc mbr-italic mbr-light mbr-fonts-style display-7">
                               '.ucfirst($dataR2[1]).'
                        </small>
                    </div>
                </div>
            </div>

            <div class="mbr-testimonial p-3 align-center col-12 col-md-6 col-lg-4">
                <div class="panel-item p-3">
                    <div class="card-block" style="height:300px;">
                        <div class="testimonial-photo">
                             <img src="data:image/png;base64,'.base64_encode($dataR3[2]).'">
                        </div>
                        <p class="mbr-text mbr-fonts-style display-7">
                           '.$dataR3[3].'
                        </p>
                    </div>
                    <div class="card-footer">
                        <div class="mbr-author-name mbr-bold mbr-fonts-style display-7">
                            '.$dataR3[0].'
                        </div>
                        <small class="mbr-author-desc mbr-italic mbr-light mbr-fonts-style display-7">
                              '.ucfirst($dataR3[1]).'
                        </small>
                    </div>
                </div>
            </div>';

            

            

           
        echo '</div>'.
		(($_SESSION['loggedIN']== true) ?'<br><button type="button" class="btn btn-primary" style="font-size:120%" data-toggle="modal" data-target="#ratingsModal" aria-expanded="true">Write a Review</button>':'')
		.'</div>   
    </section>';}
?>


 
<!-- Bottom -->
<section class="cid-riwvlFoijk id="footer2-10">
    <div class="container">
        <div class="media-container-row content mbr-white">
            <div class="col-12 col-md-3 mbr-fonts-style display-7">
                <p class="mbr-text">
                    <strong>Address</strong>
                    <br>
                    <br>Blk. 2 &nbsp;Lot 39 Sampaguita St. Pembo, Makati City<br>
                    <br>
                    <br><strong>Contacts</strong>
                    <br>
                    <br>Email: support@something.com
                    <br>Phone: +(63)927-4528152/<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; +(63)949-8673311</p>
            </div>
            <div class="col-12 col-md-3 mbr-fonts-style display-7">
                <p class="mbr-text">
                    <strong>Links</strong>
                    <br>
                    <br><a class="text-primary" href="">Facebook Page</a>&nbsp;<br><a class="text-primary" onclick="location.href='?au';return false;">About Us</a>&nbsp;<br><a class="text-primary" >Other Page</a>&nbsp;<br>
                    <br><strong>Feedback</strong>
                    <br>
                    <br>Please send us your ideas, bug reports, suggestions! Any feedback would be appreciated.
                </p>
            </div>
            <div class="col-12 col-md-6">
                <div class="google-map"><iframe frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAEIpgj38KyLFELm2bK9Y7krBkz1K-cMq8&amp;q=place_id:EkBCbGsuIDIgIExvdCwgMzkgU2FtcGFndWl0YSBTdCwgTWFrYXRpLCBNZXRybyBNYW5pbGEsIFBoaWxpcHBpbmVzIisaKQoaEhgKFAoSCStyIAyOyJczETJrTtm_33IhECcSC0Jsay4gMiAgTG90" allowfullscreen=""></iframe></div>
            </div>
        </div>
        <div class="footer-lower">
            <div class="media-container-row">
                <div class="col-sm-12">
                    <hr>
                </div>
            </div>
            <div class="media-container-row mbr-white">
                <div class="col-sm-6 copyright">
                    <p class="mbr-text mbr-fonts-style display-7">
                        © Copyright 2019 Johacar Enterprise - All Rights Reserved
                    </p>
                </div>
                <div class="col-md-6">
                    
                </div>
            </div>
        </div>
    </div>
</section>

<?php
 if ($_SESSION['loggedIN']== true){
	 
	 
	 
	 function checkOrderCount(){include('database_connection.php');
	     $user = $_SESSION['username'];
		 $query = "SELECT a.product_id, a.product_name, a.product_picture, a.product_available, a.product_category FROM accountstbl c,productstbl a JOIN orderdetailstbl b ON b.order_product_id=a.product_id WHERE b.account_id=c.account_id AND c.account_username='$user' AND b.order_date IS NULL";
	     $result = mysqli_query($dbCon, $query);
		 $numRows = mysqli_num_rows($result);
		 return $numRows;
	 }
	 function getOrderDetails(){include('database_connection.php');
	     $user = $_SESSION['username'];
		 $data = array();
		 $count = 0;
		 $query = "SELECT a.product_id, a.product_name, a.product_picture, a.product_available, a.product_category, a.product_price, b.order_quantity, b.order_id FROM accountstbl c,productstbl a JOIN orderdetailstbl b ON b.order_product_id=a.product_id WHERE b.account_id=c.account_id AND c.account_username='$user' AND b.order_date IS NULL";
	     $result = mysqli_query($dbCon, $query);
		 while($row = mysqli_fetch_array($result)){
			 $data[$count][0] = $row['product_id'];
			 $data[$count][1] = $row['product_name'];
			 $data[$count][2] = 'data:image/png;base64,'.base64_encode($row['product_picture']);
			 $data[$count][3] = $row['product_available'];
			 $data[$count][4] = $row['product_category'];
			 $data[$count][5] = $row['product_price'];
			 $data[$count][6] = $row['order_quantity'];
			 $data[$count][7] = $row['order_id'];
			 $count++;

		 }

		 return $data;
	 }
	 function showOrders(){
		 $orders = '<div class="row">
            <table class="table table-hover" >
                <thead >
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Total</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody >';
				$orderData = getOrderDetails();
				$perproducttotal = 0;
				$subtotal = 0;
				$total = 0;
				$insurance = 0;
				
				for ($count = 0; $count<count($orderData); $count++){
					$perproducttotal = $orderData[$count][6]*$orderData[$count][5];
					$subtotal += $perproducttotal;
                   $orders .= ' <tr >
                        <td class="col-sm">
                        <div class="media">
                            <a class="thumbnail pull-left" onClick="location.href=\'?p='.$orderData[$count][0].'\';return false;"> <img class="media-object" src="'.$orderData[$count][2].'" style="width: 72px; height: 72px;"> </a>
                            <div class="media-body"  style="max-width:250px;"> 
                                <h4 class="media-heading"><a onClick="location.href=\'?p='.$orderData[$count][0].'\';return false;">'.$orderData[$count][1].'</a></h4>
                                <h5 class="media-heading"> Category: <a onClick="location.href=\'?pl#'.$orderData[$count][4].'\';return false;">'.$orderData[$count][4].'</a></h5>
                                <span>Status: '. (($orderData[$count][3]>0) ? '</span><span class="text-success"><strong>'.$orderData[$count][3].' Stocks</strong></span>' : '</span><span class="text-danger"><strong> Out of Stock</strong></span>').'
                            </div>
                        </div></td>
                        <td class="col-sm" style="text-align: center">
						<input type="hidden" name="data[pid]['.$count.']" value="'.$orderData[$count][0].'">
                        <input type="text" class="form-control" id="ordernum'.$count.'"  name="data[quantity]['.$count.']" value="'.(($orderData[$count][6]<$orderData[$count][3])? $orderData[$count][6] : $orderData[$count][3]).'" onkeyup="  
                        var textID = document.getElementById(\'ordernum'.$count.'\'); 
                        var orderTotID = document.getElementById(\'orderTot'.$count.'\'); 
                        var max = '.$orderData[$count][3].';
                        var num = textID.value.replace(/\\D/g,\'\'); 
                        var price = document.getElementById(\'orderPrice'.$count.'\').innerHTML;
                        if (num>max){
						     textID.value = max; 
						}
						else{
						    textID.value = num; 
						}
                       
                        
						 
                        var orderSubtotalID = document.getElementById(\'orderSubtotal\');  
						var orderFeeID = document.getElementById(\'orderFee\');  
                        var orderTotalID = document.getElementById(\'orderTotal\'); 
						
						var subtotalD = orderSubtotalID.innerHTML;
						var orderTotD = orderTotID.innerHTML;
						var fee = orderFeeID.innerHTML;
						
                        var subtotal = Number(subtotalD) - Number(orderTotD);
                        
						

                         orderTotID.innerHTML = num*price;
                         orderSubtotalID.innerHTML =  subtotal+(num*price);
                          orderTotalID.innerHTML = subtotal+(num*price) + Number(fee) ;
                         " >
                         
                        </td>
                        <td class="col-sm-1 col-md-1 text-center"><b>₱</b><strong id="orderPrice'.$count.'">'.$orderData[$count][5].'</strong><b>.00</b></td>
                        <td class="col-sm-1 col-md-1 text-center"><b>₱</b><strong id="orderTot'.$count.'">'.$perproducttotal.'</strong><b>.00</b></td>
                        <td class="col-sm-1 col-md-1">
                        <button type="button" class="btn btn-danger"  style="padding:0px;min-width:150px;min-height:50px;" onClick="location.href=\'?ror='.$orderData[$count][7].'\';return false;">
                            <span class="glyphicon glyphicon-remove"></span> Remove
                        </button></td>
                    </tr>';

				}
				$total += $subtotal + $insurance;
					
				$orders .= '
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td colspan="2" style="text-align:right;"><h5>Subtotal:</h5></td>
                        <td class="text-left"><h5><b>₱</b><strong id="orderSubtotal">'.$subtotal.'</strong><b>.00</b></h5></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td colspan="2" style="text-align:right;"><h5>Reservation fee:</h5></td>
                        <td class="text-left"><h5><b>₱</b><strong id="orderFee">'.$insurance.'</strong><b>.00</b></h5></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td colspan="2" style="text-align:right;"><h3>Total:</h3></td>
                        <td class="text-left"><h3><b>₱</b><strong id="orderTotal">'.$total.'</strong><b>.00</b></h3></td>
                    </tr>

                </tbody>
            </table>
    </div>';
	
		return $orders; 
	 }
	     
	 function checkOrderHistoryCount(){include('database_connection.php');
	 $user = $_SESSION['username'];
	     $num = 0;
		 $query = "SELECT a.product_id, a.product_name, a.product_picture, a.product_price, a.product_category, b.order_quantity, b.order_date FROM accountstbl c,productstbl a JOIN orderdetailstbl b ON b.order_product_id=a.product_id WHERE b.account_id=c.account_id AND c.account_username='$user' AND b.order_date IS NOT NULL ORDER BY b.order_date";
	     $result = mysqli_query($dbCon, $query);
		 return mysqli_num_rows($result);
	 }
	 
	 function getOrderHistoryDetails(){include('database_connection.php');
	     $user = $_SESSION['username'];
         $data= array();
		 $count = 0;
		 $query = "SELECT a.product_id, a.product_name, a.product_price, a.product_category, b.order_quantity, b.order_date, b.order_status , b.order_id FROM accountstbl c,productstbl a JOIN orderdetailstbl b ON b.order_product_id=a.product_id WHERE b.account_id=c.account_id AND c.account_username='$user' AND b.order_date IS NOT NULL ORDER BY b.order_date DESC";
	     $result = mysqli_query($dbCon, $query);
		 while($row = mysqli_fetch_array($result)){
			 $data[$count][0] = $row['product_id'];
			 $data[$count][1] = $row['product_name'];
			 $data[$count][2] = $row['product_price'];
			 $data[$count][3] = $row['product_category'];
			 $data[$count][4] = $row['order_quantity'];
			 $data[$count][5] = $row['order_date'];
			 $data[$count][6] = $row['order_status'];
			 $data[$count][7] = $row['order_id'];
			 $count++;
		 }
		 return $data;
	 }
	 
	 function showOrderHistory(){include('database_connection.php');
	     $string = "";
         $ohCount = checkOrderHistoryCount();
		 if($ohCount>0){
			$ohData = getOrderHistoryDetails();
			 for($count = 0; $count < $ohCount; $count++){
				 $string .= '<span class="row" >
				  <div class="col-md-12"  >
					<div class="row">
					  <div class="col-md-12">
					  '.(($ohData[$count][6]=='pending')? '<a onclick="location.href=\'?ohdelete='.$ohData[$count][7].'\';return false;"><div style="margin-left:5px;" class="float-right"><label class="label label-warning">Cancel</label> </div></a>' : '').'
					  <div class="float-right"><label class="label label-'.(($ohData[$count][6]=='pending')? 'info' : (($ohData[$count][6]=='recieved')? "success":"danger" )).'">'.ucfirst($ohData[$count][6]).'</label> </div>
						
						
						<span><strong>'.$ohData[$count][1].'</strong></span> <span class="label label-info">'.ucfirst($ohData[$count][3]).'</span><br>
						Quantity : '.$ohData[$count][4].', cost: ₱'.($ohData[$count][4]*$ohData[$count][2]).'.00 <br>
					  </div>
					  <div class="col-md-12">
						order made on: '.$ohData[$count][5].' by '.ucfirst($_SESSION['username']).'
					  </div>
					</div>
				  </div>
				</span>';
			 }
		 }
		 else{
			 $string .= '<div class="panel-footer " style="text-align:center;" >No Order History</div>';
		 }
		 return $string;
	 }
	 echo ' <!-- Modal -->
  <div class="modal fade" id="orderHistory" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
		  <h4 class="modal-title"> <strong>History</strong></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <link rel="stylesheet" type="text/css" href="/admin/vendor/fontawesome-free/css/all.min.css">
			<div class="panel panel-default panel-order">
			  <div class="panel-heading">
				  <strong>Order history</strong>
				  
			  </div>


			<div class="panel-body" id="orderListB">
				'.showOrderHistory().'

			  

			</div>
			
			</div>                    
					</div>
					<div class="modal-footer">
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				  
				</div>
			  </div>
			  
			</div>';
	 echo '
	 <!-- Modal -->
  <div class="modal fade" id="orders" role="dialog" >
    <div class="modal-dialog" style="min-width:62%;">
    
      <!-- Modal content-->
      <div class="modal-content" >
        <div class="modal-header">
          <h4 class="modal-title">Orders</h4>
		   <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <form action="order.php" method="POST">'.((checkOrderCount()<1) ? '<p style="text-align:center;">No orders to show!</p>': showOrders()).'
    

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-default" name="savebtn">
                            <span class="glyphicon glyphicon-shopping-cart" ></span> Continue Shopping
                        </button>
                        <button type="submit" class="btn btn-success" name="orderbtn">
                            Checkout <span class="glyphicon glyphicon-play"></span>
                        </button>
						</form>
        </div>
      </div>
      
    </div>
  </div>
	 
	  <!-- Review Ratings-->
		  <div class="modal fade" id="ratingsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <h1 class="modal-title" id="exampleModalLabel">Review Ratings</h1>
				  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				  </button>
				</div>
				<form action="accountSet.php" method="POST">
				<div class="modal-body">
				<input type="hidden" value="'.$_SESSION['username'].'" name="user">
				<textarea rows="4" cols="48" name="reviewText"></textarea>
				
				</div>
				<div class="modal-footer" style="vertical-align: middle">
				
				  <button style="padding:5px 10px 5px 10px;margin:0px 5px 0px 5px;" class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
				   <button type="submit" name="reviewb" class="btn btn-primary" style="Color:white;padding:5px 10px 5px 10px;margin:0px;" >Submit</button></form>
				</div>
			  </div>
			</div>
		  </div>
	 
	 
	 
	 
	 <div class="modal fade" id="accountSettings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
			<div class="modal-dialog" role="document" style="min-width:50%;">
			  <div class="modal-content">
				<div class="modal-header">
				  <h1 class="modal-title" id="exampleModalLabel">Account Settings</h1>
				  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				  </button>
				</div>
				
				<div class="modal-body">
				   <p>Please be Advised, to Change Settings Respectively.</p>
		  
		  <table class="table table-striped">
			
			<tbody>
			  <tr>
				<td>Change Email</td>
				<td><form id="cemail" style="display: none;" action="accountSet.php" method="POST"><h4>Email Change</h4>
				
				
				
				<div class="row">
					<div class="col-sm-2" style="text-align:right;">Email:
					</div>
					<div class="col-sm" ><input type="hidden" value="'.$_SESSION['username'].'" name="user">
					<input type="email" name="email" required=""><br><input type="submit" name="emailc" value="Change"></form>
					</div>
				</div>
				
				
				</td>
				<td style="width:10%;padding-right:50px;"><a id="edit1" onclick="showStuff(\'cemail\',  this.id); return false;">Edit</a></td>
			  </tr>
			  <tr>
				<td>Change Username</td>
				<td><form id="cuser" style="display: none;"  action="accountSet.php" method="POST"><h4>Username</h4>
				<div class="row">
					<div class="col-sm-3" style="text-align:right;">Username:
					</div>
					<div class="col-sm"><input type="hidden" value="'.$_SESSION['username'].'" name="user">
					<input type="text" name="username" value="'.$_SESSION['username'].'" required=""><br><input type="submit" name="userc" value="Change"></form>
					</div>
				</div>
				</td>
				<td style="width:10%;padding-right:50px;"><a id="edit2" onclick="showStuff(\'cuser\',  this.id); return false;">Edit</a></td>
			  </tr>
			  <tr>
				<td>Change Password</td>
				<td>
				
				<form id="cpass" style="display: none;"  action="accountSet.php" method="POST">
        <h4>Password Change</h4>
		<div>
		<div class="row">
			<div class="col-sm-5" style="text-align:right;">
			  Password:<br>Re-Type Password:
			</div>
			<div class="col-sm">
			  <input type="hidden" value="'.$_SESSION['username'].'" name="user">
			  <input type="text" id="pass1" name="pass1" min="8" required="">
			  <br><input type="text" id="pass2" name="pass2" min="8" required=""><br>
			  <input type="submit" id="passc" name="passc" value="Change">
			</div>
		  </div>
				
</form>
			</td>
				<td style="width:10%;padding-right:50px;"><a id="edit3" onclick="showStuff(\'cpass\',  this.id); return false;">Edit</a></td>
			  </tr>
			  <tr>
			  
				<td>Change Picture</td>
				<td>
				<form id="cpicture" style="display: none;"  action="accountSet.php" method="POST"> 
				<h4>Picture Change</h4>
				<input type="text" id="pictures" name="photoCROP"  style="display: none;" required="">
				
					Select Photo:
					
					
					<input type="hidden" value="'.$_SESSION['username'].'" name="user">
					<input type="file" name="insert_image" value="" id="insert_image" accept="image/*" required onchange="var reader = new FileReader();
						reader.onload = function (event) {
						  $image_crop.croppie(\'bind\', {
							url: event.target.result
						  }).then(function(){
							console.log(\'jQuery bind complete\');
						  });
						}
						reader.readAsDataURL(this.files[0]);
						$(\'#insertimageModal\').modal(\'show\');" />
						<input type="submit" name="picturec" value="Change" ></form>
					
				
				
				</td>
				<td style="width:10%;padding-right:50px;"><a id="edit4" onclick="showStuff(\'cpicture\',  this.id); return false;">Edit</a></td>
			  </tr>
			  <tr >
				<td>Change Gender</td>
				<td class="text-left" style="width:60%;"><form id="cgender" style="display: none;"  action="accountSet.php" method="POST">
				<h4>Gender Change</h4>
			  <input type="radio" name="gender" value="male" required=""> Male<br>
			  <input type="radio" name="gender" value="female"> female<br>
			  <input type="hidden" value="'.$_SESSION['username'].'" name="user">
			  <input type="submit" name="genderc" value="Change"><br></form></td>
				<td style="width:10%;padding-right:50px;"><a id="edit5" onclick="showStuff(\'cgender\',  this.id); return false;">Edit</a></td>
			  </tr>
			  <tr>
				<td></td>
				<td></td>
				<td></td>
			  </tr>
			</tbody>
		  </table>
				
				
				</div>
				
				
				<div class="modal-footer" style="vertical-align: middle">
				
				  <button style="padding:5px 10px 5px 10px;margin:0px 5px 0px 5px;" class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
				</div>
			  </div>
			</div>
		  </div>
	 
	 
<div id="insertimageModal" class="modal" role="dialog" >
					 <div class="modal-dialog" style="width:60%;height:50%;">
					  <div class="modal-content">
						  <div class="modal-header">
							
							<h4 class="modal-title">Crop & Insert Image</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						  </div>
						  <div  class="modal-body" >
							<div class="row">
							
							  <div class="col-md-8 text-center">
								<div id="image_demo" style=" margin-top:30px;"></div>
							  </div>
								
							</div>
						  </div>
						  <div class="modal-footer">
						  <button class="btn btn-success crop_image float-right">Crop & Insert Image</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						  </div>
						</div>
					  </div>
					</div>

					
 

	 <script>
	 					$(document).ready(function(){

					 $image_crop = $(\'#image_demo\').croppie({
						enableExif: true,
						viewport: {
						  width:300,
						  height:300,
						  type:\'square\' //circle
						},
						boundary:{
						  width:300,
						  height:300
						}    
					  });



					  $(\'.crop_image\').click(function(event){
						  
						$image_crop.croppie(\'result\', {
						  type: \'canvas\',
						  size: \'viewport\'
						}).then(function(response){
							var picture1 = response;
						   $(\'#pictures\').val(picture1);
						   $(\'#insertimageModal\').modal(\'hide\');
						   alert(\'Picture Cropped!\');
						});
					  });



					}); 
	 
	    document.addEventListener("DOMContentLoaded", function() {';
		
        			 if(isset($_GET['accountSettings'])){
				 echo " jQuery.noConflict();
				 document.getElementById('accountSettings').style.display = 'block';
				 
				 
				 $('#accountSettings').modal('show'); ";
				if(isset($_GET['m'])){
					echo "alert('".str_replace("."," ",$_GET['m'])."');";
				}
			}
			  
	    echo'});
		
			const signUpForm = document.getElementById("cpass");
			const pass1 = document.getElementById("pass1");
			const pass2 = document.getElementById("pass2");
			const okButton = document.getElementById("passc");
			okButton.disabled = true;
			pass2.addEventListener("keyup", function (event) {
				var len1 = pass1.value.length;
			  if(pass1.value!=pass2.value||len1<8){
				  okButton.disabled = true;
			  }
			  else{
				  okButton.disabled = false;
			  }
			});
			
			pass1.addEventListener("keyup", function (event) {
			  var len2 = pass1.value.length;
			  if(pass1.value!=pass2.value||len2<8){
				  okButton.disabled = true;
			  }
			  else{
				  okButton.disabled = false;
			  }
			});
			  
			okButton.addEventListener("click", function (event) {
			  signUpForm.submit();
			});


		function showStuff(id, editT) {
			document.getElementById("cpass").style.display = "none";
			document.getElementById("cpicture").style.display = "none";
			document.getElementById("cuser").style.display = "none";
			document.getElementById("cemail").style.display = "none";
			document.getElementById("cgender").style.display = "none";
			document.getElementById("edit1").style.display = "block";
			document.getElementById("edit2").style.display = "block";
			document.getElementById("edit3").style.display = "block";
			document.getElementById("edit4").style.display = "block";
			document.getElementById("edit5").style.display = "block";
			document.getElementById(id).style.display = "block";
			document.getElementById(editT).style.display = "none";
		}
	</script>
	 
	 
	 
	 
	 
	 <!-- Logout Modal-->
		  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <h1 class="modal-title" id="exampleModalLabel">Ready to Leave?</h1>
				  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				  </button>
				</div>
				<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
				<div class="modal-footer" style="vertical-align: middle">
				
				  <button style="padding:5px 10px 5px 10px;margin:0px 5px 0px 5px;" class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
				   <button class="btn btn-primary" style="Color:white;padding:5px 10px 5px 10px;margin:0px;" onClick="location.href=\'?logout\';return false;">Logout</button>
				</div>
			  </div>
			</div>
		  </div>';
 }

?>




<!-- Modal -->
<div class="modal fade bs-modal-sm" id="LOGINREGISTER" tabindex="1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="bs-example bs-example-tabs" >
            <ul id="myTab" class="nav nav-tabs" >
              <li style="width:50%;text-align:center;" class="<?php if($_SESSION['signUPBOOL']==true){echo "active";}?>"><a href="#signup" data-toggle="tab">Register</a></li>
              <li style="width:50%;text-align:center;" class="<?php if(($_SESSION['signUPBOOL']!=true&&$_SESSION['signUPBOOL']==true)||($_SESSION['signUPBOOL']==null&&$_SESSION['signUPBOOL']==null)||($_SESSION['signUPBOOL']==false&&$_SESSION['signUPBOOL']==false)){echo "active";}?>"><a href="#signin" data-toggle="tab">Login</a></li>
              
			</ul>
        </div>
      <div class="modal-body">
        <div id="myTabContent" class="tab-content">
        
        <div class="tab-pane fade <?php if(($_SESSION['signUPBOOL']==false&&$_SESSION['signINBOOL']==true)||($_SESSION['signUPBOOL']==null&&$_SESSION['signUPBOOL']==null)||($_SESSION['signUPBOOL']==false&&$_SESSION['signUPBOOL']==false)){echo "active in";}?>" id="signin">
            <form href id="loginFORM" class="form-horizontal">
            <fieldset>
            <!-- Sign In Form -->
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="uSI">Username:</label>
              <div class="controls">
                <input required="" id="uSI" name="uSI" type="text" class="form-control" placeholder="Username007" class="input-medium" required="">
              </div>
            </div>

            <!-- Password input-->
            <div class="control-group">
              <label class="control-label" for="pSI">Password:</label>
              <div class="controls">
                <input required="" id="pSI" name="pSI" class="form-control" type="password" placeholder="********" class="input-medium">
              </div>
            </div>

            <!-- Multiple Checkboxes (inline) -->
			<!-----
            <div class="control-group">
              <label class="control-label" for="rememberme"></label>
              <div class="controls">
                <label  for="rememberme-0">
                  <input type="checkbox" name="remMe" id="remMe" value="remMe">
                  Remember me
                </label>
              </div>
            </div>----->

            <!-- Button -->
            <div class="control-group">
              <label class="control-label" for="signin"></label>
              <div class="controls">
                <button type="submit" id="loginBTN" class="btn btn-success float-right">Sign In</button>
              </div>
            </div>
            </fieldset>
            </form>
        </div>
	
		
		
		

		
		
		
        <div class="tab-pane fade <?php if($_SESSION['signUPBOOL']==true){echo "active in";}?>" id="signup">
            <form id="signInForm" class="form-horizontal">
            <fieldset>
            <!-- Sign Up Form -->
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="eSU">Email:</label>
              <div class="controls">
                <input id="eSU" name="eSU" class="form-control" type="email" placeholder="yourEmail@gmail.com"  class="input-large">
              </div>
            </div>
            
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="uSU">Username:</label>
              <div class="controls">
                <input id="uSU" name="uSU" class="form-control" type="text" placeholder="Username007" class="input-large" required>
              </div>
            </div>
            
            <!-- Password input-->
            <div class="control-group">
              <label class="control-label" for="pSU">Password:</label>
              <div class="controls">
                <input id="pSU" name="pSU" class="form-control" type="password" placeholder="********" class="input-large" required>
                <em>Enter 8 Characters or More</em>
              </div>
            </div>
            
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="pSU2">Re-Enter Password:</label>
              <div class="controls">
                <input id="pSU2" class="form-control" name="pSU2" type="password" placeholder="********" class="input-large" required>
              </div>
            </div>
            
            <!-- Multiple Radios (inline) -->
            <div class="control-group">
			<label class="control-label" for="gSU">Gender:</label>
              <div class="controls">
                <label for="gSU-0">
                  <input type="radio" name="gSU" id="gender-0" value="male" required>&nbsp Male</label>
                <label  for="gSU-1">
                  <input type="radio" name="gSU" id="gender-1" value="female">&nbsp Female</label>
              </div>
              <label class="control-label" for="rSU">Humanity Check:</label>
              <div class="controls">
                <label for="rSU-0">
                  <input type="radio"  name="rSU" id="humancheck-0" value="robot" checked="checked" required>&nbsp I'm a Robot</label>
                <label  for="rSU-1">
                  <input type="radio" name="rSU" id="humancheck-1" value="human">&nbsp I'm Human</label>
              </div>
            </div>
            
            <!-- Button -->
            <div class="control-group" >
              <label class="control-label " for="confirmsignup"></label>
              <div class="controls">
                <button type="submit" class="btn btn-success float-right" id="register" >Sign Up</button>
				
              </div>
            </div>
            </fieldset>
            </form>
				<script>
			$(document).ready(function(){

			  jQuery.validator.addMethod("noSpace", function(value, element) { 
			  return value.indexOf(" ") < 0 && value != ""; 
			}, "No space please and don't leave it empty");


			$("signInForm").validate({
			   rules: {
				  name: {
					  noSpace: true
				  }
			   }
			  });


			})
		</script>
      </div>
    </div>
      </div>
    </div>
  </div>
</div>
 

</body>
</html>




<?php
$dbCon->close(); 
$con->close(); 
?>