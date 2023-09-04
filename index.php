<?php
// Turn off error reporting for production (set to 0 for no error reporting)
error_reporting(0);

// Initialize variables
$status = "";
$sult = "";
$city = "";

// Check if the form was submitted
if (isset($_POST['submit'])) {
   // Get the city name from the form
   $city = $_POST['city'];
   
   // Create the URL for the OpenWeatherMap API
   $url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid=49c0bad2c7458f1c76bec9654081a661";

   // Initialize cURL session
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

   // Execute the cURL request and get the result
   $result = curl_exec($ch);

   // Close the cURL session
   curl_close($ch);

   // Decode the JSON result
   $result = json_decode($result, true);

   // Check if the response code is 200 (OK)
   if ($result['cod'] == 200) {
      $status = "yes";
   } else {
      $sult = $result['message'];
   }
}
?>

<html lang="en" class=" -webkit-">
<head>
   <meta charset="UTF-8">
   <title>DEVTIFE WEATHER</title>
   <!-- Include Bootstrap CSS from a local file (ensure the file path is correct) -->
   <link rel="stylesheet" href="./bootstrap.min.css">
</head>
<body>
   <div class="form m-5">
      <h1>WEATHER FORECAST SITE</h1>
      <form method="post">
         <div class="form-group">
            <label class="col-form-label mt-4" for="inputDefault">Enter a City Name</label>
            <!-- Display the city name entered previously if any -->
            <input type="text" class="form-control" placeholder="Enter city name" name="city" value="<?php echo $city ?>">
         </div>
         <button type="submit" class="btn btn-secondary mt-3"  name="submit" >Search🔍</button>
         <!-- Display an error message if there's an issue with the city name -->
         <div class="alert alert-dismissible alert-danger">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong>Oh snap!  <?php echo $sult ?></strong> <a href="./index.php" class="alert-link">Change a few things up</a> and try submitting again.
         </div>
      </form>
   </div>

   <?php if ($status == "yes") { ?>
      <div class="card m-5">
         <h3 class="card-header">FETCHED DATA</h3>
         <div class="weatherIcon">
            <!-- Display the weather icon based on the OpenWeatherMap response -->
            <img class="d-block user-select-none" src="http://openweathermap.org/img/wn/<?php echo $result['weather'][0]['icon'] ?>@4x.png" />
         </div>
         <div class="card-body">
            <div class="btn-group" role="group" aria-label="Basic example">
               <button type="button" class="btn btn-secondary">
                  <div class="temperature">
                     <!-- Display temperature in Celsius (converted from Kelvin) -->
                     <span><?php echo round($result['main']['temp'] - 273.15) ?>°</span>
                  </div>
               </button>
               <button type="button" class="btn btn-secondary">
                  <!-- Display the weather condition (e.g., cloudy, rainy) -->
                  <div class="weatherCondition"><?php echo $result['weather'][0]['main'] ?></div>
               </button>
               <button type="button" class="btn btn-secondary">
                  <!-- Display the city name -->
                  <div class="place"><?php echo $result['name'] ?></div>
               </button>
               <button type="button" class="btn btn-secondary">
                  <!-- Display the wind speed -->
                  <div class="place"><?php echo $result['wind']['speed'] ?> M/H</div>
               </button>
               <button type="button" class="btn btn-secondary">
                  <!-- Display the date (day and month) -->
                  <?php echo date('d M', $result['dt']) ?>
               </button>
            </div>
         </div>
      </div>
   <?php } ?>
</body>
</html>
