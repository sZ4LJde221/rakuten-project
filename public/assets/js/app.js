// public/assets/js/app.js

document.addEventListener('DOMContentLoaded', loadBooks);

async function loadBooks() {
  const ENDPOINT = '/index.php?json=1';
  const listContainer = document.getElementById('book-list');

  try {
    const res = await fetch(ENDPOINT);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();

    if (data.error) {
      throw new Error(data.error);
    }

    const items = data.items;
    if (!items || items.length === 0) {
      listContainer.textContent = '現在、100円以下の電子書籍はありません。';
      return;
    }

    const ul = document.createElement('ul');
    items.forEach(item => {
      const li = document.createElement('li');
      li.innerHTML = `
        <a href="${item.itemUrl}" target="_blank" rel="noopener noreferrer">
          ${item.title}
        </a> — ¥${item.itemPrice}
      `;
      ul.appendChild(li);
    });

    listContainer.innerHTML = '';
    listContainer.appendChild(ul);
  } catch (err) {
    console.error(err);
    listContainer.textContent = `データ取得に失敗しました (${err.message})`;
  }
}