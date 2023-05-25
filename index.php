<!DOCTYPE html>
<html>
  <head>
    <title>dogpi2 Temperature Alert</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <h1>dogpi2 Temperature Alert</h1>

    <h2>Current Conditions:</h2>
    <div>
      <?php
      // Read the temperature, humidity, and heat index from data.txt
      $file = fopen("/var/www/html/data.txt", "r");
      $data = explode(",", fread($file, filesize("/var/www/html/data.txt")));
      fclose($file);

      $temperature = floatval($data[0]);
      $humidity = floatval($data[1]);
      $heat_index = floatval($data[2]);
      ?>
      
      <p><span>Temperature:</span> <?php echo $temperature; ?> &#8457;</p>
      <p><span>Humidity:</span> <?php echo $humidity; ?>%</p>
      <p><span>Heat Index:</span> <?php echo $heat_index; ?> &#8457;</p>
    </div>

    <h2>Temperature Alert:</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <label for="high_temp">High Temperature (&#8457;):</label>
      <input type="number" name="high_temp" id="high_temp"><br><br>
      <label for="low_temp">Low Temperature (&#8457;):</label>
      <input type="number" name="low_temp" id="low_temp"><br><br>
      <input type="submit" name="submit" value="Submit">
    </form>

    <?php
    // Check if temperature alert has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $high_temp = floatval($_POST["high_temp"]);
      $low_temp = floatval($_POST["low_temp"]);

      // Write the high and low temperatures to temp_alert.txt
      $file = fopen("/var/www/html/temp_alert.txt", "w");
      fwrite($file, $high_temp . "," . $low_temp);
      fclose($file);

      echo "<p>Temperature alert submitted:</p>";
      echo "<p>High Temperature: " . $high_temp . " &#8457;</p>";
      echo "<p>Low Temperature: " . $low_temp . " &#8457;</p>";
    }
else
{
      $alertFile = fopen("/var/www/html/temp_alert.txt", "r");
      $alert_temps = explode(",", fread($alertFile, filesize("/var/www/html/temp_alert.txt")));
      fclose($alertFile);
      echo "<p>High Temperature: " . $alert_temps[0] . " &#8457;</p>";
      echo "<p>Low Temperature: " . $alert_temps[1] . " &#8457;</p>";

}
    ?>
  </body>
</html>
