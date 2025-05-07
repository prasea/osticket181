function updateText() {
  document.getElementById('name').textContent = document.getElementById('name-input').value;
  document.getElementById('amount-num').textContent = document.getElementById('amount-num-input').value;
  document.getElementById('amount-words').textContent = document.getElementById('amount-words-input').value;
  document.getElementById('date').textContent = formatDate(document.getElementById('date-input').value);
}

function formatDate(dateStr) {
  // Converts 2025-07-05 to 0 5 0 7 2 0 2 5
  if (!dateStr) return '';
  const d = new Date(dateStr);
  const dd = String(d.getDate()).padStart(2, '0');
  const mm = String(d.getMonth() + 1).padStart(2, '0');
  const yyyy = String(d.getFullYear());
  return (dd + mm + yyyy).split('').join(' ');
}

function setGlobalFontSize(size) {
  const fields = ['name', 'amount-num', 'amount-words', 'date'];
  fields.forEach(id => {
    const el = document.getElementById(id);
    el.style.fontSize = size + 'px';
  });
}


function changeFontSize(id, size) {
  const el = document.getElementById(id);
  if (el) el.style.fontSize = size + 'px';
}

// Drag and save position
document.querySelectorAll('.draggable').forEach(el => {
  el.onmousedown = function (e) {
    let offsetX = e.clientX - el.offsetLeft;
    let offsetY = e.clientY - el.offsetTop;

    function move(e) {
      el.style.left = (e.clientX - offsetX) + 'px';
      el.style.top = (e.clientY - offsetY) + 'px';
    }

    document.addEventListener('mousemove', move);
    document.addEventListener('mouseup', () => {
      document.removeEventListener('mousemove', move);
      savePositions(); // Save to localStorage
    }, { once: true });
  };
});

function savePositions() {
  const positions = {};
  document.querySelectorAll('.draggable').forEach(el => {
    positions[el.id] = {
      top: el.style.top,
      left: el.style.left,
      fontSize: el.style.fontSize
    };
  });
  localStorage.setItem('chequePositions', JSON.stringify(positions));
}

function loadPositions() {
  const saved = localStorage.getItem('chequePositions');
  if (!saved) return;
  const positions = JSON.parse(saved);
  for (const id in positions) {
    const el = document.getElementById(id);
    if (el) {
      el.style.top = positions[id].top;
      el.style.left = positions[id].left;
      el.style.fontSize = positions[id].fontSize;
    }
  }
}

loadPositions();
