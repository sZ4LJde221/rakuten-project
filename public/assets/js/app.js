// public/assets/js/app.js
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('book-list');
  
    fetch(window.API_ENDPOINT)
      .then(res => {
        if (!res.ok) throw new Error('Network response was not ok: ' + res.status);
        return res.json();
      })
      .then(data => {
        if (data.items && data.items.length) {
          container.innerHTML = data.items.map(item => `
            <div>
              <h3>${item.title}</h3>
              <p>価格: ${item.itemPrice}円</p>
              <a href="${item.itemUrl}" target="_blank">商品ページ</a>
            </div>
          `).join('');
        } else {
          container.textContent = '商品が見つかりません。';
        }
      })
      .catch(err => {
        container.textContent = 'データ取得に失敗しました。';
        console.error('Fetch error:', err);
      });
  });
  