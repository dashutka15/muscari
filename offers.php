<?php
require __DIR__ . '/layout.php';
pageHead('offers', 'Специальные предложения пространства красоты «Мускари».');
?>
  <section id="offers" class="page-lead page-lead-offers shell" aria-labelledby="offers-title">
    <div class="page-lead-copy">
      <p class="eyebrow"><span></span> 02 / ПРИЯТНЫЙ ПОВОД</p>
      <h1 id="offers-title">Особые <em>предложения</em></h1>
      <p>Знакомство с косметологией и первое окрашивание — два повода выбрать визит. В карточках можно посмотреть, к какой услуге относится предложение.</p>
      <div class="lead-actions"><a class="button" href="#specials">Посмотреть предложения ↓</a><a class="button outline" href="services.php">Открыть каталог ↗</a></div>
    </div>
    <div class="offer-art" aria-hidden="true"><img src="assets/botanical.svg" alt=""><span>MUSCARI</span><small>BEAUTY & CARE</small></div>
  </section>
  <section id="specials" class="section shell" aria-labelledby="specials-title">
    <div class="section-head"><div><p class="eyebrow"><span></span> ВЫБРАТЬ ПРЕДЛОЖЕНИЕ</p><h2 id="specials-title">Повод <em>для визита</em></h2></div><p>Демонстрационные предложения по мотивам сайта салона. Актуальные условия уточняйте в «Мускари».</p></div>
    <div id="offers-grid" class="offers-grid"></div>
  </section>
  <section class="section shell" aria-labelledby="offer-guide">
    <div class="section-head"><div><p class="eyebrow"><span></span> КАК ВОСПОЛЬЗОВАТЬСЯ</p><h2 id="offer-guide">От предложения <em>к заявке</em></h2></div></div>
    <ol class="step-grid">
      <li><span>01</span><h3>Откройте условия</h3><p>Проверьте, для кого действует предложение и к какой услуге оно относится.</p></li>
      <li><span>02</span><h3>Сравните услуги</h3><p>Посмотрите описание и длительность в каталоге, если ещё выбираете процедуру.</p></li>
      <li><span>03</span><h3>Укажите время</h3><p>Откройте форму из карточки акции. Предложение сохранится в заявке.</p></li>
    </ol>
  </section>
  <section class="section shell" aria-labelledby="offer-visit">
    <div class="visit-banner"><div><p class="eyebrow"><span></span> ПЛАНЫ НА ВИЗИТ</p><h2 id="offer-visit">Подберите <em>свой уход</em></h2><p>В каталоге собраны все направления, с акциями и без.</p></div><div class="visit-actions"><a class="button light" href="services.php">Смотреть услуги ↗</a><a href="contacts.php">Контакты салона ↗</a></div></div>
  </section>
<?php pageEnd(); ?>
