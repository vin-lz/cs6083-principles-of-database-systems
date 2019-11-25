<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Problem Set 3 Problem 1</title>
    <meta name="description" content="A web frontend with PHP for CS-GY6083 Problem Set 3 Question 1">
    <meta name="author" content="Vin Liu">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Problem Set 3 Question 1</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        City Name:
        <input type="text" name="city-name">
        <input type="submit" value="View Results">
    </form>

    <?php

    $city_name = "";
    $input_error = "";

    if (isset($_POST["city-name"])) {

        require "config.php";

        try {
            $connection = new PDO($dsn, $username, $password, $options);
            $sql = "SELECT s.sid AS id, s.scity AS city, m.mtemp AS temperature,    m.mhumid AS humidity, m.mpriecip AS precipitation
            FROM station AS s, measurement AS m,
                (SELECT s.sid, MAX(mtimestamp)AS recent
                FROM station AS s, measurement AS m
                WHERE s.sid = m.sid
                GROUP BY sid) AS r
            WHERE s.sid = r.sid AND m.sid = r.sid AND m.mtimestamp = r.recent AND s.scity = :city_name";

            if (empty($_POST["city-name"])) {
                $input_error = "City Name is required";?>
                <span class="error"><?php echo $input_error;?></span>
                <?php } else {
                    if (!preg_match("/^[a-zA-Z ]*$/", $_POST["city-name"])) {
                        $input_error = "Only letters and white space allowed";?>
                        <span class="error"><?php echo $input_error; ?></span>
                    <?php } else {
                        $city_name = test_input($_POST["city-name"]);
                        // echo $city_name;
                        // print_r($_POST);
                        $statement = $connection->prepare($sql);
                        $statement->bindParam(":city_name", $city_name, PDO::PARAM_STR);
                        $statement->execute([":city_name" => $city_name]);
                        $result = $statement->fetchAll();
                        // print_r($result);
                        if ($result && $statement->rowCount() > 0) {?>
                            <h2>Results</h2>
                            <table>
                            <thead>
                                <tr>
                                    <th>Station ID</th>
                                    <th>City</th>
                                    <th>Temperature</th>
                                    <th>Humidity</th>
                                    <th>Precipitation</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($result as $row) {
                                ?><tr>
                                    <td>
                                        <form method="post">
                                            <input type="submit" name="sid" value="
                                                <?php $id = $row["id"];
                                                echo $id; ?>" />
                                        </form>
                                        </td>
                                        <td><?php echo $row["city"]; ?></td>
                                        <td><?php echo $row["temperature"]; ?></td>
                                        <td><?php echo $row["humidity"]; ?></td>
                                        <td><?php echo $row["precipitation"]; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                    <h2>No results found for <?php echo $city_name; ?>.</h2>
                    <?php }
                    $city_name = null;
                    $statement = null;
                    $connection = null;
                }
            }
        } catch (PDOException $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    }



    if (isset($_POST["sid"])) {

        require "config.php";

        try {
            $connection = new PDO($dsn, $username, $password, $options);

            $sql = "SELECT sid, TIME(mtimestamp) AS t, DATE(mtimestamp) AS d,       mtemp, mhumid, mpriecip
            FROM measurement
            WHERE sid = :station_id
            ORDER BY DATE(mtimestamp) DESC, TIME(mtimestamp) DESC";

            $station_id = $_POST["sid"];
            // echo $station_id;
            // print_r($_POST);
            $statement = $connection->prepare($sql);
            $statement->bindParam(":station_id", $station_id, PDO::PARAM_STR);
            $statement->execute([":station_id" => $station_id]);
            $result = $statement->fetchAll();
            // print_r($result);
            if ($result && $statement->rowCount() > 0) { ?>
                <h2>Results</h2>
                <form>
                    <input type="button" value="Back" onclick="history.back()">
                </form>
                <table>
                    <thead>
                        <tr>
                            <th>Station ID</th>
                            <th>Time</th>
                            <th>Date</th>
                            <th>Temperature</th>
                            <th>Humidity</th>
                            <th>Precipitation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row) { ?>
                            <tr>
                                <td><?php echo $row["sid"]; ?></td>
                                <td><?php echo $row["t"]; ?></td>
                                <td><?php echo $row["d"]; ?></td>
                                <td><?php echo $row["mtemp"]; ?></td>
                                <td><?php echo $row["mhumid"]; ?></td>
                                <td><?php echo $row["mpriecip"]; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <h2>No results found for <?php echo $station_id; ?>.</h2>
            <?php }
            $station_id = null;
            $statement = null;
            $connection = null;
        } catch (PDOException $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

</body>

</html>