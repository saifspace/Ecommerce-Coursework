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

	// echo "Connected successfully";

	return $connection;

}

function register($email, $firstName, $lastName, $address, $pass) {
	$connection = db_connect();
	$query = "INSERT INTO `accounts` VALUES('$email', '$pass', '$firstName', '$lastName', '$address')";
	mysqli_query($connection, $query);
	mysqli_close($connection);
	header("Location: index.php");
}

function login($email, $pass) {
	$connection = db_connect();
	$query = "SELECT * FROM accounts WHERE email = '$email' AND pass = '$pass'"; 
	$result = mysqli_query($connection, $query);

	if(mysqli_num_rows($result) == 1){
        session_start();
        $_SESSION["user"] = $email;
        echo $_SESSION["user"];
        header("Location: index.php");
    }else{
        $msg = "Your password was not recognised - try again!" ; 
        echo "<script type ='text/javascript'> 
            alert('$msg');
            window.location = 'account.html'; </script>"; 
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
    //header("Location: basket.php");

}

function display_basket() {
	    if(!isset($_SESSION['basket'])){
        
        echo'<h1><span class="glyphicon glyphicon-shopping-cart"></span> Your basket is empty</h1>';
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
         <td><form action='order.php' method='post'><input type ='submit' value='Order'/></form></td>
         </tr>
         </table>";
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
					<input style='margin-top:5px;' class='btn btn-primary btn-sm' type='button' value='Buy' onclick='add($row[id])' /> 
					</form>
					</td>
			";
			$iterator = 0;
		} else {

		echo " 
				<td align='center'>
					<img src='$row[imagePath]' width='100' height='100'>
					<p>$row[name]</p>
					<p>£$row[price]</p>
					<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#$row[id]'>Description</button>
					<div class='modal fade' id='$row[id]' role='dialog'>  <div class='modal-dialog'> <div class='modal-content'> <div class='modal-body'> $row[description] </div> </div> </div> </div> 
					<form> 
					<input style='margin-top:5px;' class='btn btn-primary btn-sm' type='button' value='Buy' onclick='add($row[id])' /> 
					</form>
				</td>
			  
		";
	}

		$iterator++;
	}
	echo "</tr>";
	echo "</table>";
	mysqli_close($connection); 

}



function show_user(){
	session_start();
	$user = $_SESSION["user"];
	if(isset($_SESSION["user"])){
		echo '<p style="position: absolute; top: 115px; right: 20px; color: inherit; font-weight: bold; font-size:12pt;">' . $user .'</p>'; 
	}else{
		echo '<a href="./account.html" style="position: absolute; top: 115px; right: 20px; color: inherit; font-weight: bold; font-size:12pt;">LOGIN</a>';
	}
}

?>