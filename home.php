<?php 
session_start();
require('./_config.php');
$apiHome = "$api/api/v2/hianime/home";
$json = file_get_contents($apiHome);
$json = json_decode($json, true);
$topAiringAnimes = $json['data']['topAiringAnimes'];
$mostPopularAnimes = $json['data']['mostPopularAnimes'];
$mostFavoriteAnimes = $json['data']['mostFavoriteAnimes'];
$latestCompletedAnimes = $json['data']['latestCompletedAnimes'];
$latestEpisodeAnimes = $json['data']['latestEpisodeAnimes'];

$currentDate = date('Y-m-d');
$scheduleData = [];
for ($i = -1; $i <= 6; $i++) {
    $date = date('Y-m-d', strtotime("{$i} day", strtotime($currentDate)));
    $scheduleUrl = "$api/api/v2/hianime/schedule?date={$date}";
    $scheduleJson = @file_get_contents($scheduleUrl);
    if ($scheduleJson !== false) {
        $data = json_decode($scheduleJson, true);
        if (isset($data['data']['scheduledAnimes'])) {
            $scheduleData[$date] = $data['data']['scheduledAnimes'];
        }
    }
}
?>

<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title><?=$websiteTitle?> #1 Watch High Quality Anime Online Without Ads</title>
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="title" content="<?=$websiteTitle?> #1 Watch High Quality Anime Online Without Ads" />
    <meta name="description" content="<?=$websiteTitle?> #1 Watch High Quality Anime Online Without Ads. You can watch anime online free in HD without Ads. Best place for free find and one-click anime." />
    <meta name="keywords" content="<?=$websiteTitle?>, watch anime online, free anime, anime stream, anime hd, english sub, kissanime, gogoanime, animeultima, 9anime, 123animes, vidstreaming, gogo-stream, animekisa, zoro.to, gogoanime.run, animefrenzy, animekisa" />
    <meta name="charset" content="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <meta name="robots" content="index, follow" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="Content-Language" content="en" />
    <meta property="og:title" content="<?=$websiteTitle?> #1 Watch High Quality Anime Online Without Ads">
    <meta property="og:description" content="<?=$websiteTitle?> #1 Watch High Quality Anime Online Without Ads. You can watch anime online free in HD without Ads. Best place for free find and one-click anime.">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?=$websiteTitle?>">
    <meta property="og:url" content="<?=$websiteUrl?>/home">
    <meta itemprop="image" content="<?=$banner?>">
    <meta property="og:image" content="<?=$banner?>">
    <meta property="og:image:secure_url" content="<?=$banner?>">
    <meta property="og:image:width" content="650">
    <meta property="og:image:height" content="350">
    <meta name="apple-mobile-web-app-status-bar" content="#202125">
    <meta name="theme-color" content="#202125">
    <link rel="stylesheet" href="<?=$websiteUrl?>/files/css/style.css?v=<?=$version?>">
    <link rel="stylesheet" href="<?=$websiteUrl?>/files/css/min.css?v=<?=$version?>">
    <link rel="apple-touch-icon" href="<?=$websiteUrl?>/favicon.png?v=<?=$version?>" />
    <link rel="shortcut icon" href="<?=$websiteUrl?>/favicon.png?v=<?=$version?>" type="image/x-icon"/>
    <link rel="apple-touch-icon" sizes="180x180" href="<?=$websiteUrl?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=$websiteUrl?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=$websiteUrl?>/favicon-16x16.png">
    <link rel="mask-icon" href="<?=$websiteUrl?>/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="icon" sizes="192x192" href="<?=$websiteUrl?>/files/images/touch-icon-192x192.png?v=<?=$version?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/10.0.4/swiper-bundle.min.js"></script>
    <script type="text/javascript">
    setTimeout(function() {
        var wpse326013 = document.createElement('link');
        wpse326013.rel = 'stylesheet';
        wpse326013.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css';
        wpse326013.type = 'text/css';
        var godefer = document.getElementsByTagName('link')[0];
        godefer.parentNode.insertBefore(wpse326013, godefer);
        var wpse326013_2 = document.createElement('link');
        wpse326013_2.rel = 'stylesheet';
        wpse326013_2.href = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css';
        wpse326013_2.type = 'text/css';
        var godefer2 = document.getElementsByTagName('link')[0];
        godefer2.parentNode.insertBefore(wpse326013_2, godefer2);
    }, 500);
    </script>
    <noscript>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" />
    </noscript>
    <style>
        .airing-status {
            display: block;
            font-size: 0.8em;
            color: #666;
        }
        .countdown {
            font-size: 0.9em;
            color: #e74c3c;
        }
    </style>
</head>

<body data-page="page_home">
    <div id="sidebar_menu_bg"></div>
    <div id="wrapper" data-page="page_home">
        <?php include('./_php/header.php');?>
        <div class="clearfix"></div>
        <div class="deslide-wrap">
            <div class="container" style="max-width:100%!important;width:100%!important;">
                <div id="slider" class="swiper-container-initialized swiper-container-horizontal">
                    <?php include('./_php/slidebar.php'); ?>
                    <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets"></div>
                    <div class="swiper-navigation">
                        <div class="swiper-button swiper-button-next" tabindex="0" role="button" aria-label="Next slide"><i class="fas fa-angle-right"></i></div>
                        <div class="swiper-button swiper-button-prev" tabindex="0" role="button" aria-label="Previous slide"><i class="fas fa-angle-left"></i></div>
                    </div>
                    <div class="clearfix"></div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                </div>
            </div>
        </div>

        <?php include('./_php/trending.php')?>
        <div class="share-buttons share-buttons-home">
            <div class="container">
                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-63430163bc99824a"></script>
                <div class="share-buttons-block">
                    <div class="share-icon"></div>
                    <div class="sbb-title">
                        <span>Share <?=$websiteTitle?></span>
                        <p class="mb-0">to your friends</p>
                    </div>
                    <div class="addthis_inline_share_toolbox"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <div class="mba-block" style="display: block; text-align: center; margin: 1rem 0;"><a href="" target="_blank" rel="nofollow"><img style="width: 728px; height: auto; max-width: 100%;" src="https://t3.ftcdn.net/jpg/07/32/10/90/360_F_732109080_4lXwGofazqAiysUpcCnrbflsNOl9EMdW.jpg" alt="Soon"></a></div>
        

        <div id="anime-featured">
            <div class="container">
                <div class="anif-blocks">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="anif-block anif-block-01">
                                <div class="anif-block-header">Top Airing</div>
                                <div class="anif-block-ul">
                                    <ul class="ulclear">
                                        <?php foreach (array_slice($topAiringAnimes, 0, 5) as $anime) { ?>
                                            <li>
                                                <div class="film-poster item-qtip" data-id="<?= htmlspecialchars($anime['id']) ?>">
                                                    <a href="/anime/<?= htmlspecialchars($anime['id']) ?>">
                                                        <img data-src="<?= htmlspecialchars($anime['poster']) ?>" class="film-poster-img lazyload" alt="<?= htmlspecialchars($anime['name']) ?>" src="<?= htmlspecialchars($websiteUrl) ?>/files/images/no_poster.jpg">
                                                    </a>
                                                </div>
                                                <div class="film-detail">
                                                    <h3 class="film-name"><a href="/anime/<?= htmlspecialchars($anime['id']) ?>" title="<?= htmlspecialchars($anime['name']) ?>" data-jname="<?= htmlspecialchars($anime['jname']) ?>"><?= htmlspecialchars($anime['name']) ?></a></h3>
                                                    <div class="fd-infor">
                                                        <span class="fdi-item"><?= htmlspecialchars($anime['type']) ?></span>
                                                        <span class="dot"></span>
                                                        <span class="fdi-item">
                                                            <?php if ($anime['episodes']['sub'] !== null) echo "Sub " . $anime['episodes']['sub']; ?>
                                                            <?php if ($anime['episodes']['dub'] !== null) echo " | Dub " . $anime['episodes']['dub']; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="anif-block anif-block-03">
                                <div class="anif-block-header">Most Popular</div>
                                <div class="anif-block-ul">
                                    <ul class="ulclear">
                                        <?php foreach (array_slice($mostPopularAnimes, 0, 5) as $anime) { ?>
                                            <li>
                                                <div class="film-poster item-qtip" data-id="<?= htmlspecialchars($anime['id']) ?>">
                                                    <a href="/anime/<?= htmlspecialchars($anime['id']) ?>">
                                                        <img data-src="<?= htmlspecialchars($anime['poster']) ?>" class="film-poster-img lazyload" alt="<?= htmlspecialchars($anime['name']) ?>" src="<?= htmlspecialchars($websiteUrl) ?>/files/images/no_poster.jpg">
                                                    </a>
                                                </div>
                                                <div class="film-detail">
                                                    <h3 class="film-name"><a href="/anime/<?= htmlspecialchars($anime['id']) ?>" title="<?= htmlspecialchars($anime['name']) ?>" data-jname="<?= htmlspecialchars($anime['jname']) ?>"><?= htmlspecialchars($anime['name']) ?></a></h3>
                                                    <div class="fd-infor">
                                                        <span class="fdi-item"><?= htmlspecialchars($anime['type']) ?></span>
                                                        <span class="dot"></span>
                                                        <span class="fdi-item">
                                                            <?php if ($anime['episodes']['sub'] !== null) echo "Sub " . $anime['episodes']['sub']; ?>
                                                            <?php if ($anime['episodes']['dub'] !== null) echo " | Dub " . $anime['episodes']['dub']; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="anif-block anif-block-02">
                                <div class="anif-block-header">Most Favorite</div>
                                <div class="anif-block-ul">
                                    <ul class="ulclear">
                                        <?php foreach (array_slice($mostFavoriteAnimes, 0, 5) as $anime) { ?>
                                            <li>
                                                <div class="film-poster item-qtip" data-id="<?= htmlspecialchars($anime['id']) ?>">
                                                    <a href="/anime/<?= htmlspecialchars($anime['id']) ?>">
                                                        <img data-src="<?= htmlspecialchars($anime['poster']) ?>" class="film-poster-img lazyload" alt="<?= htmlspecialchars($anime['name']) ?>" src="<?= htmlspecialchars($websiteUrl) ?>/files/images/no_poster.jpg">
                                                    </a>
                                                </div>
                                                <div class="film-detail">
                                                    <h3 class="film-name"><a href="/anime/<?= htmlspecialchars($anime['id']) ?>" title="<?= htmlspecialchars($anime['name']) ?>" data-jname="<?= htmlspecialchars($anime['jname']) ?>"><?= htmlspecialchars($anime['name']) ?></a></h3>
                                                    <div class="fd-infor">
                                                        <span class="fdi-item"><?= htmlspecialchars($anime['type']) ?></span>
                                                        <span class="dot"></span>
                                                        <span class="fdi-item">
                                                            <?php if ($anime['episodes']['sub'] !== null) echo "Sub " . $anime['episodes']['sub']; ?>
                                                            <?php if ($anime['episodes']['dub'] !== null) echo " | Dub " . $anime['episodes']['dub']; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="anif-block anif-block-02">
                                <div class="anif-block-header">Latest Completed</div>
                                <div class="anif-block-ul">
                                    <ul class="ulclear">
                                        <?php foreach (array_slice($latestCompletedAnimes, 0, 5) as $anime) { ?>
                                            <li>
                                                <div class="film-poster item-qtip" data-id="<?= htmlspecialchars($anime['id']) ?>">
                                                    <a href="/anime/<?= htmlspecialchars($anime['id']) ?>">
                                                        <img data-src="<?= htmlspecialchars($anime['poster']) ?>" class="film-poster-img lazyload" alt="<?= htmlspecialchars($anime['name']) ?>" src="<?= htmlspecialchars($websiteUrl) ?>/files/images/no_poster.jpg">
                                                    </a>
                                                </div>
                                                <div class="film-detail">
                                                    <h3 class="film-name"><a href="/anime/<?= htmlspecialchars($anime['id']) ?>" title="<?= htmlspecialchars($anime['name']) ?>" data-jname="<?= htmlspecialchars($anime['jname']) ?>"><?= htmlspecialchars($anime['name']) ?></a></h3>
                                                    <div class="fd-infor">
                                                        <span class="fdi-item"><?= htmlspecialchars($anime['type']) ?></span>
                                                        <span class="dot"></span>
                                                        <span class="fdi-item">
                                                            <?php if ($anime['episodes']['sub'] !== null) echo "Sub " . $anime['episodes']['sub']; ?>
                                                            <?php if ($anime['episodes']['dub'] !== null) echo " | Dub " . $anime['episodes']['dub']; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="main-wrapper">
            <div class="container">
                <div id="main-content">
                <?php if(isset($_COOKIE['userID'])){ 
                    $user_id = $_COOKIE['userID'];
                    $select = mysqli_query($conn, "SELECT * FROM `user_history` WHERE user_id = $user_id");
                    $rows = mysqli_fetch_all($select, MYSQLI_ASSOC);
                    $rows = array_reverse($rows);
                    if(count($rows) != 0){ ?>
                <section class="block_area block_area_home">
                    <div class="block_area-header">
                        <div class="float-left bah-heading mr-4">
                            <h2 class="cat-heading"><i class="fas fa-history mr-2"></i>Continue Watching</h2>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="tab-content">
                        <div class="block_area-content block_area-list film_list film_list-grid">
                            <div class="film_list-wrap">
                                <?php 
                                foreach (array_slice($rows, 0, 4) as $rows) { ?>
                                <div class="flw-item">
                                    <div class="film-poster">
                                        <div class="tick ltr">
                                            <div class="tick-item-<?= $rows['dubOrSub'] ?> tick-eps amp-algn">
                                                <?= strtoupper($rows['dubOrSub']) ?>
                                            </div>
                                        </div>
                                        <div class="tick rtl">
                                            <div class="tick-item tick-eps amp-algn">Episode <?= $rows['anime_ep'] ?>
                                            </div>
                                        </div>
                                        <img class="film-poster-img lazyload" data-src="<?= $rows['anime_image'] ?>"
                                            src="<?= $websiteUrl ?>/files/images/no_poster.jpg"
                                            alt="<?= $rows['anime_title'] ?>">
                                        <a class="film-poster-ahref" href="/anime/<?= $rows['anime_id'] ?>"
                                            title="<?= $rows['anime_title'] ?>" data-jname="<?= $rows['anime_title'] ?>"><i
                                                class="fas fa-play"></i></a>
                                    </div>
                                    <div class="film-detail">
                                        <h3 class="film-name">
                                            <a href="/anime/<?= $rows['anime_id'] ?>" title="<?= $rows['anime_title'] ?>"
                                                data-jname="<?= $rows['anime_title'] ?>"><?= $rows['anime_title'] ?></a>
                                        </h3>
                                        <div class="fd-infor">
                                            <span class="fdi-item"><?= $rows['anime_release'] ?></span>
                                            <span class="dot"></span>
                                            <span class="fdi-item"><?= $rows['anime_type'] ?></span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </section>
                <?php } ?>
                <?php } ?>
                    <section class="block_area block_area_home">
                        <div class="block_area-header">
                            <div class="float-left bah-heading mr-4">
                                <h2 class="cat-heading">Latest Episode</h2>
                            </div>
                            <div class="float-right viewmore">
                                <a class="btn" href="/latest/subbed">View more<i
                                        class="fas fa-angle-right ml-2"></i></a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="tab-content">
                            <div class="block_area-content block_area-list film_list film_list-grid">
                                <div class="film_list-wrap">
                                    <?php foreach ($latestEpisodeAnimes as $anime) {
                                        if ($anime['episodes']['sub'] !== null) { ?>
                                    <div class="flw-item">
                                        <div class="film-poster">
                                            <div class="tick ltr">
                                                <div class="tick-item-sub tick-eps amp-algn">SUB</div>
                                            </div>
                                            <div class="tick rtl">
                                                <div class="tick-item tick-eps amp-algn">Episode 
                                                    <?= $anime['episodes']['sub'] ?>
                                                </div>
                                            </div>
                                            <img class="film-poster-img lazyload"
                                                data-src="<?= htmlspecialchars($anime['poster']) ?>"
                                                src="<?= htmlspecialchars($websiteUrl) ?>/files/images/no_poster.jpg"
                                                alt="<?= htmlspecialchars($anime['name']) ?>">
                                            <a class="film-poster-ahref" 
                                                href="/anime/<?= htmlspecialchars($anime['id']) ?><?= $anime['episodes']['sub'] ?>"
                                                title="<?= htmlspecialchars($anime['name']) ?>"
                                                data-jname="<?= htmlspecialchars($anime['jname']) ?>"><i class="fas fa-play"></i></a>
                                        </div>
                                        <div class="film-detail">
                                            <h3 class="film-name">
                                                <a href="/anime/<?= htmlspecialchars($anime['id']) ?>-episode-<?= $anime['episodes']['sub'] ?>"
                                                    title="<?= htmlspecialchars($anime['name']) ?>"
                                                    data-jname="<?= htmlspecialchars($anime['jname']) ?>">
                                                    <?= htmlspecialchars($anime['name']) ?>
                                                </a>
                                            </h3>
                                            <div class="fd-infor">
                                                <span class="fdi-item">Latest</span>
                                                <span class="dot"></span>
                                                <span class="fdi-item">SUB</span>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <?php } } ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </section>
                    <div class="clearfix"></div>

                    <!-- Schedule Section -->
                    <section class="block_area block_area_sidebar block_area-schedule schedule-full">
                        <div class="block_area-header">
                            <div class="float-left bah-heading mr-4">
                                <h2 class="cat-heading">Estimated Schedule</h2>
                            </div>
                            <div class="float-left bah-time">
                                <span class="current-time"><span id="timezone"></span> <span id="current-date"><?= date('F d, Y') ?></span> <span id="clock"></span></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="block_area-content">
                            <div class="table_schedule">
                                <div class="table_schedule-date">
                                    <div class="swiper-container swiper-container-initialized swiper-container-horizontal" id="schedule-swiper">
                                        <div class="swiper-wrapper">
                                            <?php for ($i = -1; $i <= 6; $i++): 
                                                $date = date('Y-m-d', strtotime("{$i} day", strtotime($currentDate)));
                                                $dayName = date('D', strtotime($date));
                                                $monthDay = date('M d', strtotime($date));
                                            ?>
                                                <div class="swiper-slide day-item" data-date="<?= $date ?>" style="width: 140px; margin-right: 13px;">
                                                    <div class="tsd-item <?= $i === 0 ? 'active' : '' ?>">
                                                        <span><?= $dayName ?></span>
                                                        <div class="date"><?= $monthDay ?></div>
                                                    </div>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                                    </div>
                                    <div class="ts-navigation">
                                        <button class="btn tsn-next" tabindex="0" role="button" aria-label="Next slide"><i class="fas fa-angle-right"></i></button>
                                        <button class="btn tsn-prev" tabindex="0" role="button" aria-label="Previous slide"><i class="fas fa-angle-left"></i></button>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <ul class="ulclear table_schedule-list limit-8" id="schedule-list">
                                    <?php foreach ($scheduleData[$currentDate] ?? [] as $anime): ?>
                                        <li data-timestamp="<?= $anime['airingTimestamp'] ?>" data-id="<?= htmlspecialchars($anime['id']) ?>">
                                            <a href="/anime/<?= htmlspecialchars($anime['id']) ?>" class="tsl-link">
                                                <div class="time"><?= htmlspecialchars($anime['time']) ?></div>
                                                <div class="film-detail">
                                                    <h3 class="film-name dynamic-name" data-jname="<?= htmlspecialchars($anime['jname']) ?>"><?= htmlspecialchars($anime['name']) ?></h3>
                                                    <div class="fd-play">
                                                        <button type="button" class="btn btn-sm btn-play"><i class="fas fa-play mr-2"></i>Episode <?= htmlspecialchars($anime['episode']) ?></button>
                                                    </div>
                                                    <span class="airing-status"><span class="countdown" id="countdown-<?= htmlspecialchars($anime['id']) ?>"></span></span>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <button id="scl-more" class="btn btn-sm btn-block btn-showmore" style="display: <?= count($scheduleData[$currentDate] ?? []) > 7 ? 'block' : 'none' ?>;"></button>
                            </div>
                        </div>
                    </section>
                </div>
                <?php include('./_php/sidenav.php'); ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php include('./_php/footer.php'); ?>
        <div id="mask-overlay"></div>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
        <script type="text/javascript" src="<?= $websiteUrl ?>/files/js/app.js"></script>
        <script type="text/javascript" src="<?= $websiteUrl ?>/files/js/comman.js"></script>
        <script type="text/javascript" src="<?= $websiteUrl ?>/files/js/movie.js"></script>
        <link rel="stylesheet" href="<?= $websiteUrl ?>/files/css/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript" src="<?= $websiteUrl ?>/files/js/function.js"></script>
        <script>
            $(document).ready(function() {
                const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                $('#timezone').text(`(${timeZone})`);

                const scheduleData = <?= json_encode($scheduleData) ?>;

                const scheduleSw = new Swiper('#schedule-swiper', {
                    slidesPerView: 7,
                    spaceBetween: 10,
                    initialSlide: 1,
                    navigation: {
                        nextEl: '.tsn-next',
                        prevEl: '.tsn-prev',
                    },
                    breakpoints: {
                        320: { slidesPerView: 3, spaceBetween: 10 },
                        360: { slidesPerView: 3, spaceBetween: 10 },
                        480: { slidesPerView: 3, spaceBetween: 10 },
                        640: { slidesPerView: 4, spaceBetween: 10 },
                        768: { slidesPerView: 5, spaceBetween: 10 },
                        1024: { slidesPerView: 7, spaceBetween: 13 },
                    },
                });

                $('.day-item').click(function() {
                    const date = $(this).data('date');
                    const animes = scheduleData[date] || [];
                    let html = '';
                    animes.forEach(anime => {
                        html += `
                            <li data-timestamp="${anime.airingTimestamp}" data-id="${anime.id}">
                                <a href="/anime/${anime.id}" class="tsl-link">
                                    <div class="time">${anime.time}</div>
                                    <div class="film-detail">
                                        <h3 class="film-name dynamic-name" data-jname="${anime.jname}">${anime.name}</h3>
                                        <div class="fd-play">
                                            <button type="button" class="btn btn-sm btn-play"><i class="fas fa-play mr-2"></i>Episode ${anime.episode}</button>
                                        </div>
                                        <span class="airing-status"><span class="countdown" id="countdown-${anime.id}"></span></span>
                                    </div>
                                </a>
                            </li>
                        `;
                    });
                    $('#schedule-list').html(html);
                    $('#scl-more').toggle(animes.length > 7);
                    $('.tsd-item').removeClass('active');
                    $(this).find('.tsd-item').addClass('active');
                    updateCountdowns();
                });

                $("#scl-more").click(function() {
                    $(this).parent().find(".limit-8").toggleClass("active");
                    $(this).toggleClass("active");
                });

                function updateClock() {
                    const now = new Date();
                    const hour = now.getHours() % 12 || 12;
                    const min = now.getMinutes();
                    const sec = now.getSeconds();
                    const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
                    $('#clock').html(`${String(hour).padStart(2, '0')}:${String(min).padStart(2, '0')}:${String(sec).padStart(2, '0')} ${ampm}`);
                }
                setInterval(updateClock, 1000);
                updateClock();

                function updateCountdowns() {
                    const now = new Date();
                    const countdownItems = $('#schedule-list li');
                    countdownItems.each(function() {
                        const timestamp = $(this).data('timestamp');
                        const animeId = $(this).data('id');
                        const countdownElement = $(`#countdown-${animeId}`);
                        if (timestamp) {
                            const airingTime = new Date(timestamp);
                            const diffMs = airingTime - now;
                            const diffSec = Math.floor(diffMs / 1000);

                            if (diffSec > 0) {
                                const hours = Math.floor(diffSec / 3600);
                                const minutes = Math.floor((diffSec % 3600) / 60);
                                const seconds = diffSec % 60;
                                countdownElement.text(`${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`);
                            } else if (diffSec < 0) {
                                const pastHours = Math.floor(Math.abs(diffSec) / 3600);
                                const pastMinutes = Math.floor((Math.abs(diffSec) % 3600) / 60);
                                const pastSeconds = Math.abs(diffSec) % 60;
                                countdownElement.text(`-${String(pastHours).padStart(2, '0')}:${String(pastMinutes).padStart(2, '0')}:${String(pastSeconds).padStart(2, '0')} (Aired)`);
                            } else {
                                countdownElement.text('00:00:00 (Live)');
                            }
                        }
                    });
                }
                setInterval(updateCountdowns, 1000);
                updateCountdowns();
            });
        </script>
    </div>
</body>

</html>