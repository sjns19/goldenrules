<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>
      <section class="hero">
        <div class="bg-img bg-img-fixed" id="hero-bg-<?= rand(1, 4) ?>"></div>
        <div class="wrapper">
          <div class="hero-container">
            <div class="hero-inner">
              <p class="hero-paragraph">Helping individual traders gain<br />the correct knowledge in understanding</p>
              <div class="my-1">
                <h1 class="hero-heading txt-uppercase txt-grad-gold txt-uppercase">the art<br />of investing</h1>
              </div>
              <div class="mt-2">
                <button class="btn btn-gold btn-md txt-uppercase" id="learn-more">learn more</button>
                <a href="/user/paid-membership/checkout" class="btn btn-silver btn-md txt-uppercase">get started</a>
              </div>
            </div>
          </div>
        </div>
      </section>
      <div class="section-container">
        <section class="section py-5">
          <div class="wrapper">
            <div class="flex align-items-center -m-flex-dir-reverse py-5">
              <div class="showcase">
                <div class="showcase-img-frame overlay backdrop-left">
                  <img class="showcase-img grt-lzy" src="<?= App::resizeImage(SITE_URL . IMAGES_PATH . 'section-image-1.jpg', 4, 4) ?>" data-src="<?= IMAGES_PATH ?>section-image-1.jpg" alt="A man using a laptop on a table">
                </div>
                <div class="-m-align-center -m-visible-block mt-3">
                  <a href="/about" class="btn btn-gold btn-md" title="Read more">read more</a>
                </div>
              </div>
              <div class="showcase -m-align-center -m-px-2 mx-3">
                <h1 class="section-title txt-grad-gold">why<br />goldenrules?</h1>
                <p class="section-paragraph txt-grey mt-1">We strive to provide a quality trading experience for both our students and investors. We understand trading can be difficult at times so we provide the proper knowledge in how retail traders and banks control the markets.</p>
                <a href="/about" class="btn btn-gold btn-md mt-2 txt-uppercase -m-hidden" title="Read more">read more</a>
              </div>
            </div>
          </div>
        </section>
<?php if ($this->user_count != 0): ?>
        <section class="section py-5" id="member-count">
          <div class="wrapper py-3">
            <div class="flex align-items-stretch justify-space-around flex-no-break txt-align-center">
              <div class="card">
                <strong class="section-title txt-grad-gold">1</strong>
                <p class="section-paragraph txt-grey txt-uppercase">family</p>
              </div>
              <div class="card">
                <strong class="section-title txt-grad-gold"><?= $this->user_count ?></strong>
                <p class="section-paragraph txt-grey txt-uppercase">members</p>
              </div>
              <div class="card">
                <strong class="section-title txt-grad-gold">1</strong>
                <p class="section-paragraph txt-grey txt-uppercase">goal</p>
              </div>
            </div>
          </div>
        </section>
<?php endif; ?>
        <section class="section py-5" id="mentorship">
          <div class="section-header txt-align-center py-5">
            <h1 class="section-title mb-1 txt-grad-gold">our mentorship</h1>
            <p class="section-paragraph txt-grey">Step up your trading game to the next level with the help of GoldenRules' mentorship.</p>
            <p class="section-paragraph txt-grey">Here is everything we offer to get you started.</p>
          </div>
          <div class="wrapper">
            <div class="grid">
<?php foreach ($this->mentorship_items as $key => $val): ?>
              <div class="grid-item">
                <div class="card card-icon">
                  <div class="card-icon-frame">
                    <img class="card-icon-img grt-lzy" src="<?= App::resizeImage(SITE_URL . ICONS_PATH . $val['icon'] . '.png?v=1', 4, 4) ?>" data-src="<?= ICONS_PATH . $val['icon'] ?>.png?v=1" alt="<?= $val['title'] ?> icon">
                  </div>
                  <div class="card-body">
                    <h4 class="txt-white"><?= $val['title'] ?></h4>
                    <p class="txt-grey txt-sm mt-1"><?= $val['paragraph'] ?></p>
                  </div>
                </div>
              </div>
<?php endforeach; ?>
            </div>
            <div class="txt-align-center my-3">
              <p class="section-paragraph txt-grey">Are you interested? Become a paid member to start your professional trading journey today!</p>
              <a href="/user/paid-membership/checkout" class="link-gold link-no-underline txt-sm txt-uppercase" title="Become a paid member">get started</a>
            </div>
          </div>
        </section>
        <section class="section py-5">
          <div class="wrapper">
            <div class="flex align-items-center">
              <div class="showcase -m-align-center -m-px-2 mx-3">
                <h1 class="section-title txt-grad-gold">876illionaire</h1>
                <p class="section-paragraph txt-grey mt-1">A brand established by young Jamaicans that represents a positive and productive Caribbean lifestyle. One of wealth in every aspect of life: Mentally, Physically, Spiritually, Emotionally and Financially.
                  <p class="section-paragraph txt-grey collapsed" id="876illionaire-paragraph">
                    <br>It is a movement of progressive individuals who are not victims of circumstances but conquerors of adversity and creators of their own reality. A symbol of success and an Abundance mindset towards life. Just like a common saying in Jamaica, “We likkle but we Tallawah”. 876illionaire’s aspires to cultivate and inspire the strength and resilience embedded in our Jamaican culture towards manifesting each individuals ideal quality of life. Our island might be small but we’re winners against all odds because we truly can become anything we set our minds too especially within a community of like-minds. We welcome you to be apart of this movement.
                  </p>
                </p>
                <a href="javascript:" class="link-gold link-no-underline txt-uppercase txt-sm toggle-collapse" data-collapse="#876illionaire-paragraph" title="Show more">show more</a>
              </div>
              <div class="showcase">
                <div class="showcase-img-frame overlay backdrop-right">
                  <img class="showcase-img grt-lzy" src="<?= App::resizeImage(SITE_URL . IMAGES_PATH . '876millionaire-bg.jpg', 4, 4) ?>" data-src="<?= IMAGES_PATH ?>876millionaire-bg.jpg" alt="A man holding 876 millionaire logo banner">
                </div>
              </div>
            </div>
          </div>
        </section>
        <section class="section py-5">
          <div class="wrapper">
            <div class="flex align-items-center -m-flex-dir-reverse">
              <div class="showcase">
                <div class="showcase-icon-frame">
                  <img class="showcase-icon grt-lzy" src="<?= App::resizeImage(SITE_URL . ICONS_PATH . 'brain.png', 4, 4) ?>" data-src="<?= ICONS_PATH ?>brain.png" alt="A brain icon in a circular gold plate">
                </div>
                <div class="-m-align-center -m-visible-block mt-4">
                  <a href="/golden-strategy" class="btn btn-gold btn-md txt-uppercase" title="Learn more about GoldenStrategy">learn more</a>
                </div>
              </div>
              <div class="showcase -m-align-center -m-px-2">
                <h1 class="section-title txt-grad-gold">goldenstrategy</h1>
                <p class="section-paragraph txt-grey mt-1">The GoldenStrategy provides the information necessary in becoming a profitable trader while allowing you to choose your personal mentor to help you alongside your trading journey.</p>
                <a href="/golden-strategy" class="btn btn-gold btn-md txt-uppercase -m-hidden mt-2" title="Learn more about GoldenStrategy">learn more</a>
              </div>
            </div>
          </div>
        </section>
<?php if ($this->news): ?>
        <section class="section py-5">
          <div class="section-header txt-align-center">
            <h1 class="section-title mb-1 txt-grad-gold">news</h1>
            <p class="section-paragraph txt-grey">Stay up to date with the latest news on Forex and Stock market, provided by <?= SITE_NAME ?>.</p>
          </div>
          <div class="wrapper">
            <div class="grid -m-grid-col">
      <?php foreach ($this->news as $key => $val): ?>
              <div class="grid-item">
                <a href="<?= $val['url'] ?>" class="card card-grey card-link card-animated card-animated-gold" title="<?= $val['title'] ?>">
                  <div class="card-body p-1">
                    <p class="txt-light-grey txt-break txt-bold"><?= $val['title'] ?></p>
                    <div class="flex align-items-center flex-no-break mt-1">
                      <div class="avatar avatar-sm avatar-bordered">
                  <?php if (!is_null($val['author_avatar_url'])): ?>
                        <img 
                          class="avatar-img grt-lzy" 
                          src="<?= App::resizeImage($val['author_avatar_url'], 4, 4) ?>" 
                          data-src="<?= $val['author_avatar_url'] ?>" 
                          alt="<?= $author ?>'s avatar">
                  <?php else: ?>
                        <div class="avatar-no-img" style="background-color:<?= $val['author_avatar_color'] ?>;"><?= $val['author_first_letter'] ?></div>
                  <?php endif; ?>
                      </div>
                      <small class="txt-grey txt-xs txt-uppercase pl-1"><?= $val['posted_date'] ?></small>
                    </div>
                  </div>
                </a>
              </div>
      <?php endforeach; ?>
            </div>
            <div class="txt-align-center my-2">
              <a href="/news" class="btn btn-gold btn-md" title="See more news">see more</a>
            </div>
          </div>
        </section>
<?php endif; ?>
<?php if ($this->quote && count($this->quote)): ?>
        <section class="section py-5">
          <div class="wrapper">
            <div class="quote">
              <h3 class="quote-text txt-grey"><?= $this->quote['text'] ?></h3>
              <p class="quote-author">~ <?= $this->quote['author'] ?></p>
            </div>
          </div>
        </section>
<?php endif; ?>
      </div>