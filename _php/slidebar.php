<div class="swiper-wrapper">
    <?php 
        $json = file_get_contents("$api/api/v2/hianime/home");
        $json = json_decode($json, true);
        $spotlightAnimes = $json['data']['spotlightAnimes'];
        
        foreach($spotlightAnimes as $anime) { ?>
    <div class="swiper-slide">
        <div class="deslide-item">
            <div class="deslide-cover">
                <div class="deslide-cover-img">
                    <img class="film-poster-img lazyload" data-src="<?= htmlspecialchars($anime['poster']) ?>"
                        alt="<?= htmlspecialchars($anime['name']) ?>">
                </div>
            </div>
            <div class="deslide-item-content">
                <div class="desi-sub-text">#<?= $anime['rank'] ?> Spotlight</div>
                <div class="desi-head-title dynamic-name" data-jname="<?= htmlspecialchars($anime['jname']) ?>">
                    <?= htmlspecialchars($anime['name']) ?>
                </div>
                <div class="sc-detail">
                    <div class="scd-item">
                        <i class="fas fa-play-circle mr-1"></i> <?= htmlspecialchars($anime['type']) ?>
                    </div>
                    <div class="scd-item m-hide">
                        <i class="fas fa-calendar mr-1"></i> <?= htmlspecialchars($anime['otherInfo'][2]) ?>
                    </div>
                    <div class="scd-item mr-1"><span class="quality">HD</span></div>
                    <div class="scd-item">
                        <?php if ($anime['episodes']['sub'] !== null) { ?>
                            <span class="quality bg-white ">SUB</span>
                        <?php } ?>
                        <?php if ($anime['episodes']['dub'] !== null) { ?>
                            <span class="quality bg-white ">DUB</span>
                        <?php } ?>
                    </div>
                    <div class="desi-description"><?= htmlspecialchars($anime['description']) ?></div>
                </div>
                <div class="desi-buttons">
                    <a href="<?php echo $websiteUrl; ?>/anime/<?= htmlspecialchars($anime['id']) ?>" class="btn btn-primary btn-radius mr-2">
                        <i class="fas fa-play-circle mr-2"></i>Watch Now</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php } ?>
</div>