<?php
require __DIR__ . '/layout.php';
pageHead('team', 'Мастера «Мускари», их специализация, опыт и запись на визит.');
?>
  <section id="team" class="page-lead page-lead-team shell" aria-labelledby="team-title">
    <div class="page-lead-copy">
      <p class="eyebrow"><span></span> 03 / ЛЮДИ МУСКАРИ</p>
      <h1 id="team-title">Наши <em>мастера</em></h1>
      <p>В команде «Мускари» работают косметологи, стилисты, специалисты по ногтевому сервису и визажисты. В профилях можно посмотреть опыт и узнать, с какими услугами работает каждый.</p>
      <div class="lead-actions"><a class="button" href="#specialists">Выбрать специалиста ↓</a><a class="button outline" href="services.php">Посмотреть услуги ↗</a></div>
    </div>
    <figure><img src="assets/interior.webp" alt="Команда «Мускари» у салона" fetchpriority="high"><figcaption>МУСКАРИ / КОМАНДА</figcaption></figure>
  </section>
  <section id="specialists" class="section shell" aria-labelledby="specialists-title">
    <div class="section-head"><div><p class="eyebrow"><span></span> ПРОФИЛИ МАСТЕРОВ</p><h2 id="specialists-title">Найдите <em>своего мастера</em></h2></div><p>Выберите направление или откройте профиль мастера, чтобы перейти к записи.</p></div>
    <div class="pills team-filters" id="team-filters" aria-label="Специализации мастеров"></div><div class="team-grid" id="team-grid"></div>
  </section>
  <section class="section shell" aria-labelledby="team-faq">
    <div class="guide-layout">
      <div><p class="eyebrow"><span></span> ВЫБОР СПЕЦИАЛИСТА</p><h2 id="team-faq">На что <em>ориентироваться</em></h2></div>
      <div class="faq-list">
        <details><summary>Где узнать специализацию и опыт мастера?</summary><p>Опыт указан в карточке. Нажмите «О мастере», чтобы прочитать описание и открыть форму записи к этому специалисту.</p></details>
        <details><summary>Можно начать с выбора услуги?</summary><p>Да. После выбора услуги в форме появятся мастера соответствующего направления.</p></details>
      </div>
    </div>
  </section>
  <section class="section shell" aria-labelledby="team-visit">
    <div class="visit-banner"><div><p class="eyebrow"><span></span> ВИЗИТ В МУСКАРИ</p><h2 id="team-visit">Готовы <em>познакомиться?</em></h2><p>Выберите мастера в форме или укажите только услугу.</p></div><div class="visit-actions"><button class="button light" type="button" data-book>Записаться на визит ↗</button><a href="services.php">Сравнить услуги ↗</a></div></div>
  </section>
<?php pageEnd(); ?>
