<?php include "header.php";?>

<form method="post">
    <label for="city-name">City Name:</label>
    <input type="text" id="city-name" name="city-name">
    <input type="submit" name="submit" value="View Results">
</form>

<?php

    if (isset($_POST['submit'])) {
        require "config.php";

        try {
            $connection = new PDO($dsn, $username, $password, $options);
            $sql = "SELECT s.sid AS id, s.scity AS city, m.mtemp AS temperature, m.mhumid AS humidity, m.mpriecip AS precipitation
            FROM station AS s, measurement AS m,
                (SELECT s.sid, MAX(mtimestamp)AS recent
                FROM station AS s, measurement AS m
                WHERE s.sid = m.sid
                GROUP BY sid) AS r
            WHERE s.sid = r.sid AND m.sid = r.sid AND m.mtimestamp = r.recent AND s.scity = :city_name";
            $city_name = $_POST['city-name'];
            echo $city_name;
            print_r($_POST);
            $statement = $connection->prepare($sql);
            // $statement->bindParam(':city_name', $city_name, PDO::PARAM_STR);
            $statement->execute([':city_name'=>$city_name]);
            $result = $statement->fetchAll();
            print_r($result);
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
                        <?php foreach ($result as $row) { ?>
                        <tr>
                            <td><?php echo $row["id"]; ?></td>
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
        } catch(PDOException $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    }

    // function test_input($data) {
    //     $data = trim($data);
    //     $data = stripslashes($data);
    //     $data = htmlspecialchars($data);
    //     return $data;
    // }
?>

<?php include "footer.php";?>