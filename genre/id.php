<?php 
session_start();
require('../_config.php');
$parts = parse_url($_SERVER['REQUEST_URI']); 
$page_url = explode('/', $parts['path']);
$genre = $page_url[count($page_url)-1];
$id = ucfirst(str_replace("+", " ", $genre));

if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page']; 
}
?>

<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title><?php echo htmlspecialchars($id); ?> on <?php echo $websiteTitle; ?></title>
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="title" content="<?php echo htmlspecialchars($id); ?> on <?php echo $websiteTitle; ?>">
    <meta name="description" content="Popular Anime in HD with No Ads. Watch anime online">
    <meta name="keywords" content="<?php echo $websiteTitle; ?>, watch anime online, free anime, anime stream, anime hd, english sub, kissanime, gogoanime, animeultima, 9anime, 123animes, <?php echo $websiteTitle; ?>, vidstreaming, gogo-stream, animekisa, zoro.to, gogoanime.run, animefrenzy, animekisa">
    <meta name="charset" content="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Language" content="en">
    <meta property="og:title" content="<?php echo htmlspecialchars($id); ?> on <?php echo $websiteTitle; ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($id); ?> on <?php echo $websiteTitle; ?> in HD with No Ads. Watch anime online">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo $websiteTitle; ?>">
    <meta itemprop="image" content="<?php echo $banner; ?>">
    <meta property="og:image" content="<?php echo $banner; ?>">
    <meta property="og:image:width" content="650">
    <meta property="og:image:height" content="350">
    <meta property="twitter:card" content="summary">
    <meta name="apple-mobile-web-app-status-bar" content="#202125">
    <meta name="theme-color" content="#202125">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" type="text/css">
    <link rel="apple-touch-icon" href="<?php echo $websiteUrl; ?>/favicon.png?v=<?php echo $version; ?>" />
    <link rel="shortcut icon" href="<?php echo $websiteUrl; ?>/favicon.png?v=<?php echo $version; ?>" type="image/x-icon"/>
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $websiteUrl; ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $websiteUrl; ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $websiteUrl; ?>/favicon-16x16.png">
    <link rel="mask-icon" href="<?php echo $websiteUrl; ?>/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="icon" sizes="192x192" href="<?php echo $websiteUrl; ?>/files/images/touch-icon-192x192.png?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="<?php echo $websiteUrl; ?>/files/css/style.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="<?php echo $websiteUrl; ?>/files/css/min.css?v=<?php echo $version; ?>">
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
    <script></script>
</head>

<body data-page="page_anime">
    <div id="sidebar_menu_bg"></div>
    <div id="wrapper" data-page="page_home">
        <?php include('../_php/header.php'); ?>
        <div class="clearfix"></div>
        <div id="main-wrapper">
            <div class="container">
                <div id="main-content">
                    <section class="block_area block_area_category">
                        <div class="block_area-header">
                            <div class="float-left bah-heading mr-4">
                                <h2 class="cat-heading">Genre: <?php echo htmlspecialchars($id); ?></h2>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="tab-content">
                            <div class="block_area-content block_area-list film_list film_list-grid film_list-wfeature">
                                <div class="film_list-wrap">
                                <?php 
                                $apiUrl = "https://aniwatch-api1-two.vercel.app/api/v2/hianime/genre/{$genre}?page={$page}";
                                $json = file_get_contents($apiUrl);
                                $json = json_decode($json, true);
                                
                                if ($json['status'] === 200 && isset($json['data']['animes'])) {
                                    foreach($json['data']['animes'] as $key => $anime) { ?>
                                        <div class="flw-item">
                                            <div class="film-poster">
                                                <div class="tick ltr">
                                                    <div class="tick-item-<?php echo $anime['episodes']['dub'] ? 'dub' : 'sub'; ?> tick-eps amp-algn">
                                                        <?php echo $anime['episodes']['dub'] ? 'Dub' : 'Sub'; ?>
                                                    </div>
                                                </div>
                                                <div class="tick rtl">
                                                </div>
                                                <img class="film-poster-img lazyload"
                                                    data-src="<?php echo $anime['poster']; ?>"
                                                    src="<?php echo $websiteUrl; ?>/files/images/no_poster.jpg"
                                                    alt="<?php echo htmlspecialchars($anime['name']); ?>">
                                                <a class="film-poster-ahref"
                                                    href="/anime/<?php echo $anime['id']; ?>"
                                                    title="<?php echo htmlspecialchars($anime['name']); ?>"
                                                    data-jname="<?php echo htmlspecialchars($anime['jname']); ?>"><i class="fas fa-play"></i></a>
                                            </div>
                                            <div class="film-detail">
                                                <h3 class="film-name">
                                                    <a href="/anime/<?php echo $anime['id']; ?>"
                                                       title="<?php echo htmlspecialchars($anime['name']); ?>"
                                                       data-jname="<?php echo htmlspecialchars($anime['jname']); ?>">
                                                       <?php echo htmlspecialchars($anime['name']); ?>
                                                    </a>
                                                </h3>
                                                <div class="description"></div>
                                                <div class="fd-infor">
                                                    <span class="fdi-item"><?php echo $anime['type']; ?></span>
                                                    <span class="dot"></span>
                                                    <span class="fdi-item"><?php echo $anime['duration']; ?></span>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    <?php } 
                                } else {
                                    echo '<p>No anime found for this genre.</p>';
                                } ?>
                                </div>
                                <div class="clearfix"></div>
                                <style>
                                    .cus_pagi {
                                        margin-top: 7px;
                                        display: flex;
                                        justify-content: center;
                                        gap: 10px;
                                    }
                                    .cus_pagi a.btn {
                                        padding: 7px 15px;
                                        height: 34px;
                                        border-radius: 4px;
                                        font-size: 14px;
                                        line-height: 20px;
                                        text-transform: uppercase;
                                        font-weight: 500;
                                    }
                                    .btn.btn-primary {
                                        background-color: #007bff;
                                        border-color: #007bff;
                                    }
                                    .btn.btn-primary:hover {
                                        background-color: #0056b3;
                                        border-color: #004085;
                                    }
                                    .btn.btn-primary.disabled {
                                        background-color: #6c757d;
                                        border-color: #6c757d;
                                        cursor: not-allowed;
                                    }
                                </style>
                                <div class="pagination">
                                    <nav>
                                        <ul class="ulclear cus_pagi">
                                            <?php
                                            if ($json['status'] === 200) {
                                                $currentPage = $json['data']['currentPage'];
                                                $hasNextPage = $json['data']['hasNextPage'];
                                                
                                                // Previous page link
                                                echo '<li><a href="?page='.($currentPage-1).'" class="btn btn-primary'.($currentPage <= 1 ? ' disabled' : '').'">Previous</a></li>';
                                                
                                                // Next page link
                                                echo '<li><a href="?page='.($currentPage+1).'" class="btn btn-primary'.(!$hasNextPage ? ' disabled' : '').'">Next</a></li>';
                                            }
                                            ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="clearfix"></div>
                </div>
                <?php include('../_php/sidenav.php'); ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php include('../_php/footer.php'); ?>
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
        <div style="display:none;"></div>
    </div>
</body>
</html>