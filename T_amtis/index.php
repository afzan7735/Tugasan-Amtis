<?php
include "connection.php";

function calculateElectricityCharge($voltage, $current, $rate) {
    
    $power = $voltage * $current;

    $energy = $power / 1000; 

    $totalCharge = $energy * ($rate / 100);
	
	
	global $connection; 
    
    $maxHourQuery = mysqli_query($connection, "SELECT MAX(hour) AS maxHour FROM tbl_calculate");
    $maxHourData = mysqli_fetch_assoc($maxHourQuery);
    
    
    $nextHour = $maxHourData['maxHour'] ? $maxHourData['maxHour'] + 1 : 1;

    
    $query = mysqli_query($connection, "INSERT INTO tbl_calculate (hour, energy, total) 
        VALUES ('$nextHour', '$energy', '$totalCharge')") or die(mysqli_error());


    return array(
        'power' => $power,
        'energy' => $energy,
        'totalCharge' => $totalCharge,
		'hour' => $nextHour
		
    );
	
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $voltage = isset($_POST['voltage']) ? floatval($_POST['voltage']) : 0;
    $current = isset($_POST['current']) ? floatval($_POST['current']) : 0;
    $rate = isset($_POST['rate']) ? floatval($_POST['rate']) : 0;

    $result = calculateElectricityCharge($voltage, $current, $rate);
	
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Calculator</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <style>
    body {
    background-color: #46CBEF;
    background-image: url(bg.jpg);
    background-size: 1400px 680px;
    }
    h1{
    color:white;
    }
    </style>
</head>
<body>
    <br>
    <h1 class= "offset-sm-1">Calculate</h1>

    <br>

            <div class = "card col-sm-4 offset-sm-1" >
                <div class = "card-body card-body-login-body">
                    <div class = "container">

                    <form action="" method="POST" novalidate="">
                   
                    <div class ="mb-3 row">
                        <label class ="col-sm-4 col-form-label"><b>Voltage :</b></label>
                        <div class="col-sm-8 d-flex justify-content-center">
                            <input type="number" class="form-control"  name="voltage" >
                        </div>
                    </div>

                    <div class ="mb-3 row">
                        <label class ="col-sm-4 col-form-label"><b>Current :</b></label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control"  name="current" >
                        </div>
                    </div>

                    <div class ="mb-3 row">
                        <label class ="col-sm-4 col-form-label"><b>Current Rate :</b></label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control"  name="rate" >
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class = "btn btn-primary" type="submit">Calculate</button>
                    </div>

                    </form>

                    </div>
                </div>
            </div>

            <br>

            <div class = "card col-sm-3 offset-sm-1" >
                <div class = "card-body card-body-login-body ">
                    <div class = "container ">

                    <h3>RESULT</h3>

                    <?php if (isset($result)): ?>
                   
                    <div class ="mb-3 row">
                        <label class ="col-sm-6 "><b>Power :</b></label>
                        <div class="col-sm-8">
                           <?php echo $result['power']; ?> Wh
                        </div>
                    </div>

                    <div class ="mb-3 row">
                        <label class ="col-sm-6 "><b>Energy :</b></label>
                        <div class="col-sm-8">
                           <?php echo $result['energy']; ?> kWh
                        </div>
                    </div>

                    <div class ="mb-3 row">
                        <label class ="col-sm-6 "><b>Total Charge :</b></label>
                        <div class="col-sm-8">
                           RM <?php echo number_format($result['totalCharge'], 2); ?>
                        </div>
                    </div>

                    <?php endif; ?>

                    </div>
                </div>
            </div>
			
			<br><br>
			
			<div class = "card-body table-responsive row offset-sm-1">
			  <div class="col-sm-5">
                <table class = "table table-striped">
                    <thead>
                        <tr>
                            <th>Num</th>
                            <th>Hour</th>
                            <th>Energy (kWh)</th>
                            <th>Total (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					$query = mysqli_query($connection, "SELECT * FROM tbl_calculate");
					while ($data = mysqli_fetch_array($query)) {
					?>

                    
                        <tr>
                            <td><?php echo $data['num']; ?></td>
                            <td><?php echo $data['hour']; ?></td>
                            <td><?php echo $data['energy']; ?></td>
                            <td><?php echo number_format($data['total'], 2); ?></td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
			  </div>
            </div>
          
</body>
</html>