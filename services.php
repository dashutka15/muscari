<?php
require __DIR__ . '/layout.php';
pageHead('services', 'Каталог услуг «Мускари» с ценами, длительностью и записью на визит.');
?>
  <section id="services" class="page-lead shell" aria-labelledby="services-title">
    <div class="page-lead-copy">
      <p class="eyebrow"><span></span> 01 / МЕНЮ УХОДА</p>
      <h1 id="services-title">Услуги <em>и процедуры</em></h1>
      <p>Уход за лицом, стрижка и окрашивание, маникюр, брови и макияж. Найдите нужную услугу или сравните процедуры по цене и длительности.</p>
      <div class="lead-actions"><a class="button" href="#catalog">Перейти к каталогу <span aria-hidden="true">↓</span></a><a class="button outline" href="team.php">Выбрать мастера <span aria-hidden="true">↗</span></a></div>
    </div>
    <figure><img src="assets/face.webp" alt="Массажный роллер для ухода за лицом" fetchpriority="high"><figcaption>МУСКАРИ / УХОД</figcaption></figure>
  </section>
  <section id="catalog" class="section shell" aria-labelledby="catalog-title">
    <div class="section-head"><div><p class="eyebrow"><span></span> КАТАЛОГ</p><h2 id="catalog-title">Что можно <em>выбрать</em></h2></div><p>Используйте поиск или фильтр по направлению. На карточке указаны ориентиры для планирования визита.</p></div>
    <div class="filters"><div class="pills" id="categories" aria-label="Категории услуг"></div><label class="search"><span data-icon="search"></span><input id="search" type="search" placeholder="Найти услугу" aria-label="Найти услугу"></label></div>
    <p class="sr-only" id="service-count" aria-live="polite"></p><div class="service-grid" id="service-grid"></div><p class="catalog-note">В каталоге указаны демонстрационные цены и длительность процедур.</p>
  </section>
  <section class="section shell" aria-labelledby="service-faq">
    <div class="guide-layout">
      <div><p class="eyebrow"><span></span> ПЕРЕД ЗАПИСЬЮ</p><h2 id="service-faq">Как выбрать <em>процедуру</em></h2></div>
      <div class="faq-list">
        <details><summary>Как сравнить варианты ухода?</summary><p>Откройте нужное направление и посмотрите описания, цены и длительность. Для окрашивания учитывайте длину волос и выбранную технику.</p></details>
        <details><summary>Обязательно ли выбирать мастера?</summary><p>В форме можно выбрать конкретного специалиста или оставить вариант «Любой мастер». Профили с опытом и специализацией доступны на <a href="team.php">странице команды</a>.</p></details>
        <details><summary>Выбранное время сразу подтверждается?</summary><p>Время в форме — ваше пожелание. После отправки у заявки появится статус, который можно обновить в админ-панели проекта.</p></details>
      </div>
    </div>
  </section>
  <section class="section shell" aria-labelledby="services-visit">
    <div class="visit-banner"><div><p class="eyebrow"><span></span> СЛЕДУЮЩИЙ ШАГ</p><h2 id="services-visit">Запланируйте <em>визит</em></h2><p>Укажите услугу и время, которое вам удобно.</p></div><div class="visit-actions"><button class="button light" type="button" data-book>Открыть форму записи ↗</button><a href="offers.php">Посмотреть предложения ↗</a></div></div>
  </section>
<?php pageEnd(); ?>
