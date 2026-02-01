<?php 
session_start();
require_once('./_config.php');
$parts = parse_url($_SERVER['REQUEST_URI']); 
$page_url = explode('/', $parts['path']);
$animeId = $page_url[count($page_url)-1];

$getAnime = file_get_contents("$api/api/v2/hianime/anime/{$animeId}");
$getAnime = json_decode($getAnime, true);
$animeInfo = $getAnime['data']['anime']['info'] ?? [];
$animeMoreInfo = $getAnime['data']['anime']['moreInfo'] ?? [];

$getEpisodes = file_get_contents("$api/api/v2/hianime/anime/{$animeId}/episodes");
$getEpisodes = json_decode($getEpisodes, true);
$episodesList = $getEpisodes['data']['episodes'] ?? [];
$firstEpisodeId = '';

if (!empty($episodesList)) {
    $firstEpisodeId = $episodesList[0]['episodeId'];
}
?>

<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Watch <?php echo htmlspecialchars($animeInfo['name']); ?> - <?php echo $websiteTitle; ?></title>
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="title" content="Watch <?php echo htmlspecialchars($animeInfo['name']); ?> - <?php echo $websiteTitle; ?>" />
    <meta name="description" content="<?php echo substr(htmlspecialchars($animeInfo['description']), 0, 150); ?>.... Read More On <?php echo $websiteTitle; ?>" />
    <meta name="keywords" content="<?php echo htmlspecialchars($animeInfo['name']); ?>, <?php echo htmlspecialchars($animeMoreInfo['japanese'] ?? $animeInfo['name']); ?>, <?php echo $websiteTitle; ?>, watch anime online, free anime, anime stream, anime hd, english sub, kissanime, gogoanime, animeultima, 9anime, 123animes, <?php echo $websiteTitle; ?>, vidstreaming, gogo-stream, animekisa, zoro.to, gogoanime.run, animefrenzy, animekisa" />
    <meta name="charset" content="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <meta name="robots" content="index, follow" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="Content-Language" content="en" />
    <meta property="og:title" content="Watch <?php echo htmlspecialchars($animeInfo['name']); ?> - <?php echo $websiteTitle; ?>">
    <meta property="og:description" content="<?php echo substr(htmlspecialchars($animeInfo['description']), 0, 150); ?>.... Read More On <?php echo $websiteTitle; ?>.">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo $websiteTitle; ?>">
    <meta property="og:url" content="<?php echo $websiteUrl; ?>/anime/<?php echo $animeId; ?>">
    <meta itemprop="image" content="<?php echo $animeInfo['poster']; ?>">
    <meta property="og:image" content="<?php echo $animeInfo['poster']; ?>">
    <meta property="og:image:secure_url" content="<?php echo $animeInfo['poster']; ?>">
    <meta property="og:image:width" content="650">
    <meta property="og:image:height" content="350">
    <meta property="twitter:title" content="Watch <?php echo htmlspecialchars($animeInfo['name']); ?> - <?php echo $websiteTitle; ?>">
    <meta property="twitter:description" content="<?php echo substr(htmlspecialchars($animeInfo['description']), 0, 150); ?>.... Read More On <?php echo $websiteTitle; ?>.">
    <meta property="twitter:url" content="<?php echo $websiteUrl; ?>/anime/<?php echo $animeId; ?>">
    <meta property="twitter:card" content="summary">
    <meta name="apple-mobile-web-app-status-bar" content="#202125">
    <meta name="theme-color" content="#202125">
    <link rel="apple-touch-icon" href="<?php echo $websiteUrl; ?>/favicon.png?v=<?php echo $version; ?>" />
    <link rel="shortcut icon" href="<?php echo $websiteUrl; ?>/favicon.png?v=<?php echo $version; ?>" type="image/x-icon"/>
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $websiteUrl; ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $websiteUrl; ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $websiteUrl; ?>/favicon-16x16.png">
    <link rel="mask-icon" href="<?php echo $websiteUrl; ?>/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="icon" sizes="192x192" href="<?php echo $websiteUrl; ?>/files/images/touch-icon-192x192.png?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="<?php echo $websiteUrl; ?>/files/css/style.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="<?php echo $websiteUrl; ?>/files/css/min.css?v=<?php echo $version; ?>">
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-63430163bc99824a"></script>
    <script type="text/javascript">
        setTimeout(function () {
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
</head>

<body data-page="movie_info">
    <div id="sidebar_menu_bg"></div>
    <div id="wrapper" data-page="page_home">
        <?php include('./_php/header.php'); ?>
        <div class="clearfix"></div>
        <div id="main-wrapper" date-page="movie_info" data-id="<?php echo $animeId; ?>">
            <div id="ani_detail">
                <div class="ani_detail-stage">
                    <div class="container">
                        <div class="anis-cover-wrap">
                            <div class="anis-cover"
                                style="background-image: url('<?php echo $animeInfo['poster']; ?>')"></div>
                        </div>
                        <div class="anis-content">
                            <div class="anisc-poster">
                                <div class="film-poster">
                                    <img src="<?php echo $websiteUrl; ?>/files/images/no_poster.jpg"
                                        data-src="<?php echo $animeInfo['poster']; ?>"
                                        class="lazyload film-poster-img">
                                </div>
                            </div>
                            <div class="anisc-detail">
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
                                                itemtype="http://schema.org/ListItem"
                                                class="breadcrumb-item dynamic-name" data-jname="<?php echo htmlspecialchars($animeInfo['name']); ?>"
                                                aria-current="page">
                                                <a itemprop="item" href="/anime/<?php echo $animeId; ?>"><span itemprop="name"><?php echo htmlspecialchars($animeInfo['name']); ?></span></a>
                                                <meta itemprop="position" content="3">
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                                <h2 class="film-name dynamic-name" data-jname="<?php echo htmlspecialchars($animeInfo['name']); ?>"><?php echo htmlspecialchars($animeInfo['name']); ?></h2>
                                <div class="film-stats">
                                    <div class="tac tick-item tick-quality"><?php echo $animeInfo['stats']['quality'] ?? 'HD'; ?></div>
                                    <div class="tac tick-item tick-dub">
                                        <?php echo $animeInfo['stats']['episodes']['dub'] ? 'Dubbed' : 'Subbed'; ?>
                                    </div>
                                    <span class="dot"></span>
                                    <span class="item"><?php echo $animeInfo['stats']['type']; ?></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="film-buttons">
                                    <?php if (!empty($firstEpisodeId)): ?>
                                        <a href="/watch/<?php echo $firstEpisodeId; ?>" class="btn btn-radius btn-primary btn-play"><i
                                                class="fas fa-play mr-2"></i>Watch now</a>
                                    <?php else: ?>
                                        <a href="javascript:void(0)" class="btn btn-radius btn-primary btn-play disabled"><i
                                                class="fas fa-play mr-2"></i>Episodes not available</a>
                                    <?php endif; ?>

                                    <div class="dr-fav" id="watch-list-content">
                                        <?php if (isset($_COOKIE['userID'])) { ?>
                                            <?php 
                                            $watchLater = mysqli_query($conn, "SELECT * FROM `watch_later` WHERE (user_id,anime_id) = ('$user_id','$animeId')"); 
                                            $watchLater = mysqli_fetch_assoc($watchLater); 
                                            $anime_id = $watchLater['anime_id'] ?? null;
                                            if ($anime_id == null) { ?>
                                                <a id="addToList" class="btn btn-radius btn-light"
                                                    animeId="<?php echo $animeId; ?>">&nbsp;<i class='fas fa-plus mr-2'></i> Add to List&nbsp;</a>
                                                <script>
                                                let btn = document.querySelector('#addToList');
                                                btn.addEventListener("click", () => {
                                                    let btnValue = btn.getAttribute('animeId');
                                                    $.post('../user/ajax/watchlist.php', {
                                                        btnValue: btnValue
                                                    }, (response) => {
                                                        btn.innerHTML = response;
                                                    });
                                                });
                                                </script>
                                            <?php } elseif ($anime_id == $animeId) { ?>
                                                <a id="addToList" class="btn btn-radius btn-light"
                                                    animeId="<?php echo $animeId; ?>">&nbsp;<i class='fas fa-minus mr-2'></i> Remove From List&nbsp;</a>
                                                <script>
                                                let btn = document.querySelector('#addToList');
                                                btn.addEventListener("click", () => {
                                                    let btnValue = btn.getAttribute('animeId');
                                                    $.post('../user/ajax/watchlist.php', {
                                                        btnValue: btnValue
                                                    }, (response) => {
                                                        btn.innerHTML = response;
                                                    });
                                                });
                                                </script>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <a href="<?php echo $websiteUrl; ?>/user/login?animeId=<?php echo $animeId; ?>"
                                                class="btn btn-radius btn-light">&nbsp;<i class='fas fa-plus mr-2'></i>&nbsp;Login to Add&nbsp;</a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="film-description m-hide">
                                    <div class="text"><?php echo htmlspecialchars($animeInfo['description']); ?></div>
                                </div>
                                <div class="film-text m-hide"><?php echo $websiteTitle; ?> is a site to watch online anime like <strong><?php echo htmlspecialchars($animeInfo['name']); ?></strong> online, or you can even watch <strong><?php echo htmlspecialchars($animeInfo['name']); ?></strong> in HD quality</div>
                                <div class="share-buttons share-buttons-min mt-3">
                                    <div class="share-buttons-block" style="padding-bottom: 0 !important;">
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
                            <div class="anisc-info-wrap">
                                <div class="anisc-info">
                                    <div class="item item-title w-hide">
                                        <span class="item-head">Overview:</span>
                                        <div class="text"><?php echo htmlspecialchars($animeInfo['description']); ?></div>
                                    </div>
                                    <div class="item item-title">
                                        <span class="item-head">Other names:</span> <span class="name"><?php echo htmlspecialchars($animeMoreInfo['synonyms'] ?? $animeMoreInfo['japanese'] ?? $animeInfo['name']); ?></span>
                                    </div>
                                    <div class="item item-title">
                                        <span class="item-head">Language:</span> 
                                        <span class="name"><?php echo $animeInfo['stats']['episodes']['dub'] ? 'Dubbed' : 'Subbed'; ?></span>
                                    </div>
                                    <div class="item item-title">
                                        <span class="item-head">Episodes:</span> <span class="name"><?php echo $animeInfo['stats']['episodes']['sub'] ?? $animeInfo['stats']['episodes']['dub'] ?? 'Unknown'; ?></span>
                                    </div>
                                    <div class="item item-title">
                                        <span class="item-head">Release Year:</span> <span class="name"><?php echo explode(' to ', $animeMoreInfo['aired'] ?? '')[0] ?? 'Unknown'; ?></span>
                                    </div>
                                    <div class="item item-title">
                                        <span class="item-head">Type:</span> <span class="name"><?php echo $animeInfo['stats']['type'] ?? 'Unknown'; ?></span>
                                    </div>
                                    <div class="item item-title">
                                        <span class="item-head">Status:</span> <a href="<?php echo ($animeMoreInfo['status'] == 'Finished Airing') ? '/status/completed' : '/status/ongoing'; ?>"><?php echo $animeMoreInfo['status'] ?? 'Unknown'; ?></a>
                                    </div>
                                    <div class="item item-list">
                                        <span class="item-head">Genres:</span>
                                        <?php foreach ($animeMoreInfo['genres'] ?? [] as $genre) { ?>
                                            <a href="<?php echo $websiteUrl; ?>/genre/<?php echo strtolower(str_replace(' ', '+', $genre)); ?>"><?php echo htmlspecialchars($genre); ?></a>
                                        <?php } ?>
                                    </div>
                                    <div class="film-text w-hide"><?php echo $websiteTitle; ?> is a site to watch online anime like <strong><?php echo htmlspecialchars($animeInfo['name']); ?></strong> online, or you can even watch <strong><?php echo htmlspecialchars($animeInfo['name']); ?></strong> in HD quality</div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="container">
                <div id="main-content">
                    <?php include('./_php/recent-releases.php'); ?>
                    <div class="clearfix"></div>
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
        <script type="text/javascript" src="<?php echo $websiteUrl; ?>/files/js/app.js"></script>
        <script type="text/javascript" src="<?php echo $websiteUrl; ?>/files/js/comman.js"></script>
        <script type="text/javascript" src="<?php echo $websiteUrl; ?>/files/js/movie.js"></script>
        <link rel="stylesheet" href="<?php echo $websiteUrl; ?>/files/css/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript" src="<?php echo $websiteUrl; ?>/files/js/function.js"></script>
    </div>
</body>
</html>