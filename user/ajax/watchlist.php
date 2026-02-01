<?php
include('../../_config.php');
session_start();
if(isset($_COOKIE['userID'])){
    $user_id = $_COOKIE['userID'];
};
include '../../_config.php';
 $animeID = $_POST['btnValue'];

 $stmt = $conn->prepare("SELECT id FROM `watch_later` WHERE user_id = ? AND anime_id = ?");
 $stmt->bind_param("ss", $user_id, $animeID);
 $stmt->execute();
 $result = $stmt->get_result();
 $query = $result->fetch_assoc(); 
 $stmt->close();

if(isset($query['id'])){
    $id = $query['id'];
    $stmt = $conn->prepare("DELETE FROM `watch_later` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    echo " &nbsp;<i class='fas fa-plus mr-2'></i>&nbsp;Add to List&nbsp;";
}else{
    $getAnime = file_get_contents("$api/api/v2/hianime/anime/{$animeID}");
    $getAnime = json_decode($getAnime, true);
    
    $animeInfo = $getAnime['data']['anime']['info'] ?? [];
    $animeMoreInfo = $getAnime['data']['anime']['moreInfo'] ?? [];
    
    $name = $animeInfo['name'] ?? '';
    $type = $animeInfo['stats']['type'] ?? '';
    $image = $animeInfo['poster'] ?? '';
    $release = explode(' to ', $animeMoreInfo['aired'] ?? '')[0] ?? '';

    $stmt = $conn->prepare("INSERT INTO `watch_later` (user_id, name, anime_id, image, type, released) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $user_id, $name, $animeID, $image, $type, $release);
    $stmt->execute();
    $stmt->close();

    echo " &nbsp;<i class='fas fa-minus mr-2'></i>&nbsp;Remove from List&nbsp;";
}
?>