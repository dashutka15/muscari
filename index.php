<?php
require __DIR__ . '/layout.php';
pageHead('home', 'Услуги, мастера и запись в пространство красоты «Мускари» на Красной Пресне.');
?>
  <section class="hero shell" aria-labelledby="hero-title">
    <div class="hero-panel">
      <div class="hero-copy">
        <p class="eyebrow"><span></span> Пространство красоты · Москва</p>
        <h1 id="hero-title">Искусство<br>быть <em>собой<span>.</span></em></h1>
        <p class="hero-description">«Мускари» — салон красоты и косметологии в ЖК Lucky. Здесь можно выбрать уход за лицом, обновить стрижку, сделать маникюр или собраться на особенный вечер.</p>
        <div class="hero-actions"><button class="button light" type="button" data-book>Записаться на визит <span aria-hidden="true">↗</span></button><a class="hero-link" href="services.php">Смотреть услуги <span aria-hidden="true">↗</span></a></div>
      </div>
      <div class="hero-index" aria-hidden="true">КРАСНАЯ ПРЕСНЯ <i></i> ЖК LUCKY</div>
    </div>
    <div class="hero-visual"><img src="assets/hero.webp" srcset="assets/hero-small.webp 800w, assets/hero.webp 1550w" sizes="(max-width: 680px) 100vw, 53vw" fetchpriority="high" alt="Портрет с естественным макияжем"><span class="image-note">MUSCARI <i></i> MOSCOW</span></div>
  </section>
  <aside class="quick shell" aria-label="Информация о салоне">
    <a href="contacts.php"><span class="quick-icon" data-icon="pin"></span><span><strong>ЖК Lucky</strong><small>Москва, ул. Костикова, 4, корп. 6</small></span><span class="quick-arrow" aria-hidden="true">↗</span></a>
    <div><span class="quick-icon" data-icon="clock"></span><span><strong>10:00–22:00</strong><small>Ежедневно</small></span></div>
    <a href="reviews.php"><span class="quick-icon" data-icon="star"></span><span><strong>Впечатления гостей</strong><small>Отзывы о салоне</small></span><span class="quick-arrow" aria-hidden="true">↗</span></a>
  </aside>
  <section class="section shell" aria-labelledby="about-title">
    <div class="editorial-split">
      <figure><img src="assets/face.webp" loading="lazy" decoding="async" alt="Уход за кожей лица массажным роллером"><figcaption>01 / ЗАБОТА О СЕБЕ</figcaption></figure>
      <div class="editorial-copy">
        <p class="eyebrow"><span></span> ЗНАКОМСТВО С МУСКАРИ</p>
        <h2 id="about-title">Место, где можно <em>выдохнуть</em></h2>
        <p>На Красной Пресне «Мускари» объединяет косметологию и салонный уход. Можно прийти с конкретной задачей или начать со знакомства со специалистом и вместе подобрать процедуру.</p>
        <p>Если вы у нас впервые, посмотрите направления и профили мастеров. В каталоге можно сравнить длительность услуг и выбрать удобное время для заявки.</p>
        <a class="button outline" href="team.php">Познакомиться с мастерами <span aria-hidden="true">↗</span></a>
      </div>
    </div>
  </section>
  <section id="services" class="section shell" aria-labelledby="explore-title">
    <div class="section-head"><div><p class="eyebrow"><span></span> ВЫБРАТЬ УХОД</p><h2 id="explore-title">Четыре <em>направления</em></h2></div><a class="button outline" href="services.php">Весь каталог <span aria-hidden="true">↗</span></a></div>
    <div class="direction-grid">
      <a class="direction" href="services.php?category=Косметология#catalog"><img src="assets/device.webp" alt="" loading="lazy" decoding="async"><div><h3>Косметология</h3><p>Уход за лицом и аппаратные процедуры</p><span aria-hidden="true">↗</span></div></a>
      <a class="direction" href="services.php?category=Волосы#catalog"><img src="assets/hair.webp" alt="" loading="lazy" decoding="async"><div><h3>Волосы</h3><p>Стрижки, укладки и окрашивание</p><span aria-hidden="true">↗</span></div></a>
      <a class="direction" href="services.php?category=Ногти#catalog"><img src="assets/nails.webp" alt="" loading="lazy" decoding="async"><div><h3>Ногти</h3><p>Маникюр, педикюр и покрытие</p><span aria-hidden="true">↗</span></div></a>
      <a class="direction" href="services.php?category=Брови%20и%20макияж#catalog"><img src="assets/makeup.webp" alt="" loading="lazy" decoding="async"><div><h3>Брови и макияж</h3><p>Форма, цвет и образ для особого случая</p><span aria-hidden="true">↗</span></div></a>
    </div>
  </section>
  <section class="section shell" aria-label="Баннеры Мускари">
    <div class="feature-grid">
      <article class="feature feature-offers" id="offers">
        <p class="eyebrow"><span></span> ДЛЯ НОВЫХ ГОСТЕЙ</p>
        <h2>Первый визит <em>в Мускари</em></h2>
        <p>Посмотрите, какие предложения подготовлены для знакомства с салоном, и сравните условия.</p>
        <a class="button outline" href="offers.php">Смотреть акции <span aria-hidden="true">↗</span></a>
      </article>
      <article class="feature feature-team" id="team">
        <p class="eyebrow"><span></span> ЛЮДИ МУСКАРИ</p>
        <h2>Найдите <em>своего мастера</em></h2>
        <p>Познакомьтесь со специалистами по косметологии, волосам, ногтевому сервису и макияжу.</p>
        <a class="button light" href="team.php">Открыть команду <span aria-hidden="true">↗</span></a>
      </article>
    </div>
  </section>
  <section class="section shell" aria-labelledby="visit-title">
    <div class="visit-banner">
      <div><p class="eyebrow"><span></span> СПЛАНИРОВАТЬ ВИЗИТ</p><h2 id="visit-title">Выберите время <em>для себя</em></h2><p>Откройте форму, чтобы выбрать услугу, мастера и удобное время.</p></div>
      <div class="visit-actions"><button class="button light" type="button" data-book>Записаться на визит <span aria-hidden="true">↗</span></button><a id="contacts" href="contacts.php">Как нас найти ↗</a><a id="reviews" href="reviews.php">Читать отзывы ↗</a></div>
    </div>
  </section>
<?php pageEnd(); ?>
