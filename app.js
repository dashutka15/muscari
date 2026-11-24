import {catalog, categories} from './data.js';
import {phoneHint, phonePattern} from './phone.js';
import {escape as e, $, option, api, dateInMoscow} from './shared.js?v=20261123-session-fix';

const paths = {
  pin:'<path d="M20 10c0 5-8 10-8 10S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>',
  clock:'<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
  star:'<path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1 6.2-5.5-2.9-5.5 2.9 1-6.2L3 9.6l6.2-.9Z"/>',
  search:'<circle cx="10.5" cy="10.5" r="5.5"/><path d="m15 15 4.5 4.5"/>',
};
for (const node of document.querySelectorAll('[data-icon]')) node.innerHTML = `<svg class="icon" viewBox="0 0 24 24" aria-hidden="true">${paths[node.dataset.icon]}</svg>`;
const money = new Intl.NumberFormat('ru-RU');
const requestedCategory = new URLSearchParams(location.search).get('category');
const state = {catalog, category:categories.includes(requestedCategory) ? requestedCategory : '', teamCategory:'', user:null};
const dialog = $('#dialog');
const panel = $('#dialog-content');

function pills(target, active) {
  $(target).innerHTML = ['', ...categories].map(category => `<button type="button" class="pill" data-category="${e(category)}" aria-pressed="${category === active}">${e(category || 'Все направления')}</button>`).join('');
}
function picture(key) {
  return /^[a-z-]+$/.test(key || '') ? `<img class="card-img" src="assets/${key}.webp" alt="" loading="lazy" decoding="async">` : '';
}
function services() {
  const query = $('#search').value.trim().toLocaleLowerCase('ru').replaceAll('ё', 'е');
  const list = state.catalog.services.filter(item => (!state.category || item.category === state.category) && `${item.name} ${item.category} ${item.description}`.toLocaleLowerCase('ru').replaceAll('ё','е').includes(query));
  $('#service-count').textContent = `Найдено услуг: ${list.length}`;
  $('#service-grid').innerHTML = list.map(item => `
    <article class="card service-card" data-image="${e(item.image)}">${picture(item.image)}
      <div class="card-body"><div class="card-meta"><span>${e(item.category)}</span><span>${item.duration} мин</span></div>
      <h3>${e(item.name)}</h3><p>${e(item.description)}</p>
      <div class="card-foot"><strong>от ${money.format(item.price)} ₽</strong><button class="text-button" type="button" data-book data-service="${e(item.id)}" aria-label="Записаться: ${e(item.name)}">Записаться ↗</button></div></div>
    </article>`).join('') || '<p class="empty">По вашему запросу ничего не найдено. Попробуйте другое название или выберите все направления.</p>';
}
function team() {
  const list = state.catalog.team.filter(item => !state.teamCategory || item.category === state.teamCategory);
  $('#team-grid').innerHTML = list.map(item => `
    <article class="card team-card">${picture(item.image)}<div class="card-body">
    <div class="card-meta"><span>${e(item.category)}</span><span>${e(item.experience)}</span></div>
    <h3>${e(item.name)}</h3><p class="team-role">${e(item.role)}</p><p>${e(item.description)}</p>
    <div class="card-foot"><button class="text-button" type="button" data-profile="${e(item.id)}" aria-label="Подробнее: ${e(item.name)}">О мастере ↗</button><button class="text-button" type="button" data-book data-master="${e(item.id)}" aria-label="Записаться к мастеру: ${e(item.name)}">Записаться</button></div></div></article>`).join('') || '<p class="empty">В этом направлении пока нет мастеров.</p>';

}
function offers() {
  $('#offers-grid').innerHTML = state.catalog.offers.map(item => `
    <article class="offer"><small>${e(item.tag)}</small><div class="offer-value">${e(item.value)}</div><h3>${e(item.name)}</h3><p>${e(item.description)}</p>
    <button class="button outline" type="button" data-offer="${e(item.id)}">Условия предложения ↗</button></article>`).join('') || '<p class="empty">Новые предложения появятся здесь.</p>';
}
function reviews() {
  $('#reviews-grid').innerHTML = state.catalog.reviews.map(item => `
    <article class="review">${item.rating ? `<div class="stars" aria-label="Оценка ${item.rating} из 5">${'★'.repeat(item.rating)}${'☆'.repeat(5 - item.rating)}</div>` : '<div class="stars" aria-hidden="true">✦</div>'}
    <p>${e(item.text)}</p><footer>${e(item.name)}<span>${e(item.source || 'Отзыв на сайте проекта')}</span></footer></article>`).join('') || '<p class="empty">Здесь будут опубликованные отзывы.</p>';
}

function show(content) {
  panel.innerHTML = content;
  if (!dialog.open) dialog.showModal();
  const heading = $('#dialog-title', panel);
  heading.tabIndex = -1;
  heading.focus();
}
function notify(title, message) {
  show(`<p class="eyebrow"><span></span> МУСКАРИ</p><h2 id="dialog-title">${title}</h2><p>${message}</p><button type="button" class="button" style="margin-top:18px" data-close>Готово</button>`);
}
function bindForm(endpoint, onSuccess) {
  const form = $('form', panel);
  form.addEventListener('submit', async event => {
    event.preventDefault();
    const button = $('button[type=submit]', form);
    const error = $('.error', form);
    const data = Object.fromEntries(new FormData(form));
    button.disabled = true;
    button.classList.add('pending');
    error.textContent = '';
    try { await api(endpoint, 'POST', data); onSuccess(); }
    catch (failure) {
      error.textContent = failure instanceof SyntaxError ? 'Сервис временно недоступен. Попробуйте ещё раз.' : failure.message;
      button.disabled = false;
      button.classList.remove('pending');
    }
  });
}
function booking(serviceId = '', masterId = '', offerId = '') {
  if (!state.catalog.services.length) return notify('Каталог обновляется', 'Попробуйте оставить заявку позже.');
  const selectedMaster = state.catalog.team.find(item => item.id === masterId);
  const selectedOffer = state.catalog.offers.find(item => item.id === offerId);
  const serviceList = state.catalog.services.filter(item =>
    (!selectedMaster || item.category === selectedMaster.category) && (!selectedOffer?.service || item.id === selectedOffer.service),
  );
  if (!serviceList.length) return notify('Каталог обновляется', 'У этого мастера пока нет доступных для заявки услуг.');
  show(`<p class="eyebrow"><span></span> ВИЗИТ В МУСКАРИ</p><h2 id="dialog-title">Заявка на визит</h2>
    <p>${selectedOffer ? e(selectedOffer.name) + '. ' : ''}Укажите удобное время. ${state.user?.role === 'client' ? 'Запись появится в вашем личном кабинете.' : 'Чтобы сохранить запись в личном кабинете, войдите в аккаунт перед отправкой.'}</p>
    <form class="form-grid">
      <label>Услуга<select required name="service">${selectedOffer?.service ? '' : option('', 'Выберите услугу')}${serviceList.map(item => option(item.id, item.name, serviceId)).join('')}</select></label>
      <label>Мастер<select name="master"></select></label>
      <label>Желаемая дата<input type="date" name="date" required min="${dateInMoscow()}"></label>
      <label>Желаемое время<select required name="time">${option('', 'Выберите время')}${Array.from({length:23}, (_, i) => `${10 + Math.floor(i / 2)}:${i % 2 ? '30' : '00'}`).map(t => option(t,t)).join('')}</select></label>
      <label>Имя<input name="name" required minlength="2" maxlength="80" autocomplete="off" placeholder="Тестовое имя" value="${e(state.user?.role === 'client' ? state.user.name : '')}"></label>
      <label>Телефон<input name="phone" type="tel" required maxlength="18" autocomplete="off" pattern="${phonePattern}" aria-describedby="phone-hint" placeholder="+7 900 000-00-00" value="${e(state.user?.role === 'client' ? state.user.phone : '')}"><span class="field-hint" id="phone-hint">+7 и 10 цифр номера</span></label>
      <input name="offer" type="hidden" value="${e(selectedOffer?.id || '')}">
      <label class="wide check"><input required type="checkbox" name="demo" value="yes"> Использую вымышленные контактные данные для демонстрации.</label>
      <p class="error wide" role="alert"></p><button type="submit" class="button wide">Отправить заявку ↗</button>
    </form>`);
  const phone = $('[name=phone]', panel);
  phone.addEventListener('invalid', () => {
    if (phone.validity.patternMismatch) phone.setCustomValidity(phoneHint);
  });
  phone.addEventListener('input', () => phone.setCustomValidity(''));
  const select = $('[name=service]', panel);
  const masters = $('[name=master]', panel);
  function updateMasters(preferred) {
    const category = state.catalog.services.find(item => item.id === select.value)?.category;
    const matches = state.catalog.team.filter(item => !category || item.category === category);
    masters.innerHTML = option('', 'Любой мастер') + matches.map(item => option(item.id, item.name, preferred)).join('');
  }
  updateMasters(masterId);
  select.addEventListener('change', () => updateMasters(masters.value));
  bindForm('/api/bookings', () => {
    if (state.user?.role === 'client') {
      show('<p class="eyebrow"><span></span> ЗАПИСЬ ПРИНЯТА</p><h2 id="dialog-title">До встречи в Мускари</h2><p>Заявка сохранена. Статус можно посмотреть в личном кабинете. Время ожидает подтверждения.</p><a class="button" style="margin-top:18px" href="account.php">Мои записи ↗</a>');
    } else {
      notify('Заявка сохранена', 'Администратор получил заявку. Выбранное время ожидает подтверждения.');
    }
  });
}
function reviewForm() {
  show(`<p class="eyebrow"><span></span> СЛОВА ГОСТЕЙ</p><h2 id="dialog-title">Оставить отзыв</h2><p>В учебном проекте отзыв публикуется после модерации. Используйте вымышленное имя.</p>
    <form class="form-grid"><label>Имя<input required name="name" minlength="2" maxlength="80" placeholder="Тестовое имя" value="${e(state.user?.role === 'client' ? state.user.name : '')}"></label>
    <label>Оценка<select name="rating" required>${[5,4,3,2,1].map(r => option(r, `${r} из 5`)).join('')}</select></label>
    <label class="wide">Отзыв<textarea required name="text" minlength="10" maxlength="1200" rows="4"></textarea></label>
    <p class="error wide" role="alert"></p><button type="submit" class="button wide">Отправить на модерацию ↗</button></form>`);
  bindForm('/api/reviews', () => notify('Отзыв отправлен', 'После одобрения он появится в разделе отзывов.'));
}

document.addEventListener('click', event => {
  const book = event.target.closest('[data-book]');
  const profile = event.target.closest('[data-profile]');
  const offer = event.target.closest('[data-offer]');
  if (book) {
    sessionReady.then(() => booking(book.dataset.service, book.dataset.master, book.dataset.promo));
  }
  if (event.target.closest('[data-review]')) sessionReady.then(reviewForm);
  if (profile) {
    const item = state.catalog.team.find(person => person.id === profile.dataset.profile);
    if (item) show(`${item.image ? `<img class="profile-img" src="assets/${e(item.image)}.webp" alt="">` : ''}<p class="eyebrow"><span></span> ${e(item.category)} · ${e(item.experience)}</p>
      <h2 id="dialog-title">${e(item.name)}</h2><p><strong>${e(item.role)}</strong></p><p class="profile-copy">${e(item.description)}</p>
      <button class="button" type="button" data-book data-master="${e(item.id)}">Выбрать мастера ↗</button>`);
  }
  if (offer) {
    const item = state.catalog.offers.find(entry => entry.id === offer.dataset.offer);
    if (item) show(`<p class="eyebrow"><span></span> ${e(item.tag)}</p><h2 id="dialog-title">${e(item.name)}</h2><p class="profile-copy"><strong>${e(item.value)}</strong> · ${e(item.description)}</p>
      <button type="button" class="button" data-book data-service="${e(item.service)}" data-promo="${e(item.id)}">Оставить заявку ↗</button>`);
  }
  if (event.target === dialog || event.target.closest('.close, [data-close]')) dialog.close();
});
const menu = $('.menu');
function toggleMenu(open) {
  $('#nav').classList.toggle('is-open', open);
  menu.setAttribute('aria-expanded', String(open));
  menu.setAttribute('aria-label', open ? 'Закрыть меню' : 'Открыть меню');
}
menu.addEventListener('click', () => toggleMenu(menu.getAttribute('aria-expanded') === 'false'));
$('#nav').addEventListener('click', event => { if (event.target.closest('a')) toggleMenu(false); });
document.addEventListener('keydown', event => {
  if (event.key === 'Escape' && menu.getAttribute('aria-expanded') === 'true') { toggleMenu(false); menu.focus(); }
});
function activateFilter(event, key, render) {
  const button = event.target.closest('[data-category]');
  if (!button) return;
  state[key] = button.dataset.category;
  for (const control of event.currentTarget.children) control.setAttribute('aria-pressed', String(control === button));
  render();
}
const renderers = [];
if ($('#service-grid')) {
  pills('#categories', state.category);
  $('#categories').addEventListener('click', event => activateFilter(event, 'category', services));
  $('#search').addEventListener('input', services);
  renderers.push(services);
}
if ($('#team-grid')) {
  pills('#team-filters', '');
  $('#team-filters').addEventListener('click', event => activateFilter(event, 'teamCategory', team));
  renderers.push(team);
}
if ($('#offers-grid')) renderers.push(offers);
if ($('#reviews-grid')) renderers.push(reviews);
function render() {
  for (const section of renderers) section();
}
render();
const sessionReady = api('/api/session').then(data => {
  state.user = data.user;
  const token = document.querySelector('meta[name=csrf-token]');
  if (token) token.content = data.csrf;
}).catch(() => {});
api('/api/catalog').then(data => { state.catalog = data; render(); }).catch(() => {});

for (const phone of document.querySelectorAll('input[type=tel][name=phone]')) {
  phone.pattern = phonePattern;
  phone.title = phoneHint;
}
