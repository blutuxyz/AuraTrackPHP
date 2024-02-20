<?php
include("conf.php");
$mysqli = new mysqli($database_address, $database_username, $database_password, $database_name);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$tracking_number = isset($_GET['tracking_number']) ? $_GET['tracking_number'] : ''; // Get tracking number from URL parameter

if (!empty($tracking_number)) {
    // Retrieve latest tracking information based on the provided tracking number
    $query = "SELECT * FROM tracking_history WHERE tracking_number = '$tracking_number' ORDER BY timestamp DESC LIMIT 1";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $latest_status = $row["status"];
        $progress_percentage = $row["progress_percentage"];
    } else {
        $latest_status = "No tracking information available for the provided tracking number.";
        $progress_percentage = 0;
    }

    // Retrieve tracking history for the provided tracking number
    $history_query = "SELECT * FROM tracking_history WHERE tracking_number = '$tracking_number' ORDER BY timestamp DESC";
    $history_result = $mysqli->query($history_query);
}

$mysqli->close();
?>
<?php
// Function to format timestamp without seconds
function formatTimestamp($timestamp) {
    $dateTime = new DateTime($timestamp);
    return $dateTime->format('Y-m-d H:i');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $auratrack_company ?></title>
    <link rel="stylesheet" href="output.css">
</head>
<body>
    <div class="max-w-screen-md mx-auto relative">
        <!-- Tracking Number Input Form -->
        <div class="mt-4">
            <form action="tracking.php" method="get">
                <?php echo "<h2 class='text-2xl'>".$auratrack_company."</h2>"; ?><br><br>
                <label for="tracking_number">Enter Tracking Number:</label>
                <input class="px-3.5 py-1.5 bg-cyan-600 text-white border-cyan-700 border-[3px] border-solid hover:bg-[#0084a3] transition-all duration-300" type="text" id="tracking_number" name="tracking_number" required>
                <input class="px-3.5 py-1.5 bg-cyan-600 text-white cursor-pointer border-cyan-700 border-[3px] border-solid hover:bg-[#0084a3] transition-all duration-300" type="submit" value="Track">
                
            </form>
        </div>
        <!-- End Tracking Number Input Form -->

        <?php if (!empty($tracking_number)) : ?>
            <!-- Progress Bar -->
            <div class="relative pt-1">
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-300">
                    <div style="width:<?php echo $progress_percentage; ?>%" class="shadow-none flex flex-col whitespace-nowrap text-white justify-center bg-cyan-600"></div>
                </div>

                <!-- Dots Container -->
                <div class="flex justify-between absolute inset-0 items-center" style="top: 25%">
                    <div class="w-3 h-3 bg-black opacity-55 rounded-full"></div> <!-- Dot after "Order Data Transmitted" -->
                    <div class="w-3 h-3 bg-black opacity-55 rounded-full"></div> <!-- Dot after "Shipped" -->
                    <div class="w-3 h-3 bg-black opacity-55 rounded-full"></div> <!-- Dot after "Out for Delivery" -->
                    <div class="w-3 h-3 bg-black opacity-55 rounded-full"></div> <!-- Dot after "Delivered" -->
                </div>
                <!-- End Dots Container -->
            </div>
            <!-- End Progress Bar -->

            <!-- Text Labels -->
            <div class="mt-2 grid grid-cols-4 gap-2 text-sm">
                <div>Origin country</div>
                <div>In transit</div>
                <div>Destination country</div>
                <div>Delivered</div>
            </div>
            <!-- End Text Labels -->

            <!-- Display Latest Tracking Status -->
            <div class="mt-4 ">
                <h3 class="text-2xl"><?php echo $latest_status; ?></h3>
            </div>
            <!-- End Display Latest Tracking Status -->

            <!-- Tracking History -->
            <div class="mt-4">
                <h3 class="text-xl">Tracking History:</h3>
                <ul>
                    <?php while ($history_row = $history_result->fetch_assoc()) : ?>
                        <li>
                            <?php
                                $locationInfo = !empty($history_row["location"]) ? " [" . $history_row["location"] . ", " . $history_row["location_code"] . "]" : "";
                                echo $history_row["status"] . $locationInfo . " - " . formatTimestamp($history_row["timestamp"]);
                            ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <br><br>
                <a class="px-3.5 py-1.5 bg-red-600 text-white cursor-pointer border-red-700 border-[3px] border-solid hover:bg-[#ac250d] transition-all duration-300" href="?">Close</a>
            </div>
            <!-- End Tracking History -->

            <!-- End Tracking History -->
        <?php endif; ?>
    </div>
</body>
</html>