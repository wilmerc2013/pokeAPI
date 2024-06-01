<?php
set_time_limit(0);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pokedex";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getPokemonData($id) {
    $url = "https://pokeapi.co/api/v2/pokemon/$id";
    $response = file_get_contents($url);
    if ($response === FALSE) {
        return null;
    }
    return json_decode($response, true);
}

$start = 1001;
$end = 1025;

for ($i = $start; $i <= $end; $i++) {
    $data = getPokemonData($i);

    if ($data) {
        $id = $data['id'];
        $name = $data['name'];
        $image_url = $data['sprites']['front_default'];

        $sql = "INSERT INTO pokemon (id, name, image) VALUES ($id, '$name', '$image_url')
                ON DUPLICATE KEY UPDATE name=VALUES(name), image=VALUES(image)";
        $conn->query($sql);

        foreach ($data['types'] as $type) {
            $type_name = $type['type']['name'];

            $type_query = "SELECT id FROM type WHERE name='$type_name'";
            $type_result = $conn->query($type_query);

            if ($type_result->num_rows == 0) {
                $insert_type_sql = "INSERT INTO type (name) VALUES ('$type_name')";
                $conn->query($insert_type_sql);
                $type_id = $conn->insert_id;
            } else {
                $type_id_row = $type_result->fetch_assoc();
                $type_id = $type_id_row['id'];
            }

            $relation_query = "SELECT * FROM relationpokemontype WHERE pokemon_id=$id AND type_id=$type_id";
            $relation_result = $conn->query($relation_query);

            if ($relation_result->num_rows == 0) {
                $insert_relation_sql = "INSERT INTO relationpokemontype (pokemon_id, type_id) VALUES ($id, $type_id)";
                $conn->query($insert_relation_sql);
            }
        }
    } else {
        echo "Failed to fetch data for Pokémon ID $i<br>";
    }
    sleep(1);
}

$conn->close();
?>