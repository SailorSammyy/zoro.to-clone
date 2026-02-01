<?php
require('./_config.php');
session_start();

 $parts = parse_url($_SERVER['REQUEST_URI']);
 $page_url = explode('/', $parts['path']);
 $url = $page_url[count($page_url) - 1];

parse_str($parts['query'] ?? '', $query);
 $episodeNum = $query['ep'] ?? '';
 $animeId = $url;

 $getAnime = file_get_contents("$api/api/v2/hianime/anime/{$animeId}");
 $getAnime = json_decode($getAnime, true);

if (!isset($getAnime['data']['anime']['info'])) {
    header('Location: home.php');
    exit;
}

 $animeInfo = $getAnime['data']['anime']['info'];
 $animeMoreInfo = $getAnime['data']['anime']['moreInfo'];

 $getEpisodes = file_get_contents("$api/api/v2/hianime/anime/{$animeId}/episodes");
 $getEpisodes = json_decode($getEpisodes, true);
 $episodesList = $getEpisodes['data']['episodes'] ?? [];

 $currentEpisodeId = '';
 $episodeTitle = '';
 $episodeNumber = '';
foreach ($episodesList as $episode) {
    $episodeIdParts = explode('?ep=', $episode['episodeId']);
    $episodeNumFromId = $episodeIdParts[1] ?? '';
    
    if ($episodeNumFromId == $episodeNum) {
        $currentEpisodeId = $episode['episodeId'];
        $episodeTitle = $episode['title'];
        $episodeNumber = $episode['number'];
        break;
    }
}

if (empty($currentEpisodeId) && !empty($episodesList)) {
    $currentEpisodeId = $episodesList[0]['episodeId'];
    $episodeNum = $episodesList[0]['number'];
    $episodeTitle = $episodesList[0]['title'];
    $episodeNumber = $episodesList[0]['number'];
}

 $episodeIdParts = explode('?ep=', $currentEpisodeId);
 $episodeId = $episodeIdParts[1] ?? '';

 $language = 'sub';

if (isset($_GET['lang']) && $_GET['lang'] === 'dub') {
    $language = 'dub';
}

 $embedUrl = "https://megaplay.buzz/stream/s-2/{$episodeId}/{$language}";
?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Watch <?= $animeInfo['name'] ?> Episode <?= $episodeNum ?> - <?= $websiteTitle ?></title>
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="title" content="Watch <?= $animeInfo['name'] ?> Episode <?= $episodeNum ?> - <?= $websiteTitle ?>">
    <meta name="description" content="<?= substr($animeInfo['description'], 0, 150) ?> ... at <?= $websiteUrl ?>">
    <meta name="keywords" content="<?= $websiteTitle ?>, <?= $animeInfo['name'] ?> Episode <?= $episodeNum ?>, <?= $animeInfo['name'] ?>, watch anime online, free anime, anime stream, anime hd, english sub">
    <meta name="charset" content="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="robots" content="index, follow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Language" content="en">
    <meta property="og:title" content="Watch <?= $animeInfo['name'] ?> Episode <?= $episodeNum ?> - <?= $websiteTitle ?>">
    <meta property="og:description" content="<?= substr($animeInfo['description'], 0, 150) ?> ... at <?= $websiteUrl ?>">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= $websiteTitle ?>">
    <meta property="og:url" content="<?= $websiteUrl ?>/watch/<?= $url ?>">
    <meta itemprop="image" content="<?= $animeInfo['poster'] ?>">
    <meta property="og:image" content="<?= $animeInfo['poster'] ?>">
    <meta property="twitter:title" content="Watch <?= $animeInfo['name'] ?> Episode <?= $episodeNum ?> - <?= $websiteTitle ?>">
    <meta property="twitter:description" content="<?= substr($animeInfo['description'], 0, 150) ?> ... at <?= $websiteUrl ?>">
    <meta property="twitter:url" content="<?= $websiteUrl ?>/watch/<?= $url ?>">
    <meta property="twitter:card" content="summary">
    <meta name="apple-mobile-web-app-status-bar" content="#202125">
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-63430163bc99824a"></script>
    <meta name="theme-color" content="#202125">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" type="text/css">
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
                            <div class="anis-cover" style="background-image: url('<?= $websiteUrl ?>/files/images/banner.webp')"></div>
                        </div>
                        <div class="anis-watch-wrap">
                            <div class="prebreadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" class="breadcrumb-item">
                                            <a itemprop="item" href="/"><span itemprop="name">Home</span></a>
                                            <meta itemprop="position" content="1">
                                        </li>
                                        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" class="breadcrumb-item">
                                            <a itemprop="item" href="/anime"><span itemprop="name">Anime</span></a>
                                            <meta itemprop="position" content="2">
                                        </li>
                                        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" class="breadcrumb-item" aria-current="page">
                                            <a itemprop="item" href="/anime/<?= $animeId ?>"><span itemprop="name"><?= $animeInfo['name'] ?></span></a>
                                            <meta itemprop="position" content="3">
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
                                        <!-- Direct embed from megaplay.buzz -->
                                        <iframe id="player-iframe" src="<?= $embedUrl ?>" frameborder="0" scrolling="no" allow="accelerometer;autoplay;encrypted-media;gyroscope;picture-in-picture" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" width="100%" height="100%"></iframe>
                                    </div>
                                    <div class="player-controls">
                                        <div class="pc-item pc-resize">
                                            <a href="javascript:;" id="media-resize" class="btn btn-sm"><i class="fas fa-expand mr-1"></i>Expand</a>
                                        </div>
                                        <div class="pc-item pc-toggle pc-light">
                                            <div id="turn-off-light" class="toggle-basic">
                                                <span class="tb-name"><i class="fas fa-lightbulb mr-2"></i>Light</span>
                                                <span class="tb-result"></span>
                                            </div>
                                        </div>
                                        <div class="pc-right">
                                            <?php 
                                            $prevEpisode = null;
                                            $nextEpisode = null;
                                            
                                            foreach ($episodesList as $index => $episode) {
                                                if ($episode['episodeId'] === $currentEpisodeId) {
                                                    if ($index > 0) {
                                                        $prevEpisode = $episodesList[$index - 1];
                                                    }
                                                    if ($index < count($episodesList) - 1) {
                                                        $nextEpisode = $episodesList[$index + 1];
                                                    }
                                                    break;
                                                }
                                            }
                                            
                                            if ($prevEpisode) { ?>
                                                <div class="pc-item pc-control block-prev">
                                                    <a class="btn btn-sm btn-prev" href="/watch/<?= $prevEpisode['episodeId'] ?>"><i class="fas fa-backward mr-2"></i>Prev</a>
                                                </div>&nbsp;
                                            <?php } ?>
                                            
                                            <?php if ($nextEpisode) { ?>
                                                <div class="pc-item pc-control block-next">
                                                    <a class="btn btn-sm btn-next" href="/watch/<?= $nextEpisode['episodeId'] ?>"><i class="fas fa-forward ml-2"></i>Next</a>
                                                </div>
                                            <?php } ?>
                                            
                                            <div class="pc-item pc-fav" id="watch-list-content"></div>
                                            <div class="pc-item pc-download" style="display:none;">
                                                <a class="btn btn-sm pc-download"><i class="fas fa-download mr-2"></i>Download</a>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="player-servers">
                                    <div id="servers-content">
                                        <div class="ps_-status">
                                            <div class="content">
                                                <div class="server-notice"><strong>Currently watching <b>Episode <?= $episodeNumber ?></b></strong> Switch to alternate servers in case of error.</div>
                                            </div>
                                        </div>
                                        <div class="ps_-block ps_-block-sub servers-mixed">
                                            <div class="ps__-title"><i class="fas fa-server mr-2"></i>SERVERS:</div>
                                            <div class="ps__-list">
                                                <div class="item">
                                                    <a id="sub-server" href="javascript:void(0)" class="btn btn-server <?= $language === 'sub' ? 'active' : '' ?>" data-lang="sub">SUB</a>
                                                </div>
                                                <div class="item">
                                                    <a id="dub-server" href="javascript:void(0)" class="btn btn-server <?= $language === 'dub' ? 'active' : '' ?>" data-lang="dub">DUB</a>
                                                </div>
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
                                                <div id="episodes-page-1" class="ss-list ss-list-min" data-page="1" style="display:block;">
                                                    <?php foreach ($episodesList as $episode): ?>
                                                        <a title="Episode <?= $episode['number'] ?>: <?= $episode['title'] ?>" class="ssl-item ep-item <?= $episode['episodeId'] === $currentEpisodeId ? 'active' : '' ?>" href="/watch/<?= $episode['episodeId'] ?>">
                                                            <div class="ssli-order" title=""><?= $episode['number'] ?></div>
                                                            <div class="ssli-detail">
                                                                <div class="ep-name dynamic-name" data-jname="" title=""><?= $episode['title'] ?></div>
                                                            </div>
                                                            <div class="ssli-btn">
                                                                <div class="btn btn-circle"><i class="fas fa-play"></i></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </a>
                                                    <?php endforeach; ?>
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
                                            <img src="<?= $animeInfo['poster'] ?>" data-src="<?= $animeInfo['poster'] ?>" class="film-poster-img ls-is-cached lazyloaded" alt="<?= $animeInfo['name'] ?>">
                                        </div>
                                    </div>
                                    <div class="anisc-detail">
                                        <h2 class="film-name">
                                            <a href="/anime/<?= $animeId ?>" class="text-white dynamic-name" title="<?= $animeInfo['name'] ?>" data-jname="<?= $animeInfo['name'] ?>" style="opacity: 1;"><?= $animeInfo['name'] ?></a>
                                        </h2>
                                        <div class="film-stats">
                                            <div class="tac tick-item tick-quality">HD</div>
                                            <div class="tac tick-item tick-dub"><?= strtoupper($language) ?></div>
                                            <span class="dot"></span>
                                            <span class="item"><?= $animeMoreInfo['status'] ?? 'Unknown' ?></span>
                                            <span class="dot"></span>
                                            <span class="item"><?= explode(' to ', $animeMoreInfo['aired'] ?? '')[0] ?? 'Unknown' ?></span>
                                            <span class="dot"></span>
                                            <span class="item"><?= $animeInfo['stats']['type'] ?? 'Unknown' ?></span>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="film-description m-hide">
                                            <div class="text" id="anime-description">
                                                <span id="description-short"><?= substr($animeInfo['description'], 0, 200) ?><?= strlen($animeInfo['description']) > 200 ? '...' : '' ?></span>
                                                <span id="description-full" style="display: none;"><?= $animeInfo['description'] ?></span>
                                                <?php if (strlen($animeInfo['description']) > 200): ?>
                                                    <button id="read-more-btn" class="btn btn-link p-0 ml-2" style="color: #007bff; font-size: 14px;">Read More</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="film-text m-hide mb-3">
                                            <?= $websiteTitle ?> is a site to watch online anime like <strong><?= $animeInfo['name'] ?></strong> online, or you can even watch <strong><?= $animeInfo['name'] ?></strong> in HD quality
                                        </div>
                                        <div class="block"><a href="/anime/<?= $animeId ?>" class="btn btn-xs btn-light"><i class="fas fa-book-open mr-2"></i> View detail</a></div>
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
                            <div class="float-left bah-setting"></div>
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
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js?v=<?=$version?>"></script>
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js?v=<?=$version?>"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/js-cookies@rc/dist/js.cookie.min.js"></script>
        <script type="text/javascript" src="<?=$websiteUrl?>/files/js/app.js?v=<?=$version?>"></script>
        <script type="text/javascript" src="<?=$websiteUrl?>/files/js/comman.js?v=<?=$version?>"></script>
        <script type="text/javascript" src="<?=$websiteUrl?>/files/js/movie.js?v=<?=$version?>"></script>
        <link rel="stylesheet" href="<?=$websiteUrl?>/files/css/jquery-ui.css?v=<?=$version?>">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js?v=<?=$version?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $(".btn-server").click(function(e) {
                    e.preventDefault();
                    
                    var selectedLang = $(this).data('lang');
                    var currentUrl = window.location.href;
                    var url = new URL(currentUrl);
                    
                    url.searchParams.set('lang', selectedLang);
                    
                    $('.btn-server').removeClass('active');
                    $(this).addClass('active');
                    
                    $('.tick-dub').text(selectedLang.toUpperCase());
                    
                    var iframe = document.getElementById('player-iframe');
                    var currentSrc = iframe.src;
                    var newSrc = currentSrc.replace(/\/sub|\/dub/, '/' + selectedLang);
                    
                    $('#embed-loading').show();
                    
                    iframe.src = newSrc;
                    
                    history.pushState({}, '', url.toString());
                    
                    iframe.onload = function() {
                        $('#embed-loading').hide();
                    };
                });
                
                $('#read-more-btn').click(function() {
                    var shortDesc = $('#description-short');
                    var fullDesc = $('#description-full');
                    var btn = $(this);
                    
                    if (fullDesc.is(':visible')) {
                        fullDesc.hide();
                        shortDesc.show();
                        btn.text('Read More');
                    } else {
                        shortDesc.hide();
                        fullDesc.show();
                        btn.text('Read Less');
                    }
                });
            });
        </script>
    </div>
</body>
</html>