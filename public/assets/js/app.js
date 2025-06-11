// public/assets/js/app.js
async function loadBooks() {
    const res = await fetch('/fetch_books.php');
    if (!res.ok) {
      document.getElementById('book-list').textContent = 'データ取得に失敗しました';
      return;
    }
    const json = await res.json();
    const ul = document.getElementById('book-list');
    ul.innerHTML = '';
    json.Items.forEach(item => {
      const li = document.createElement('li');
      li.innerHTML = `<a href="${item.itemUrl}" target="_blank">${item.title}</a> — ¥${item.itemPrice}`;
      ul.appendChild(li);
    });
  }
  
  window.addEventListener('DOMContentLoaded', loadBooks);
  