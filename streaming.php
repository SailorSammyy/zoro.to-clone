<?php
require('./_config.php');
session_start();

/* ---------------- FAST HTTP fetch wrapper ---------------- */
function fastFetch($url) {
    // Try curl (faster)
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        $out = curl_exec($ch);
        curl_close($ch);
        if ($out) return $out;
    }

    // Ultra-fast fallback (file_get_contents)
    $ctx = stream_context_create([
        'http' => ['timeout' => 3],
        'https' => ['timeout' => 3]
    ]);

    return @file_get_contents($url, false, $ctx);
}

/* ---------- Parse URL ---------- */
$pathParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$slug = end($pathParts);

if (!str_contains($slug, '-episode-')) {
    header("Location: /watch/" . $slug . "-episode-1");
    exit;
}

if (preg_match('/^(.+)-episode-([0-9]+)$/', $slug, $m)) {
    $animeID = $m[1];
    $epNumber = max(1, intval($m[2]));
} else {
    header("Location: /watch/" . $slug . "-episode-1");
    exit;
}

$anime = $animeID;

/* ---------- Episodes list ---------- */
$apiBase = rtrim($api, '/');
$episodesUrl = "$apiBase/api/v2/hianime/anime/" . urlencode($animeID) . "/episodes";

$episodesJson = fastFetch($episodesUrl);
$episodesData = $episodesJson ? json_decode($episodesJson, true, 512, JSON_INVALID_UTF8_IGNORE) : null;

$rawEpisodes = $episodesData['data']['episodes'] ?? [];

$episodelist = [];
$realEpisodeId = null;
$megaep = null;

foreach ($rawEpisodes as $ep) {
    $num = $ep['number'] ?? ($ep['episodeNum'] ?? null);
    if (!$num) continue;

    $num = intval($num);
    $apiEpId = $ep['episodeId'] ?? '';
    $title = $ep['title'] ?? '';

    // Fast extract
    if ($apiEpId && ($pos = strpos($apiEpId, '?ep=')) !== false) {
        $thisMegaep = intval(substr($apiEpId, $pos + 4));
    } else {
        $thisMegaep = null;
    }

    if ($num === $epNumber) {
        $realEpisodeId = $apiEpId;
        $megaep = $thisMegaep;
    }

    $episodelist[] = [
        'episodeNum'  => $num,
        'episodeId'   => $animeID . "-episode-$num",
        'title'       => $title,
        'apiEpisodeId'=> $apiEpId,
        'megaep'      => $thisMegaep
    ];
}

if (!$realEpisodeId) {
    header("Location: /watch/" . $animeID . "-episode-1");
    exit;
}

/* ---------- Servers ---------- */
$serverUrl = "$apiBase/api/v2/hianime/episode/servers?animeEpisodeId=" . urlencode($realEpisodeId);

$serverJson = fastFetch($serverUrl);
$getEpisode = $serverJson ? json_decode($serverJson, true, 512, JSON_INVALID_UTF8_IGNORE) : null;

if (!isset($getEpisode['data'])) {
    $getEpisode = [
        'success' => false,
        'data' => ['sub' => [], 'dub' => [], 'raw' => []]
    ];
}

$getEpisode['ep_num'] = $epNumber;
$getEpisode['episodeId'] = $realEpisodeId;
$getEpisode['animeNameWithEP'] = ($getEpisode['data']['animeName'] ?? $animeID) . " Episode $epNumber";

/* ---------- Stream Selection ---------- */
$availableStreams = ['sub','dub','raw'];
$req = $_GET['category'] ?? null;
$requestedCategory = $req ? strtolower(trim($req)) : null;

$preferredOrder = $requestedCategory && in_array($requestedCategory, $availableStreams)
    ? [$requestedCategory]
    : $availableStreams;

$selectedStream = null;
$dub = 'sub';

foreach ($preferredOrder as $cat) {
    if (!empty($getEpisode['data'][$cat])) {
        $selectedStream = $getEpisode['data'][$cat][0];
        $dub = $cat;
        break;
    }
}
if (!$selectedStream) $selectedStream = ['serverId'=>1,'serverName'=>'unknown'];

$playerServerId = $selectedStream['serverId'] ?? 1;
$playerCategory = $dub;

$playerSrc = $websiteUrl . "/player/v1.php?animeEpisodeId=" . urlencode($realEpisodeId)
    . "&server=" . $playerServerId
    . "&category=" . $playerCategory;

/* ---------- Anime info ---------- */
$animeUrl = "$apiBase/api/v2/hianime/anime/" . urlencode($animeID);

$animeJson = fastFetch($animeUrl);
$getAnime = $animeJson ? json_decode($animeJson, true, 512, JSON_INVALID_UTF8_IGNORE) : null;

$animeRoot = $getAnime['data']['anime'][0] ?? [];
$info = $animeRoot['info'] ?? [];
$more = $animeRoot['moreInfo'] ?? [];


$animeName = $animeRoot['data']['anime']['info']['name'];
$animePoster = $animeRoot['data']['anime']['info']['poster'];
$animeDescription = $animeRoot['data']['anime']['info']['description'];

$getAnime['name'] = $info['name'] ?? $animeID;
$getAnime['synopsis'] = $info['description'] ?? '';
$getAnime['imageUrl'] = $info['poster'];
$getAnime['status'] = $more['status'] ?? '';
$getAnime['released'] = $more['aired'] ?? '';
$getAnime['othername'] = $info['othername'] ?? '';
$getAnime['type'] = $info['stats']['type'] ?? '';

$ANIME_NAME = $getAnime['name'];
$ANIME_IMAGE = $getAnime['imageUrl'];
$ANIME_RELEASED = $getAnime['released'];
$ANIME_TYPE = $getAnime['type'] ?? '';

/* ---------- Pageview counter ---------- */
$pageID = $realEpisodeId;
$escapedPageID = mysqli_real_escape_string($conn, $pageID);

$q = mysqli_query($conn, "SELECT * FROM `pageview` WHERE pageID='$escapedPageID' LIMIT 1");
$rows = mysqli_fetch_assoc($q);

if (!$rows) {
    $escapedAnime = mysqli_real_escape_string($conn, $animeID);
    mysqli_query($conn,
        "INSERT INTO pageview (pageID,totalview,like_count,dislike_count,animeID)
         VALUES('$escapedPageID',1,1,0,'$escapedAnime')"
    );
    $insertId = mysqli_insert_id($conn);
    $q2 = mysqli_query($conn, "SELECT * FROM pageview WHERE id='$insertId' LIMIT 1");
    $rows = mysqli_fetch_assoc($q2);
}

$counter = intval($rows['totalview'] ?? 0) + 1;
$id = intval($rows['id'] ?? 0);

if ($id) {
    mysqli_query($conn, "UPDATE pageview SET totalview=$counter WHERE id=$id");
}

$like_count = intval($rows['like_count'] ?? 0);
$dislike_count = intval($rows['dislike_count'] ?? 0);
$totalVotes = $like_count + $dislike_count;

/* ---------- User history ---------- */
if (!empty($_COOKIE['userID'])) {
    $userID = mysqli_real_escape_string($conn, $_COOKIE['userID']);
    $uh_q = mysqli_query($conn,
        "SELECT * FROM user_history WHERE user_id='$userID' AND anime_id='$escapedPageID' LIMIT 1"
    );
    $uh = mysqli_fetch_assoc($uh_q);

    $sqlInsert = "INSERT INTO user_history (user_id,anime_id,anime_title,anime_ep,anime_image,anime_release,dubOrSub,anime_type)
        VALUES('$userID','$escapedPageID','".mysqli_real_escape_string($conn, $ANIME_NAME)."','$epNumber',
        '".mysqli_real_escape_string($conn, $ANIME_IMAGE)."','".mysqli_real_escape_string($conn, $ANIME_RELEASED)."',
        '".mysqli_real_escape_string($conn, $playerCategory)."','".mysqli_real_escape_string($conn, $ANIME_TYPE)."')";

    if (!$uh) {
        mysqli_query($conn, $sqlInsert);
    } else {
        mysqli_query($conn, "DELETE FROM user_history WHERE id=" . intval($uh['id']));
        mysqli_query($conn, $sqlInsert);
    }
}

/* ---------- Legacy vars ---------- */
$url = $realEpisodeId;

$getEpisode['prevEpLink'] = $getEpisode['data']['prevEpLink'] ?? ($epNumber>1 ? "/watch/{$animeID}-episode-".($epNumber-1) : "");
$getEpisode['nextEpLink'] = $getEpisode['data']['nextEpLink'] ?? ("/watch/{$animeID}-episode-".($epNumber+1));
$getEpisode['prevEpText'] = $epNumber>1 ? "Episode ".($epNumber-1) : "";
$getEpisode['nextEpText'] = "Episode ".($epNumber+1);

$getEpisode['animeNameWithEP'] = $ANIME_NAME . " Episode $epNumber";
$getEpisode['ep_num'] = $epNumber;
$getEpisode['selectedStream'] = $selectedStream;
$getEpisode['playerSrc'] = $playerSrc;

$download = $websiteUrl . "/download/" . urlencode($realEpisodeId);

$firstEpID = $episodelist[0]['episodeId'] ?? ($animeID . "-episode-1");

$recent_limit = 10;

?>

<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Watch
        <?= $getEpisode['animeNameWithEP'] ?>on
        <?= $websiteTitle ?>
    </title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="title" content="Watch <?= $getEpisode['animeNameWithEP'] ?>on <?= $websiteTitle ?>">
    <meta name="description" content="<?= substr($getAnime['synopsis'], 0, 150) ?> ... at <?= $websiteUrl ?>">
    <meta name="keywords"
        content="<?= $websiteTitle ?>, <?= $getEpisode['animeNameWithEP'] ?>,<?= $getAnime['othername'] ?><?= $getAnime['name'] ?>, watch anime online, free anime, anime stream, anime hd, english sub">
    <meta name="charset" content="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="robots" content="index, follow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Language" content="en">
    <meta property="og:title" content="Watch <?= $getEpisode['animeNameWithEP'] ?>on <?= $websiteTitle ?>">
    <meta property="og:description" content="<?= substr($getAnime['synopsis'], 0, 150) ?> ... at <?= $websiteUrl ?>">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= $websiteTitle ?>">
    <meta property="og:url" content="<?= $websiteUrl ?>/anime/<?= $url ?>">
    <meta itemprop="image" content="<?= $getAnime['imageUrl'] ?>">
    <meta property="og:image" content="<?= $getAnime['imageUrl'] ?>">
    <meta property="twitter:title" content="Watch <?= $getEpisode['animeNameWithEP'] ?>on <?= $websiteTitle ?>">
    <meta property="twitter:description" content="<?= substr($getAnime['synopsis'], 0, 150) ?> ... at <?= $websiteUrl ?>">
    <meta property="twitter:url" content="<?= $websiteUrl ?>/anime/<?= $url ?>">
    <meta property="twitter:card" content="summary">
    <meta name="apple-mobile-web-app-status-bar" content="#202125">
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-63430163bc99824a"></script>
    <meta name="theme-color" content="#202125">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css"
        type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
        type="text/css">
    <link rel="apple-touch-icon" href="<?=$websiteUrl?>/favicon.png?v=<?=$version?>" />
    <link rel="shortcut icon" href="<?=$websiteUrl?>/favicon.png?v=<?=$version?>" type="image/x-icon"/>
    <link rel="apple-touch-icon" sizes="180x180" href="<?=$websiteUrl?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=$websiteUrl?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=$websiteUrl?>/favicon-16x16.png">
    <link rel="mask-icon" href="<?=$websiteUrl?>/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="icon" sizes="192x192" href="<?=$websiteUrl?>/files/images/touch-icon-192x192.png?v=<?=$version?>">
    <link rel="stylesheet" href="<?= $websiteUrl ?>/files/css/style.css?v=<?= $version ?>">
    <link rel="stylesheet" href="<?= $websiteUrl ?>/files/css/min.css?v=<?= $version ?>">
</head>

<body data-page="movie_watch">
    <div id="sidebar_menu_bg"></div>
    <div id="wrapper" data-page="movie_watch">
        <?php include('./_php/header.php'); ?>
        <div class="clearfix"></div>
        <div id="main-wrapper" date-page="movie_watch" data-id="">
            <div id="ani_detail">
                <div class="ani_detail-stage">
                    <div class="container">
                        <div class="anis-cover-wrap">
                            <div class="anis-cover"
                                style="background-image: url('<?= $websiteUrl ?>/files/images/banner.webp')">
                            </div>
                        </div>
                        <div class="anis-watch-wrap">
                            <div class="prebreadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li itemprop="itemListElement" itemscope=""
                                            itemtype="http://schema.org/ListItem" class="breadcrumb-item">
                                            <a itemprop="item" href="/"><span itemprop="name">Home</span></a>
                                            <meta itemprop="position" content="1">
                                        </li>
                                        <li itemprop="itemListElement" itemscope=""
                                            itemtype="http://schema.org/ListItem" class="breadcrumb-item">
                                            <a itemprop="item" href="/anime"><span itemprop="name">Anime</span></a>
                                            <meta itemprop="position" content="2">
                                        </li>
                                        <li itemprop="itemListElement" itemscope=""
                                            itemtype="http://schema.org/ListItem" class="breadcrumb-item"
                                            aria-current="page">
                                            <a itemprop="item" href="/anime/<?= $anime ?>"><span
                                                    itemprop="name"><?= $getAnime['name'] ?></span></a>
                                            <meta itemprop="position" content="3">
                                        </li>
                                        <li itemprop="itemListElement" itemscope=""
                                            itemtype="http://schema.org/ListItem" class="breadcrumb-item"
                                            aria-current="page">
                                            <a itemprop="item" href="<?= $websiteUrl ?>/watch/<?= $url ?>"><span
                                                    itemprop="name">Episode <?= $getEpisode['ep_num'] ?></span></a>
                                            <meta itemprop="position" content="4">
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="anis-watch anis-watch-tv">
                                <div class="watch-player">
                                    <div class="player-frame">
                                        <div class="loading-relative loading-box" id="embed-loading">
                                            <div class="loading">
                                                <div class="span1"></div>
                                                <div class="span2"></div>
                                                <div class="span3"></div>
                                            </div>
                                        </div>
                                        <!---recommended to use Anikatsu Servers only ---->
                                        <iframe name="iframe-to-load"
                                            src="https://megaplay.buzz/stream/s-2/<?=$megaep?>/sub" frameborder="0"
                                            scrolling="no"
                                            allow="accelerometer;autoplay;encrypted-media;gyroscope;picture-in-picture"
                                            allowfullscreen="true" webkitallowfullscreen="true"
                                            mozallowfullscreen="true"></iframe>
                                    </div>
                                    <div class="player-controls">
                                        <div class="pc-item pc-resize">
                                            <a href="javascript:;" id="media-resize" class="btn btn-sm"><i
                                                    class="fas fa-expand mr-1"></i>Expand</a>
                                        </div>
                                        <div class="pc-item pc-toggle pc-light">
                                            <div id="turn-off-light" class="toggle-basic">
                                                <span class="tb-name"><i class="fas fa-lightbulb mr-2"></i>Light</span>
                                                <span class="tb-result"></span>
                                            </div>
                                        </div>
                                        <div class="pc-item pc-download">
                                            <a class="btn btn-sm pc-download" href="<?= $download ?>" target="_blank"><i
                                                    class="fas fa-download mr-2"></i>Download</a>
                                        </div>
                                        <div class="pc-right">
                                            <?php if ($getEpisode['prevEpText'] == "") {
                                                echo "";
                                            } else { ?>
                                                <div class="pc-item pc-control block-prev">
                                                    <a class="btn btn-sm btn-prev"
                                                        href="/watch<?= $getEpisode['prevEpLink'] ?>"><i
                                                            class="fas fa-backward mr-2"></i>Prev</a>
                                                </div>&nbsp;
                                            <?php } ?>
                                            <?php if ($getEpisode['nextEpText'] == "") {
                                                echo "";
                                            } else { ?>
                                                <div class="pc-item pc-control block-next">
                                                    <a class="btn btn-sm btn-next"
                                                        href="/watch<?= $getEpisode['nextEpLink'] ?>"><i
                                                            class="fas fa-forward ml-2"></i>Next</a>
                                                </div>
                                            <?php } ?>
                                            <div class="pc-item pc-fav" id="watch-list-content"></div>
                                            <div class="pc-item pc-download" style="display:none;">
                                                <a class="btn btn-sm pc-download"><i
                                                        class="fas fa-download mr-2"></i>Download</a>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="player-servers">
                                    <div id="servers-content">
                                        <div class="ps_-status">
                                            <div class="content">
                                                <div class="server-notice"><strong>Currently watching <b>Episode
                                                            <?= $getEpisode['ep_num'] ?>
                                                        </b></strong> Switch to alternate
                                                    servers in case of error.</div>
                                            </div>
                                        </div>
                                        <div class="ps_-block ps_-block-sub servers-mixed">
                                            <div class="ps__-title"><i class="fas fa-server mr-2"></i>SERVERS:</div>
                                            <div class="ps__-list">
                                                <div class="item">
                                                    <a id="server1" href="https://megaplay.buzz/stream/s-2/<?=$megaep?>/sub"
                                                        target="iframe-to-load" class="btn btn-server active">SUB</a>
                                                </div>
                                        <?php if (!empty($getEpisode['data']['dub'])): ?>
    <div class="item">
        <a id="server2"
            href="https://megaplay.buzz/stream/s-2/<?=$megaep?>/dub"
            target="iframe-to-load" class="btn btn-server">DUB</a>
    </div>
<?php endif; ?>

                                            </div>
                                            <div class="clearfix"></div>
                                            <div id="source-guide"></div>
                                        </div>
                                    </div>
                                </div>

                                <div id="episodes-content">
                                    <div class="seasons-block seasons-block-max">
                                        <div id="detail-ss-list" class="detail-seasons">
                                            <div class="detail-infor-content">
                                                <div style="min-height:43px;" class="ss-choice">
                                                    <div class="ssc-list">
                                                        <div id="ssc-list" class="ssc-button">
                                                            <div class="ssc-label">List of episodes:</div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div id="episodes-page-1" class="ss-list ss-list-min" data-page="1"
                                                    style="display:block;">

                                                    <?php
                                                    foreach ($episodelist as $episodelist) { ?>
                                                        <a title="Episode <?= $episodelist['episodeNum'] ?>"
                                                            class="ssl-item ep-item <?php if ($getEpisode['ep_num'] === $episodelist['episodeNum']) {
                                                                echo 'active';
                                                            } ?>"
                                                            href="/watch/<?= $episodelist['episodeId'] ?>">
                                                            <div class="ssli-order" title="">
                                                                <?= $episodelist['episodeNum'] ?>
                                                            </div>
                                                            <div class="ssli-detail">
                                                                <div class="ep-name dynamic-name" data-jname="" title="">
                                                                </div>
                                                            </div>
                                                            <div class="ssli-btn">
                                                                <div class="btn btn-circle"><i class="fas fa-play"></i>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="anis-watch-detail">
                                <div class="anis-content">
                      <div class="anisc-poster">
    <div class="film-poster">
        <img src="<?= $getAnime['imageUrl'] ?>"
             class="film-poster-img lazyloaded"
             alt="<?= $getAnime['name'] ?>">
    </div>
</div>

<div class="anisc-detail">
    <h2 class="film-name">
        <a href="/anime/<?= urlencode($animeID) ?>"
           class="text-white dynamic-name"
           title="<?= $getAnime['name'] ?>"
           data-jname="<?= $getAnime['name'] ?>"
           style="opacity: 1;">
           <?= $getAnime['name'] ?>
        </a>
    </h2>
</div>

                                        <div class="film-stats">
                                            <div class="tac tick-item tick-quality">HD</div>
                                            <div class="tac tick-item tick-dub">SUB</div>
<?php if (!empty($getEpisode['data']['dub'])): ?>
    <div class="tac tick-item tick-dub">DUB</div>
<?php endif; ?>

                                            <div class="tac tick-item tick-dub">
                                                <?php if ($counter) {
                                                    echo "VIEWS: " . $counter;
                                                }
                                                ; ?>
                                            </div>
                                            <span class="dot"></span>
                                            <span class="item">
                                                <?= $getAnime['status'] ?>
                                            </span>
                                            <span class="dot"></span>
                                            <span class="item">
                                                <?= $getAnime['released'] ?>
                                            </span>
                                            <span class="dot"></span>
                                            <span class="item">
                                                <?= $getAnime['othername'] ?>
                                            </span>
                                            <span class="dot"></span>
                                            <span class="item">
                                                <?= $getAnime['type'] ?>
                                            </span>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="film-description m-hide">
                                            <div class="text">
                                                <?= $animeDescription ?>
                                            </div>
                                        </div>
                                        <div class="film-text m-hide mb-3">
                                            <?= $websiteTitle ?> is a site to watch online anime like
                                            <strong>
                                                <?= $animeName ?>
                                            </strong> online, or you can even watch
                                            <strong>
                                                <?= $animeName ?>
                                            </strong> in HD quality
                                        </div>
                                        <div class="block"><a href="/anime/<?= $anime ?>" class="btn btn-xs btn-light"><i
                                                    class="fas fa-book-open mr-2"></i> View detail</a></div>

                                        <?php
                                        $likeClass = "far";
                                        if (isset($_COOKIE['like_' . $id])) {
                                            $likeClass = "fas";
                                        }

                                        $dislikeClass = "far";
                                        if (isset($_COOKIE['dislike_' . $id])) {
                                            $dislikeClass = "fas";
                                        }
                                        ?>
                                        <div class="dt-rate">
                                            <div id="vote-info">
                                                <div class="block-rating">
                                                    <div class="rating-result">
                                                        <div class="rr-mark float-left">
                                                            <strong><i class="fas fa-star text-warning mr-2"></i><span
                                                                    id="ratingAnime"><?= round((10 * $like_count + 5 * $dislike_count) / ($like_count + $dislike_count), 2) ?></span></strong>
                                                            <small id="votedCount">(
                                                                <?php
                                                                if (isset($totalVotes)) {
                                                                    echo $totalVotes;
                                                                } ?> Voted)
                                                            </small>
                                                        </div>
                                                        <div class="rr-title float-right">Vote now</div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="description">What do you think about this anime?</div>
                                                    <div class="button-rate">
                                                        <button type="button"
                                                            onclick="setLikeDislike('dislike','<?= $id ?>')"
                                                            class="btn btn-emo rate-bad btn-vote" style="width:50%"
                                                            data-mark="dislike"><i id="dislike"
                                                                class="<?php echo $dislikeClass ?> fa-thumbs-down">
                                                            </i><span id="dislikeMsg"
                                                                class="ml-2">Dislike</span></button>
                                                        <button onclick="setLikeDislike('like','<?= $id ?>')"
                                                            type="button" class="btn btn-emo rate-good btn-vote"
                                                            style="width:50%"><i id="like"
                                                                class="<?php echo $likeClass ?> fa-thumbs-up"> </i><span
                                                                id="likeMsg" class="ml-2">Like</span></button>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="share-buttons share-buttons-detail">
                <div class="container">
                    <div class="share-buttons-block">
                        <div class="share-icon"></div>
                        <div class="sbb-title">
                            <span>Share Anime</span>
                            <p class="mb-0">to your friends</p>
                        </div>
                        <div class="addthis_inline_share_toolbox"></div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div id="main-content">
                    <section class="block_area block_area-comment">
                        <div class="block_area-header block_area-header-tabs">
                            <div class="float-left bah-heading mr-4">
                                <h2 class="cat-heading">Comments</h2>
                            </div>
                            <div class="float-left bah-setting">

                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="tab-content">
                            <?php include('./_php/disqus.php'); ?>
                        </div>
                    </section>

                    <?php include('./_php/recent-releases.php'); ?>
                    <div class="clearfix"></div>
                </div>
                <?php include('./_php/sidenav.php'); ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php include('./_php/footer.php'); ?>
        <div id="mask-overlay"></div>
        <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js?v=<?=$version?>"></script>
        <script type="text/javascript"
            src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js?v=<?=$version?>"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
        <script type="text/javascript" src="<?=$websiteUrl?>/files/js/app.js?v=<?=$version?>"></script>
        <script type="text/javascript" src="<?=$websiteUrl?>/files/js/comman.js?v=<?=$version?>"></script>
        <script type="text/javascript" src="<?=$websiteUrl?>/files/js/movie.js?v=<?=$version?>"></script>
        <link rel="stylesheet" href="<?=$websiteUrl?>/files/css/jquery-ui.css?v=<?=$version?>">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js?v=<?=$version?>"></script>
        <script type="text/javascript">
            $(".btn-server").click(function () {
                $(".btn-server").removeClass("active");
                $(this).closest(".btn-server").addClass("active");
            });
        </script>
        <script type="text/javascript">
            if ('<?= $likeClass ?>' === 'fas') {
                document.getElementById('likeMsg').innerHTML = "Liked"
            }
            if ('<?= $dislikeClass ?>' === 'fas') {
                document.getElementById('dislikeMsg').innerHTML = "Disliked"
            }

            function setLikeDislike(type, id) {
                jQuery.ajax({
                    url: '<?= $websiteUrl ?>/setLikeDislike.php',
                    type: 'post',
                    data: 'type=' + type + '&id=' + id,
                    success: function (result) {
                        result = jQuery.parseJSON(result);
                        if (result.opertion == 'like') {
                            jQuery('#like').removeClass('far');
                            jQuery('#like').addClass('fas');
                            jQuery('#dislike').addClass('far');
                            jQuery('#dislike').removeClass('fas');
                            jQuery('#likeMsg').html("Liked")
                            jQuery('#dislikeMsg').html("Dislike")
                        }
                        if (result.opertion == 'unlike') {
                            jQuery('#like').addClass('far');
                            jQuery('#like').removeClass('fas');
                            jQuery('#likeMsg').html("Like")
                        }

                        if (result.opertion == 'dislike') {
                            jQuery('#dislike').removeClass('far');
                            jQuery('#dislike').addClass('fas');
                            jQuery('#like').addClass('far');
                            jQuery('#like').removeClass('fas');
                            jQuery('#dislikeMsg').html("Disliked")
                            jQuery('#likeMsg').html("Like")
                        }
                        if (result.opertion == 'undislike') {
                            jQuery('#dislike').addClass('far');
                            jQuery('#dislike').removeClass('fas');
                            jQuery('#dislikeMsg').html("Dislike")
                        }


                        jQuery('#votedCount').html(
                            `(${parseInt(result.like_count) + parseInt(result.dislike_count)} Voted)`
                        );
                        jQuery('#ratingAnime').html(((parseInt(result.like_count) *
                            10 + parseInt(result.dislike_count) * 5) / (
                                parseInt(result.like_count) + parseInt(
                                    result.dislike_count))).toFixed(2));
                    }

                });
            }
        </script>
    </div>
</body>

</html>