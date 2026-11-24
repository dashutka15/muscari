<?php
require __DIR__ . '/layout.php';
pageHead('reviews', 'Отзывы гостей пространства красоты «Мускари».');
?>
  <section id="reviews" class="page-lead page-lead-reviews shell" aria-labelledby="reviews-title">
    <div class="page-lead-copy">
      <p class="eyebrow"><span></span> 04 / СЛОВА ГОСТЕЙ</p>
      <h1 id="reviews-title">Впечатления <em>о Мускари</em></h1>
      <p>В отзывах гости рассказывают, что им запомнилось: работа мастера, детали сервиса или атмосфера салона. Здесь можно познакомиться с их опытом и поделиться своим.</p>
      <div class="lead-actions"><a class="button" href="#guest-stories">Читать отзывы ↓</a><button class="button outline" type="button" data-review>Оставить отзыв ↗</button></div>
    </div>
    <div class="review-art" aria-hidden="true"><span>„</span><p>Уход, который<br><em>запоминается</em></p><small>ИСТОРИИ ГОСТЕЙ / МУСКАРИ</small></div>
  </section>
  <section id="guest-stories" class="section shell" aria-labelledby="stories-title">
    <div class="section-head"><div><p class="eyebrow"><span></span> ОПЫТ ГОСТЕЙ</p><h2 id="stories-title">Что <em>запомнилось</em></h2></div><p>В подборке есть краткие пересказы отзывов с официального сайта и опубликованные отзывы проекта. Источник указан на каждой карточке.</p></div>
    <div id="reviews-grid" class="reviews-grid"></div>
  </section>
  <section class="section shell" aria-label="Где читать и оставлять отзывы">
    <div class="feature-grid">
      <article class="feature feature-offers">
        <p class="eyebrow"><span></span> БОЛЬШЕ МНЕНИЙ</p><h2>Отзывы <em>на Картах</em></h2>
        <p>В карточке «Мускари» на Яндекс Картах можно почитать отзывы целиком и посмотреть фотографии салона.</p>
        <a class="button outline" href="https://yandex.ru/maps/org/muskari/59655556218/reviews/" target="_blank" rel="noopener noreferrer">Открыть Яндекс Карты ↗</a>
      </article>
      <article class="feature feature-team">
        <p class="eyebrow"><span></span> ВАШ ВИЗИТ</p><h2>Поделитесь <em>впечатлением</em></h2>
        <p>Расскажите об услуге и о том, что вам запомнилось. Отзыв появится на сайте проекта после модерации.</p>
        <button class="button light" type="button" data-review>Написать отзыв ↗</button>
      </article>
    </div>
  </section>
  <section class="section shell" aria-labelledby="reviews-visit">
    <div class="visit-banner"><div><p class="eyebrow"><span></span> СОБИРАЕТЕСЬ НА ВИЗИТ?</p><h2 id="reviews-visit">Познакомьтесь <em>с мастерами</em></h2><p>В профилях указаны опыт и специализация. Записаться можно прямо из карточки.</p></div><div class="visit-actions"><a class="button light" href="team.php">Открыть команду ↗</a><button class="visit-link" type="button" data-book>Записаться на визит ↗</button></div></div>
  </section>
<?php pageEnd(); ?>
