<section class="block_area block_area_category">
    <div class="block_area-header">
        <div class="float-left bah-heading mr-4">
            <h2 class="cat-heading">Recent Releases</h2>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="tab-content">
        <div class="block_area-content block_area-list film_list film_list-grid film_list-wfeature">
            <div class="film_list-wrap">
                <?php 
                $json = file_get_contents("$api/api/v2/hianime/home");
                $json = json_decode($json, true);
                $latestEpisodeAnimes = $json['data']['latestEpisodeAnimes'];
                
                foreach($latestEpisodeAnimes as $anime) { ?>
                    <div class="flw-item">
                        <div class="film-poster">
                            <div class="tick ltr">
                                <div class="tick-item-sub tick-eps amp-algn">
                                    <?php echo ($anime['episodes']['sub'] !== null) ? 'SUB' : ''; ?>
                                    <?php echo ($anime['episodes']['dub'] !== null) ? 'DUB' : ''; ?>
                                </div>
                            </div>
                            <div class="tick rtl">
                                <div class="tick-item tick-eps amp-algn">
                                    Episode <?php echo $anime['episodes']['sub'] ?? $anime['episodes']['dub'] ?? 1; ?>
                                </div>
                            </div>
                            <img class="film-poster-img lazyload"
                                data-src="<?php echo htmlspecialchars($anime['poster']); ?>"
                                src="<?php echo htmlspecialchars($websiteUrl); ?>/files/images/no_poster.jpg"
                                alt="<?php echo htmlspecialchars($anime['name']); ?>">
                            <a class="film-poster-ahref"
                                href="/anime/<?php echo htmlspecialchars($anime['id']); ?>"
                                title="<?php echo htmlspecialchars($anime['name']); ?>"
                                data-jname="<?php echo htmlspecialchars($anime['jname']); ?>"><i class="fas fa-play"></i></a>
                        </div>
                        <div class="film-detail">
                            <h3 class="film-name">
                                <a href="/anime/<?php echo htmlspecialchars($anime['id']); ?>"
                                    title="<?php echo htmlspecialchars($anime['name']); ?>"
                                    data-jname="<?php echo htmlspecialchars($anime['jname']); ?>">
                                    <?php echo htmlspecialchars($anime['name']); ?>
                                </a>
                            </h3>
                            <div class="fd-infor">
                                <span class="fdi-item">
                                    <?php echo ($anime['episodes']['sub'] !== null) ? 'SUB' : ''; ?>
                                    <?php echo ($anime['episodes']['dub'] !== null) ? 'DUB' : ''; ?>
                                </span>
                                <span class="dot"></span>
                                <span class="fdi-item">Latest</span>
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