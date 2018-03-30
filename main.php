<?php 


function db_connect() {
	$user = "root";
	$password = "root";
	$db = "ONECOIN";
	$host = "localhost";
	$port = 3306;

	$connection = mysqli_connect($host, $user, $password, $db, $port);

	if (!$connection) {
		die("Connection failed: " . mysqli_connect_error());
	}


	return $connection;

}

function register($email, $firstName, $lastName, $address, $pass) {
	$connection = db_connect();
	$query = "INSERT INTO `accounts` VALUES('$email', '$pass', '$firstName', '$lastName', '$address')";
	mysqli_query($connection, $query);
	mysqli_close($connection);
	header("Location: index.html");
}

function login($email, $pass) {
	$connection = db_connect();
	$query = "SELECT * FROM accounts WHERE email = '$email' AND pass = '$pass'"; 
	$result = mysqli_query($connection, $query);

	if(mysqli_num_rows($result) == 1){
        session_start();
        $_SESSION["user"] = $email;
        echo $_SESSION["user"];
        header("Location: account.html");
    }else{
        $msg = "Your email/password was not recognised - try again!" ; 
        echo "<script type ='text/javascript'> 
            alert('$msg');
            window.location = 'login.html'; </script>"; 
    }

}

function admin_login($email, $pass){
	$connection = db_connect();
	$query = "SELECT * FROM admins WHERE email = '$email' AND pass = '$pass'"; 
	$result = mysqli_query($connection, $query);

	if(mysqli_num_rows($result) == 1){
        session_start();
        $_SESSION["admin"] = $email;
        echo $_SESSION["admin"];
        header("Location: admin.html");
    }else{
        $msg = "Your email/password was not recognised - try again!" ; 
        echo "<script type ='text/javascript'> 
            alert('$msg');
            window.location = 'login.html'; </script>"; 
    }

}


function add_to_basket($id) {
	session_start();
    if(isset($_SESSION['basket'])){ 
        if (array_key_exists($id, $_SESSION['basket'])){ 
            $_SESSION['basket'][$id]++; 
        } else{
            $_SESSION['basket'][$id] = 1; 
        }
    }else{
        $_SESSION['basket'] = array($id => 1);                                                                
    }
}

function remove_from_basket($id) {
	session_start();
	if(isset($_SESSION['basket'])) {

		if(array_key_exists($id, $_SESSION['basket'])){
			$_SESSION['basket'][$id]--;
		}

		if($_SESSION['basket'][$id] == 0){
			unset($_SESSION['basket'][$id]);
		}

	}
	header("Location: basket.html");
}

function order($cardNo, $cardName, $expDate){

	session_start();
    
    if(!isset($_SESSION["user"])){
        
        $message = "Please Login to place an order" ; 
        echo 
        "<script> 
        	alert('$message');
            window.location = 'login.html'; 
        </script>";
    }else{

	    $connection = db_connect();
	    $date = date("Y-m-d");
	    $query = "INSERT INTO orders (email, cardNo, cardName, expDate, transcDate) VALUES('$_SESSION[user]', '$cardNo', '$cardName', 
	    								'$expDate','$date')";
	    mysqli_query($connection, $query);
	    $oid = mysqli_insert_id($connection);

	    foreach($_SESSION['basket'] as $key=>$value) {
	    	if($value > 0) {
	    		$query = "INSERT INTO orderItems (order_id, product_id, quantity, review) VALUES($oid, $key, $value, 0)";
	    		mysqli_query($connection, $query);
	    	}
	    }

	    $message = "Your Order Has Been Placed. Thank You!";

	    unset($_SESSION['basket']);
	    mysqli_close($connection);

	   	echo "
	    	<script>
	    		alert('$message');
	    		window.location = 'index.html';
	    	</script>
	    ";
	    // header("Location: index.html");
	}
}

function display_basket() {
	    if(!isset($_SESSION['basket'])){
        
        echo"<h1><span class='glyphicon glyphicon-shopping-cart'></span> Your basket is empty</h1>";
        return;
    	}
  		if(count($_SESSION['basket']) == 0) {
  			echo"<h1><span class='glyphicon glyphicon-shopping-cart'></span> Your basket is empty</h1>" ;
        	return;
  		}
    
    
    echo "<table class='table table-responsive' style='border-spacing:20px; border-collapse: separate;' ><tr>
         <th>Product Name</th>
         <th>Quantity</th>
         <th>Price</th>
         <th>Subtotal</th>
         </tr>";
    
    $conn = db_connect();
    $total = 0;
    
    foreach($_SESSION['basket'] as $key=>$value){ 
       
        if($value > 0){
        $query = "SELECT name, price FROM products WHERE id=$key";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        echo "<tr>
             <td>$row[name]</td>
             <td>$value</td>
             <td>£$row[price]</td>
             <td>£".number_format($value*$row['price'], 2, '.', '')."</td>
             <td> <form action='removeFromBasket.php' method='post'>
             		  <input type='submit' value='Remove' name='$key'> 
             	  </form> 
             </td>
             </tr>";
        
        $total = $total + $value*$row['price'];
        }
    }
    echo "</table>";
    mysqli_close($conn);
    

    
    echo "<br/><table class='table table-responsive'><tr>
         <th>Total</th>
         <th>Order</th>
         </tr>
         <tr>
         <td>&pound".number_format($total, 2, '.', '')."</td>
         <td>
         	<form action='order.php' method='post'>
         		<input type='number' name='cardNo' placeholder='Card Number' required>
         		<input type='text' name='cardName' placeholder='Name on Card' required>
         		<input type='date' name='expDate' placeholder='Expiration Date' required>
         		<input type ='submit' value='Order'/>
         	</form>
         </td>
         </tr>
         </table>";

}


function show_playstation_products(){
	$connection = db_connect();
	$query = "SELECT * FROM products WHERE brand='playstation'";
	$results = mysqli_query($connection, $query);
	show_products($results);
	mysqli_close($connection);
}

function show_nintendo_products(){
	$connection = db_connect();
	$query = "SELECT * FROM products WHERE brand='nintendo'";
	$results = mysqli_query($connection, $query);
	show_products($results);
	mysqli_close($connection);
}

function show_atari_products(){
	$connection = db_connect();
	$query = "SELECT * FROM products WHERE brand='atari'";
	$results = mysqli_query($connection, $query);
	echo "<table class='table table-responsive' style='border-spacing:20px; border-collapse: separate;'>";
	echo "<tr>";
	$iterator = 0;

	while($row = mysqli_fetch_array($results)){
		if($iterator == 3){
			echo "</tr>";
			echo "
					<tr>
					<td align='center'>
						<img src='$row[imagePath]' width='100' height='100'>
						<p>$row[name]</p>
						<p>£$row[price]</p>
						<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#$row[id]'>Description</button>
					<div class='modal fade' id='$row[id]' role='dialog'>  <div class='modal-dialog'> <div class='modal-content'> <div class='modal-body'> $row[description] </div> </div> </div> </div>
					<form> 
					<input style='margin-top:5px; width:80px;' class='btn btn-primary btn-sm' type='button' value='Buy' onclick='add($row[id])' /> 
					</form>";

					$reviewQuery = "SELECT * FROM reviews WHERE productName='$row[name]'";
					$reviewResults = mysqli_query($connection, $reviewQuery);
					if(mysqli_num_rows($reviewResults) > 0){
						$id = "review" . $row[id];
						echo "
						<button style='margin-top:5px; width:80px;' type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#$id'>Reviews</button>
						<div class='modal fade' id='$id' role='dialog'>
						<div class='modal-dialog'> 
						<div class='modal-content'>
						<div class='modal-body'>
							<table class='table table-responsive'>
						";
						while($row = mysqli_fetch_array($reviewResults)){
							echo "<tr> <td>
								<p>$row[comment]</p>
								<h6>Review by: $row[email]</h6>
								</td> </tr>

							";
						}
						echo " </table> </div> </div> </div> </div>";
					}

			echo "</td>";
			
			$iterator = 0;
		} else {

		echo " 
				<td align='center'>
					<img src='$row[imagePath]' width='100' height='100'>
					<p>$row[name]</p>
					<p>£$row[price]</p>
					<button style='width:80px;' type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#$row[id]'>Description</button>
					<div class='modal fade' id='$row[id]' role='dialog'>  <div class='modal-dialog'> <div class='modal-content'> <div class='modal-body'> $row[description] </div> </div> </div> </div> 
					<form> 
					<input style='margin-top:5px; width:80px;' class='btn btn-primary btn-sm' type='button' value='Buy' onclick='add($row[id])' /> 
					</form>";

					$reviewQuery = "SELECT * FROM reviews WHERE productName='$row[name]'";
					$reviewResults = mysqli_query($connection, $reviewQuery);

					if(mysqli_num_rows($reviewResults) > 0){
						$id = "review" . $row[id];
						echo "
						<button style='margin-top:5px; width:80px;' type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#$id'>Reviews</button>
						<div class='modal fade' id='$id' role='dialog'>
						<div class='modal-dialog'> 
						<div class='modal-content'> 
						<div class='modal-body'>
							<table class='table table-responsive'>
						";
						while($row = mysqli_fetch_array($reviewResults)){
							echo "<tr> <td>
									<p>$row[comment]</p>
									<h6>Review by: $row[email]</h6>
							   </td> </tr>";
						}
						echo " </table> </div> </div> </div> </div>";

					}


		echo "</td>";
			  
		
	}

		$iterator++;
	}
	echo "</tr>";
	echo "</table>";
	mysqli_close($connection); 

}

function update_customer_details($email, $pass, $firstName, $lastName, $address) {
	session_start();
	$connection = db_connect();
	$query = "UPDATE accounts SET email='$email', pass='$pass', firstName='$firstName', lastName='$lastName', address='$address' 
	WHERE email='$email'";
	if(mysqli_query($connection, $query) == TRUE){
		mysqli_close($connection);
		$_SESSION['accountUpdated'] = "success";	
	} else {
		$_SESSION['accountUpdated'] = "unsuccessful";	
	}
	
	mysqli_close($connection);

	header("Location: account.html");

}

function update_item_details($name, $imagePath, $description, $brand, $price){
	$connection = db_connect();
	$query = "UPDATE products SET imagePath='$imagePath', description='$description', brand='$brand', 
		price='$price' WHERE name='$name'";
	$results = mysqli_query($connection, $query);
	mysqli_close($connection);
	header("Location: admin.html");

}


function show_order_history() {
	session_start();
	$user = $_SESSION['user'];

	if(isset($_SESSION['user'])) {
		$connection = db_connect();
		$query = "SELECT id FROM orders WHERE email='$user'";
		$orders_results = mysqli_query($connection, $query);

		if(!is_null($orders_results)){
			echo "<h1>Order History:</h1>";
		echo "<table class='table' style=''>
			 <tr>
			 <th>Name</th>
			 <th>Date</th>
			 <th>Quantity</th>
			 <th>Price</th>
			 <th>Review Status</th>
			 </tr>
			 ";
		while($row = mysqli_fetch_array($orders_results)){
			$query = "
			SELECT orderItems.quantity, orderItems.id, products.name, products.imagePath, products.price, orderItems.review, orders.email, orders.transcDate
			FROM orderItems
			INNER JOIN products on products.id = orderItems.product_id INNER JOIN orders on orderItems.order_id = orders.id
			WHERE orderItems.order_id = $row[id]
			";
			$orderItems_results = mysqli_query($connection, $query);
			while($row = mysqli_fetch_array($orderItems_results)){
				echo "<tr>
				<td>$row[name]</td>
				<td>$row[transcDate]</td>
				<td>$row[quantity]</td>
				<td>£$row[price]</td>";
				if($row[review] == 0){
					echo "<td>
						<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#$row[id]'>Add Review</button>
						<div class='modal fade' id='$row[id]' role='dialog'>  
						<div class='modal-dialog'> 
						<div class='modal-content'> 
						<div class='modal-body'> 
						<p>Review for $row[name]:</p>
						<form action='./addReview.php' method='post'>
						<input style='height:100px; width:300px;' type='text' name='comment'><br><br>
						<input type='hidden' name='id' value='$row[id]'/>
						<input type='hidden' name='email' value='$row[email]'/>
						<input type='hidden' name='name' value='$row[name]'/>
						<button type='submit' value='Submit'>Submit Review</button>
					</form>
						</div> 
						</div> </div> </div> 
					</td>";
				}else {
					echo "<td>Reviewed</td>";
				}

				echo "</tr>
				";
			}
		}
		echo "</table>";
	}
	}
}

function show_user(){
	session_start();
	$user = $_SESSION["user"];
	$admin = $_SESSION["admin"];
	if(isset($_SESSION["user"])){
		echo '<a href="./account.html" style="position: absolute; top: 115px; right: 20px; font-weight: bold; font-size:12pt;>
		<p style="position: absolute; top: 115px; right: 20px; color: inherit; font-weight: bold; font-size:12pt;">' 
		. $user .'</p> </a>'; 
	}elseif(isset($_SESSION["admin"])){
		echo '<a href="./admin.html" style="position: absolute; top: 115px; right: 20px; font-weight: bold; font-size:12pt;>
		<p style="position: absolute; top: 115px; right: 20px; color: inherit; font-weight: bold; font-size:12pt;">' 
		. $admin .'</p> </a>'; 
	}else{
		echo '<a href="./login.html" style="position: absolute; top: 115px; right: 20px; color: inherit; font-weight: bold; font-size:12pt;">LOGIN</a>';
	}
}

function show_user_details(){
	session_start();
	$user = $_SESSION["user"];
	if(isset($_SESSION["user"])){
		$connection = db_connect();
		$query = "SELECT * FROM accounts WHERE email = '$user' ";
		$result = mysqli_query($connection, $query);
		$row = mysqli_fetch_array($result);
		$content = "
			<h1> Your Details: </h1>
			<form action='./updateCustomerDetails.php' method='post'>
				<label style='display:none;'>Email:</label><input style='display:none;' type='text' name='email' value='$row[email]'>
				<label>First Name:</label><br><input type='text' name='firstName' value='$row[firstName]' autocomplete='off' required><br>
				<label>Last Name:</label><br><input type='text' name='lastName' value='$row[lastName]' autocomplete='off' required><br>
				<label>Address:</label><br><input type='text' name='address' value='$row[address]' autocomplete='off' required><br>
				<label>Password:</label><br><input type='text' name='pass' value='$row[pass]' autocomplete='off' required><br><br>
				<input type='submit' value='Save Changes'/>
			 </form>
		";

		if(!isset($_SESSION['accountUpdated'])) {
			echo $content;
		} 
		else {
			if($_SESSION['accountUpdated'] == "success"){
				echo '<h4 class="text-success"> Account Updated Successfully</h4>' . $content ;
			}else {
				echo $content . '<h4 class="text-danger"> Something Went Wrong</h4>';
			}

		}

		mysqli_close($connection);
		unset($_SESSION['accountUpdated']);


	}
	else {
		echo "<p>NOT LOGGED-IN</p>"; 
	}
}

function add_review($id, $productName, $comment, $userEmail){
	$connection = db_connect();
	$query = "INSERT INTO `reviews` (productName, comment, email)
				VALUES ('$productName', '$comment', '$userEmail')";
	$result = mysqli_query($connection, $query);

	$query = "UPDATE `orderItems` SET review=1 WHERE id=$id";
	$result = mysqli_query($connection, $query);

	mysqli_close($connection);

	header("Location: account.html");
}


function drop_down_items(){
	$connection = db_connect();
	$query = "SELECT * FROM products WHERE brand != 'removed'";
	$results = mysqli_query($connection, $query);

	echo '<select id="itemSelect" onChange="getItem()">';
	echo "<option value='chooseItem'>Choose Item</option>";
	while($row = mysqli_fetch_array($results)){
		echo "<option value='$row[name]'>$row[name]</option>";
	}

	echo "</select>";
	mysqli_close($connection);

}

function drop_down_items_remove(){
	$connection = db_connect();
	$query = "SELECT * FROM products WHERE brand != 'removed'";
	$results = mysqli_query($connection, $query);

	echo '<select id="itemSelectRemove" onChange="getItemRemove()">';
	echo "<option value='chooseItem'>Choose Item</option>";
	while($row = mysqli_fetch_array($results)){
		echo "<option value='$row[name]'>$row[name]</option>";
	}

	echo "</select>";
	mysqli_close($connection);

}

function get_item_info($name){
	$connection = db_connect();
	$query = "SELECT * FROM products WHERE name='$name'";
	$result = mysqli_query($connection, $query);
	$row = mysqli_fetch_array($result);

	$infoObj->name = $row[name];
	$infoObj->imagePath = $row[imagePath];
	$infoObj->description = $row[description];
	$infoObj->brand = $row[brand];
	$infoObj->price = $row[price];

	$infoJSON = json_encode($infoObj);
	echo $infoJSON;
	mysqli_close($connection);

}

function remove_item($name){
	$connection = db_connect();
	$query = "UPDATE products SET brand='removed' WHERE name='$name' ";
	$result = mysqli_query($connection, $query);
	mysqli_close($connection);
	header("Location: admin.html");
}

function add_item($name, $imagePath, $description, $brand, $price){
	$connection = db_connect();
	$query = "INSERT INTO products (name, imagePath, description, brand, price)
		VALUES ('$name', '$imagePath', '$description', '$brand', $price)";
	$result = mysqli_query($connection, $query);
	mysqli_close($connection);
	header("Location: admin.html");
}

function show_edit_items(){
	if(isset($_SESSION['admin'])){
		echo "<h4>Edit Item</h4>" ;
		echo drop_down_items() . "

				<form action='./updateItemInfo.php' method='post'>
					<input id='name' type='hidden' name='name' required>
					<br><label>Image Path:</label><br>
					<input id='imagePath' type='text' name='imagePath' required><br>
					<label>Description:</label><br>
					<input id='description' type='text' name='description' required><br>
					<label>Brand:</label><br>
					<input id='brand' type='text' name='brand' required><br>
					<label>Price:</label><br>
					<input id='price' type='text' name='price' required><br><br>
					<input type='submit' value='Update'/><br><br>
				</form>


		";
	}
}

function show_add_item(){
	if(isset($_SESSION['admin'])){
		echo " 
				<h4>Add New Item</h4>

				<form action='./addItem.php' method='post'>
					
					<label>Name:</label><br>
					<input type='text' name='name' required><br>
					<label>Image Path:</label><br>
					<input type='text' name='imagePath' required><br>
					<label>Description:</label><br>
					<input type='text' name='description' required><br>
					<label>Brand:</label><br>
					<input type='text' name='brand' required><br>
					<label>Price:</label><br>
					<input type='text' name='price' required><br><br>
					
					<input type='submit' value='Add'/><br><br>
				</form>
		";
	}else{
		echo "<h1>Admin not logged-in.</h1>";
	}
}

function show_remove_items(){
	if(isset($_SESSION['admin'])){
		echo "<h4>Remove Item</h4>";
		echo drop_down_items_remove() . "
				<form action='./removeItem.php' method='post'>
					<input id='nameRemove' type='hidden' name='name' required><br>
					<input type='submit' value='Remove'/><br><br>
				</form>
		";
	}
}

function show_admin_logout(){
	if(isset($_SESSION['admin'])){
		echo " 
				<form action='./adminLogout.php' method='post'>
					<input type='submit' style='width: 100%; background-color: yellow; font-weight: 800;' value='LOGOUT'></input>
				</form>

		";
	}
}

function admin_logout(){
	session_start();

	if(isset($_SESSION['admin'])){
		unset($_SESSION['admin']);	
	}

	header("Location: index.html");
}

function user_logout(){
	session_start();

	if(isset($_SESSION['user'])){
		unset($_SESSION['user']);	
	}

	header("Location: index.html");
}

function show_user_logout(){
	if(isset($_SESSION['user'])){
		echo 
		"<form action='./userLogout.php' method='post'>
			<input type='submit' style='width: 100%; background-color: yellow; font-weight: 800;' value='LOGOUT'></input>
		</form>";
	}
}

function get_matches($queryString){

	if (strlen($queryString) > 0){
		$connection = db_connect();
		$query = "SELECT * FROM products WHERE name LIKE '%" . $queryString . "%'" . "AND brand != 'removed'";
		$result = mysqli_query($connection, $query);
		
		//echo "<p>hello</p>";
		$html = "";
		while($row = mysqli_fetch_array($result)){ 
			$html = $html . "<p onclick='fillSearch(this)'>$row[name]</p>";
		}

		echo $html;
	} else{
		echo "";
	}


	mysqli_close($connection);

}

function search_item($productName){
	session_start();
	unset($_SESSION["search"]);
	$connection = db_connect();
	$query = "SELECT * FROM products WHERE name LIKE '%" . $productName . "%'" . "AND brand != 'removed'";
	$result = mysqli_query($connection, $query);

	if (mysqli_num_rows($result) > 0) {
		session_start();
		$_SESSION["search"] = $productName;
	} else {
		session_start();
		$_SESSION["search"] = "empty";
	}

	header("Location: search.html");

}

function show_search(){
	session_start();
	if (isset($_SESSION["search"])) {

		if ($_SESSION["search"] == "empty"){
			echo "<h1>No Results</h1>";
		} else {
			$name = $_SESSION["search"];
			$connection = db_connect();
			$query = "SELECT * FROM products WHERE name LIKE '%" . $name . "%'" . "AND brand != 'removed'";
			$result = mysqli_query($connection, $query);
			show_products($result);

		}
	} else {
		echo "<h1>No Results</h1>";
	}
}

function show_products($results){
	$connection = db_connect();
	echo "<table class='table table-responsive' style='border-spacing:20px; border-collapse: separate;'>";
	echo "<tr>";
	$iterator = 0;

	while($row = mysqli_fetch_array($results)){
		if($iterator == 3){
			echo "</tr>";
			echo "
					<tr>
					<td align='center'>
						<img src='$row[imagePath]' width='100' height='100'>
						<p>$row[name]</p>
						<p>£$row[price]</p>
						<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#$row[id]'>Description</button>
					<div class='modal fade' id='$row[id]' role='dialog'>  <div class='modal-dialog'> <div class='modal-content'> <div class='modal-body'> $row[description] </div> </div> </div> </div>
					<form> 
					<input style='margin-top:5px; width:80px;' class='btn btn-primary btn-sm' type='button' value='Buy' onclick='add($row[id])' /> 
					</form>";

					$reviewQuery = "SELECT * FROM reviews WHERE productName='$row[name]'";
					$reviewResults = mysqli_query($connection, $reviewQuery);
					if(mysqli_num_rows($reviewResults) > 0){
						$id = "review" . $row[id];
						echo "
						<button style='margin-top:5px; width:80px;' type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#$id'>Reviews</button>
						<div class='modal fade' id='$id' role='dialog'>
						<div class='modal-dialog'> 
						<div class='modal-content'>
						<div class='modal-body'>
							<table class='table table-responsive'>
						";
						while($row = mysqli_fetch_array($reviewResults)){
							echo "<tr> <td>
								<p>$row[comment]</p>
								<h6>Review by: $row[email]</h6>
								</td> </tr>

							";

						}
						echo " </table> </div> </div> </div> </div>";
					}

			echo "</td>";
			
			$iterator = 0;
		} else {

		echo " 
				<td align='center'>
					<img src='$row[imagePath]' width='100' height='100'>
					<p>$row[name]</p>
					<p>£$row[price]</p>
					<button style='width:80px;' type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#$row[id]'>Description</button>
					<div class='modal fade' id='$row[id]' role='dialog'>  <div class='modal-dialog'> <div class='modal-content'> <div class='modal-body'> $row[description] </div> </div> </div> </div> 
					<form> 
					<input style='margin-top:5px; width:80px;' class='btn btn-primary btn-sm' type='button' value='Buy' onclick='add($row[id])' /> 
					</form>";

					$reviewQuery = "SELECT * FROM reviews WHERE productName='$row[name]'";
					$reviewResults = mysqli_query($connection, $reviewQuery);

					if(mysqli_num_rows($reviewResults) > 0){
						$id = "review" . $row[id];
						echo "
						<button style='margin-top:5px; width:80px;' type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#$id'>Reviews</button>
						<div class='modal fade' id='$id' role='dialog'>
						<div class='modal-dialog'> 
						<div class='modal-content'> 
						<div class='modal-body'>
							<table class='table table-responsive'>
						";
						while($row = mysqli_fetch_array($reviewResults)){
							echo "<tr> <td>
									<p>$row[comment]</p>
									<h6>Review by: $row[email]</h6>
									<h6>2</h6>
							   </td> </tr>";
						}
						echo " </table> </div> </div> </div> </div>";

					}


		echo "</td>";
			  
		
	}

		$iterator++;
	}
	echo "</tr>";
	echo "</table>";
	mysqli_close($connection); 

}

?>