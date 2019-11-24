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
            $sql = "SELECT * 
                FROM station
                WHERE scity = :city_name";
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
                            <th>City Name</th>
                            <th>State</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php foreach ($result as $row) { ?>
                    <tr>
                        <td><?php echo $row["sid"]; ?></td>
                        <td><?php echo $row["scity"]; ?></td>
                        <td><?php echo $row["sstate"]; ?></td>
                        <td><?php echo $row["slatitude"]; ?></td>
                        <td><?php echo $row["slongitude"]; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <blockquote>No results found for <?php echo $city_name; ?>.</blockquote>
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