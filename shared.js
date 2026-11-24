export const escape = (value = '') => String(value).replace(/[&<>"']/g, char => ({'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;'})[char]);
export const $ = (selector, root = document) => root.querySelector(selector);
export const option = (value, text, selected = '') => `<option value="${escape(value)}"${value === selected ? ' selected' : ''}>${escape(text)}</option>`;
export const dateInMoscow = () => new Intl.DateTimeFormat('en-CA', {timeZone:'Europe/Moscow', year:'numeric', month:'2-digit', day:'2-digit'}).format(new Date());
function endpoint(url) {
  return new URL('./api/index.php?route=' + encodeURIComponent(url.slice(5)), import.meta.url);
}
async function request(url, method, data, token = '') {
  const response = await fetch(endpoint(url), {
    method, credentials:'same-origin', cache:'no-store',
    headers:{'X-CSRF-Token':token, ...(data !== undefined ? {'Content-Type':'application/json'} : {})},
    ...(data !== undefined ? {body:JSON.stringify(data)} : {}),
  });
  const body = await response.json();
  if (!response.ok) throw new Error(body.error || 'Не удалось выполнить запрос. Попробуйте ещё раз.');
  return body;
}
export async function api(url, method = 'GET', data) {
  let token = document.querySelector('meta[name=csrf-token]')?.content || '';
  if (method !== 'GET') {
    // Refresh before the write: another tab or an old module may hold an obsolete token.
    // Never retry the actual write, since a network failure might follow a successful save.
    const session = await request('/api/session', 'GET');
    const identity = session.user ? session.user.role + ':' + (session.user.id || '') : 'guest';
    const expected = document.querySelector('meta[name=account-session]')?.content;
    if (expected !== undefined && expected !== identity) {
      throw new Error('Аккаунт изменился или сеанс завершён. Обновите страницу и войдите снова. Данные формы пока сохранены.');
    }
    token = session.csrf;
    const meta = document.querySelector('meta[name=csrf-token]');
    if (meta) meta.content = token;
  }
  return request(url, method, data, token);
}
