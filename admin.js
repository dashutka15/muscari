import {categories} from './data.js';
import {escape as e, $, option, api} from './shared.js?v=20261123-session-fix';

const sections = {bookings:'Заявки', services:'Услуги', offers:'Акции', team:'Мастера', reviews:'Отзывы'};
const statuses = {
  bookings:{new:'Новая', confirmed:'Подтверждена', done:'Выполнена', cancelled:'Отменена'},
  reviews:{pending:'На модерации', published:'Опубликован', hidden:'Скрыт'},
};
const images = {face:'Уход за лицом', hair:'Волосы', nails:'Ногтевой сервис', brows:'Брови', device:'Аппаратный уход', makeup:'Макияж', injection:'Косметология', nonna:'Нонна', tatyana:'Татьяна', darya:'Дарья', victoria:'Виктория Зал', maria:'Мария', svetlana:'Светлана', alena:'Алёна', snezhanna:'Снежанна', kristina:'Кристина', 'victoria-b':'Виктория Боева', izabella:'Изабелла'};
const dialog = $('#admin-dialog');
const dialogBody = $('#admin-dialog-body');
const content = $('#admin-content');
const feedback = $('#admin-feedback');
let current;
let active = 'bookings';
let filter = '';
function message(text = '', error = false) {
  feedback.textContent = text;
  feedback.classList.toggle('error', error);
}
function errorText(error) {
  return error instanceof SyntaxError ? 'Сервер временно недоступен.' : error.message;
}
function nav() {
  $('#admin-nav').innerHTML = Object.entries(sections).map(([key,label]) => `<button class="pill" type="button" data-tab="${key}" aria-pressed="${key===active}">${label}</button>`).join('');
}
function stats() {
  const values = [
    ['Новые заявки',current.bookings.filter(item => item.status === 'new').length],
    ['Услуги в каталоге',current.services.length],
    ['Отзывы на модерации',current.reviews.filter(item => item.status === 'pending').length],
  ];
  $('#summary').innerHTML = values.map(([label,value]) => `<div class="metric"><strong>${value}</strong>${label}</div>`).join('');
}
function controls(item) {
  return `<label class="field"><span>Статус</span><select data-status="${item.id}" aria-label="Статус записи: ${e(item.name)}">${Object.entries(statuses[active]).map(([value,label]) => option(value,label,item.status)).join('')}</select></label>`;
}
function booking(item) {
  const [year,month,day] = item.date.split('-');
  return `<article class="entry"><div><h3>${e(item.name)} · ${e(item.service)}</h3><p>${e(day)}.${e(month)}.${e(year)} в ${e(item.time)} (МСК) · ${e(item.master)}\n${e(item.phone)}${item.offer ? '\nАкция: ' + e(item.offer) : ''}</p></div>${controls(item)}</article>`;
}
function review(item) {
  return `<article class="entry"><div><h3>${e(item.name)} · ${item.rating ? item.rating + "/5" : "без оценки"}</h3><p>${e(item.text)}</p></div>${controls(item)}</article>`;
}
function entry(item) {
  const detail = active === 'services' ? `${item.category} · ${item.price} ₽ · ${item.duration} мин` : active === 'team' ? `${item.role} · ${item.experience}` : `${item.value} · ${item.tag}`;
  return `<article class="entry"><div class="entry-title">${item.image ? `<img src="assets/${e(item.image)}.webp" alt="" loading="lazy">` : ''}<div><h3>${e(item.name)}</h3><p>${e(detail)}</p></div></div><div class="actions"><button class="text-button" type="button" data-edit="${e(item.id)}">Редактировать</button><button class="text-button danger" type="button" data-delete="${e(item.id)}">Удалить</button></div></article>`;
}
function render() {
  $('#section-title').textContent = sections[active];
  $('#add-item').hidden = Boolean(statuses[active]) || !current;
  for (const button of $('#admin-nav').children) button.setAttribute('aria-pressed', String(button.dataset.tab === active));
  if (!current) return;
  stats();
  const entries = current[active].filter(item => !filter || item.status === filter);
  const filterControl = statuses[active] ? `<div class="admin-filters"><label class="field">Показать<select id="status-filter">${option('', 'Все статусы')}${Object.entries(statuses[active]).map(([value,label]) => option(value,label,filter)).join('')}</select></label></div>` : '';
  content.innerHTML = filterControl + '<div class="entry-list">' + (entries.map(active === 'bookings' ? booking : active === 'reviews' ? review : entry).join('') || '<p class="empty">В этом разделе пока нет записей.</p>') + '</div>';
}
async function refresh() {
  current = await api('/api/admin/state');
  render();
}
function show(html) {
  dialogBody.innerHTML = html;
  dialog.showModal();
  const heading = $('#dialog-title',dialogBody);
  heading.tabIndex = -1;
  heading.focus();
}
function openEditor(item) {
  const kind = active;
  const fields = {
    services:[['category','Направление','category'],['price','Цена, ₽','number',0,1000000],['duration','Длительность, мин','number',5,720],['image','Изображение','image']],
    offers:[['value','Выгода','text',1,40],['tag','Метка','text',2,80],['service','Связанная услуга','service']],
    team:[['role','Специализация','text',2,100],['experience','Опыт','text',1,40],['category','Направление','category'],['image','Фотография','image']],
  };
  const lists = {
    category:categories.map(label => [label,label]),
    image:[['','Без изображения'],...Object.entries(images)],
    service:[['','Без привязки'],...current.services.map(service => [service.id,service.name])],
  };
  function field([key,label,type,min,max]) {
    const value = item?.[key] ?? '';
    const limit = type === 'number' ? `min="${min}" max="${max}"` : `minlength="${min}" maxlength="${max}"`;
    return `<label>${label}${lists[type] ? `<select name="${key}">${lists[type].map(([v,l]) => option(v,l,value)).join('')}</select>` : `<input name="${key}" required type="${type}" ${limit} value="${e(value)}">`}</label>`;
  }
  show(`<h2 id="dialog-title">${item ? 'Редактирование' : 'Новая запись'}</h2><form class="form-grid">
    <label class="wide">${kind==='team' ? 'Имя' : 'Название'}<input name="name" required minlength="2" maxlength="100" value="${e(item?.name)}"></label>
    ${fields[kind].map(field).join('')}
    <label class="wide">Описание<textarea name="description" minlength="5" maxlength="600" required>${e(item?.description)}</textarea></label>
    <p class="error wide" role="alert"></p><button class="button wide" type="submit">Сохранить</button></form>`);
  bind(async form => api('/api/admin/' + kind + (item ? '/' + item.id : ''),item ? 'PUT' : 'POST',Object.fromEntries(new FormData(form))), 'Изменения сохранены.');
}
function remove(item) {
  const kind = active;
  show(`<h2 id="dialog-title">Удалить запись?</h2><p>${e(item.name)} будет удалена с сайта.</p><form><p class="error" role="alert"></p><div class="admin-dialog-actions"><button type="button" class="button outline" data-close>Отмена</button><button type="submit" class="button danger-button">Удалить</button></div></form>`);
  bind(() => api('/api/admin/' + kind + '/' + item.id,'DELETE'), 'Запись удалена.');
}
function bind(action, success) {
  const form = $('form',dialogBody);
  form.addEventListener('submit', async event => {
    event.preventDefault();
    const button = $('button[type=submit]',form);
    button.disabled = true;
    $('.error',form).textContent = '';
    try { await action(form); }
    catch (error) { $('.error',form).textContent = errorText(error); button.disabled = false; return; }
    dialog.close();
    message(success);
    refresh().catch(error => message(errorText(error),true));
  });
}
$('#admin-nav').addEventListener('click', event => {
  const button = event.target.closest('[data-tab]');
  if (!button) return;
  active = button.dataset.tab;
  filter = '';
  if (current) message();
  render();
});
$('#add-item').addEventListener('click', () => openEditor());
content.addEventListener('click', event => {
  const edit = event.target.closest('[data-edit]');
  const del = event.target.closest('[data-delete]');
  if (edit) openEditor(current[active].find(item => item.id === edit.dataset.edit));
  if (del) remove(current[active].find(item => item.id === del.dataset.delete));
});
content.addEventListener('change', async event => {
  const select = event.target;
  if (select.id === 'status-filter') {
    filter = select.value;
    render();
    $('#status-filter')?.focus();
    return;
  }
  if (!select.dataset.status) return;
  const kind = active;
  const id = select.dataset.status;
  const previous = current[kind].find(item => item.id === id)?.status;
  select.disabled = true;
  try {
    await api('/api/admin/' + kind + '/' + id,'PATCH',{status:select.value});
    message('Статус обновлён.');
    await refresh();
    if (active === kind) $('[data-status="' + id + '"]')?.focus();
  } catch (error) {
    select.value = previous;
    message(errorText(error),true);
  } finally { select.disabled = false; }
});
dialog.addEventListener('click', event => { if (event.target === dialog || event.target.closest('.close, [data-close]')) dialog.close(); });
nav();
refresh().catch(error => { message(errorText(error),true); content.innerHTML = '<p class="admin-loading">Обновите страницу, чтобы повторить загрузку.</p>'; });
