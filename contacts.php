<?php
require __DIR__ . '/layout.php';
pageHead('contacts', 'Адрес, телефон и часы работы салона «Мускари» в ЖК Lucky.');
?>
  <section id="contacts" class="page-lead page-lead-team shell" aria-labelledby="contacts-title">
    <div class="page-lead-copy">
      <p class="eyebrow"><span></span> 05 / КАК НАС НАЙТИ</p>
      <h1 id="contacts-title">До встречи <em>в Мускари</em></h1>
      <p>Салон находится на первом этаже ЖК Lucky на Красной Пресне, рядом со станцией метро «Улица 1905 года».</p>
      <div class="lead-actions"><a class="button" href="https://yandex.ru/maps/org/muskari/59655556218/" target="_blank" rel="noopener noreferrer">Построить маршрут ↗</a><a class="button outline" href="tel:+79100080818">Позвонить в салон ↗</a></div>
    </div>
    <figure><img src="assets/interior.webp" alt="Команда «Мускари» у салона" fetchpriority="high"><figcaption>МУСКАРИ / ЖК LUCKY</figcaption></figure>
  </section>
  <section class="section shell" aria-labelledby="contacts-info">
    <div class="section-head"><div><p class="eyebrow"><span></span> ПОЛЕЗНАЯ ИНФОРМАЦИЯ</p><h2 id="contacts-info">Адрес <em>и часы работы</em></h2></div></div>
    <div class="step-grid contact-grid">
      <article><span data-icon="pin"></span><h3>Адрес</h3><p>Москва, ул. Костикова, 4, корп. 6<br>ЖК Lucky, первый этаж</p><a href="https://yandex.ru/maps/org/muskari/59655556218/" target="_blank" rel="noopener noreferrer">Открыть на карте ↗</a></article>
      <article><span data-icon="clock"></span><h3>Часы работы</h3><p>Ежедневно, 10:00–22:00<br>Московское время</p></article>
      <article><span>ТЕЛ.</span><h3>Телефон</h3><p><a class="contact-phone" href="tel:+79100080818">+7 910 008-08-18</a><br>Для вопросов салону</p></article>
    </div>
  </section>
  <section class="section shell" aria-labelledby="route-title">
    <div class="guide-layout">
      <div><p class="eyebrow"><span></span> МАРШРУТ И ПОДГОТОВКА</p><h2 id="route-title">Перед <em>визитом</em></h2></div>
      <div class="faq-list">
        <details><summary>От какой станции метро идти?</summary><p>Ближайший ориентир — станция «Улица 1905 года». <a href="https://yandex.ru/maps/org/muskari/59655556218/" target="_blank" rel="noopener noreferrer">Откройте карточку салона на карте</a>, чтобы построить маршрут от своего местоположения.</p></details>
        <details><summary>Где посмотреть, сколько займёт услуга?</summary><p>Ориентировочная длительность есть в <a href="services.php">каталоге услуг</a>. Она поможет выбрать удобное время.</p></details>
      </div>
    </div>
  </section>
  <section class="section shell" aria-labelledby="contacts-visit">
    <div class="visit-banner"><div><p class="eyebrow"><span></span> ЗАПЛАНИРОВАТЬ ВИЗИТ</p><h2 id="contacts-visit">Увидимся <em>на Пресне</em></h2><p>Уже знаете, какую услугу хотите? Выберите удобное время в форме.</p></div><div class="visit-actions"><button class="button light" type="button" data-book>Записаться на визит ↗</button><a href="services.php">Открыть каталог ↗</a></div></div>
  </section>
<?php pageEnd(); ?>
