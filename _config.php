<?php 
$conn = mysqli_connect("localhost", 'root' , '', "anime") or die("Connection fail");


$websiteTitle = "Zoro";
$websiteUrl = "//{$_SERVER['SERVER_NAME']}/zoro";
$websiteLogo = $websiteUrl . "/files/images/logo_zoro.png";
$contactEmail = "@gmail.com";

$version = "0.2";

$discord = "https://dsc.gg/kirixen";
$github = "https://github.com/kirixen";
$twitter = "https://x.com/KiriX3n";
 
$disqus = "https://.disqus.com/embed.js";
$api = "https://aniwatch-api1-two.vercel.app"; 

$banner = $websiteUrl . "/files/images/banner.png";
?>
