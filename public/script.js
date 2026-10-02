const canvas = document.getElementById('board');
const ctx = canvas.getContext('2d');

// --- STATO DEL GIOCO E PUNTEGGIO ---
let giocoFinito = false;
let pezzi = [];
let striker;

// Variabili per il nuovo sistema di punteggio
let numeroTiri = 0; // Conta i lanci totali
let comboTiro = 0; // Conta quanti pezzi imbuchi con un solo lancio
let punteggioPartita = 0; // Punteggio base (accumulato con le pedine)
let pezziRimanenti = 0;

const W = canvas.width;
const H = canvas.height;
const CENTER_X = W / 2;
const CENTER_Y = H / 2;

const ATTRITO = 0.985; // Ogni frame, la velocità viene moltiplicata per 0.985 (perde l'1.5%)
const RAGGIO_BUCA = 32;
const PENALE_PER_TIRO = 2; // Valore della penale dopo i tiri gratuiti
const TIRI_GRATUITI = 15; // I primi 15 tiri non costano nulla

// --- CLASSE PEDINA: ogni pedina è un oggetto con le sue proprietà (posizione, velocità e punti)
//  e i suoi metodi (disegna, aggiorna) ---
class Pedina {
  constructor(x, y, raggio, colore, massa, punti) {
    this.x = x;
    this.y = y;
    this.raggio = raggio;
    this.colore = colore;
    this.massa = massa;
    this.punti = punti;
    //velocità inizialmente nulla
    this.vx = 0;
    this.vy = 0;
    this.inBuca = false; //stato per sapere se rimuovere la pedina o no
  }

  disegna() {
    if (this.inBuca) return;
    // Ombra
    ctx.beginPath();
    ctx.arc(this.x + 2, this.y + 2, this.raggio, 0, Math.PI * 2);
    ctx.fillStyle = 'rgba(0,0,0,0.3)';
    ctx.fill();

    // Corpo
    ctx.beginPath();
    ctx.arc(this.x, this.y, this.raggio, 0, Math.PI * 2);
    ctx.fillStyle = this.colore;
    ctx.fill();

    // Bordo
    ctx.strokeStyle = '#333';
    ctx.lineWidth = 1.5;
    ctx.stroke();
  }

  aggiorna() {
    if (this.inBuca) return; // se è in buca allora non calcolo la sua fisica

    // Movimento e attrito
    this.x += this.vx;
    this.y += this.vy;
    this.vx *= ATTRITO;
    this.vy *= ATTRITO;

    // 2. Controllo "Fermo" (Evita micro-movimenti infiniti)
    if (Math.abs(this.vx) < 0.05) this.vx = 0;
    if (Math.abs(this.vy) < 0.05) this.vy = 0;

    // 3. Rimbalzi sui bordi (Inversione di velocità con perdita di energia 0.6)
    if (this.x - this.raggio < 0 || this.x + this.raggio > W) {
      this.x = this.x < W / 2 ? this.raggio : W - this.raggio;
      this.vx = -this.vx * 0.6;
    }
    if (this.y - this.raggio < 0 || this.y + this.raggio > H) {
      this.y = this.y < H / 2 ? this.raggio : H - this.raggio;
      this.vy = -this.vy * 0.6;
    }

    // --- CONTROLLO BUCHE ---
    const buche = [
      { x: 0, y: 0 },
      { x: W, y: 0 },
      { x: 0, y: H },
      { x: W, y: H },
    ];

    buche.forEach((buca) => {
      let dist = Math.hypot(this.x - buca.x, this.y - buca.y);
      if (dist < RAGGIO_BUCA) {
        this.inBuca = true;
        this.vx = 0;
        this.vy = 0;

        if (this.punti > 0) {
          // --- LOGICA COMBO ---
          comboTiro++;
          punteggioPartita += this.punti * comboTiro;

          pezziRimanenti--;
          aggiornaUI();
          if (pezziRimanenti === 0) terminaPartita();
        } else {
          // Penalità Striker (perdi punti e resettalo)
          punteggioPartita = Math.max(0, punteggioPartita - 20);
          aggiornaUI();
          setTimeout(() => resetStriker(), 500);
        }
      }
    });
  }
}

// --- UI E LOGICA DI FINE PARTITA ---

function aggiornaUI() {
  const scoreEl = document.getElementById('current-score');
  if (scoreEl) {
    scoreEl.innerHTML = `
      <div style="font-size: 1.2em;">Punti Base: <strong>${punteggioPartita}</strong></div>
      <div style="font-size: 0.9em; color: #f1c40f;">Lanci effettuati: ${numeroTiri}</div>
    `;
  }
}

function terminaPartita() {
  if (giocoFinito) return;
  giocoFinito = true;

  // CALCOLO FINALE: Punti Base - (Tiri * 10)
  // Math.max(0, ...) serve a evitare che il punteggio diventi negativo
  const tiriExtra = Math.max(0, numeroTiri - TIRI_GRATUITI);
  const penaleTiri = tiriExtra * PENALE_PER_TIRO;
  const punteggioFinale = Math.max(0, punteggioPartita - penaleTiri);

  alert(
    `PARTITA FINITA!\n\n` +
      `Punti accumulati: ${punteggioPartita}\n` +
      `Penale Tiri (${numeroTiri}): -${penaleTiri}\n\n` +
      `PUNTEGGIO TOTALE: ${punteggioFinale}`
  );

  // Invio dati al server
  fetch('salva-punteggio.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ punteggio: punteggioFinale }),
  })
    .then(() => {
      window.location.href = 'classifica.php';
    })
    .catch((err) => console.error('Errore salvataggio:', err));
}

function resetStriker() {
  striker.x = CENTER_X;
  striker.y = H - 100;
  striker.vx = 0;
  striker.vy = 0;
  striker.inBuca = false;
}

// --- FISICA E INPUT ---

function gestisciCollisioni() {
  // Filtriamo solo le pedine non imbucate
  let tutti = [striker, ...pezzi].filter((p) => !p.inBuca);

  for (let i = 0; i < tutti.length; i++) {
    for (let j = i + 1; j < tutti.length; j++) {
      let p1 = tutti[i],
        p2 = tutti[j];
      let dx = p2.x - p1.x,
        dy = p2.y - p1.y;
      let dist = Math.sqrt(dx * dx + dy * dy);

      // Se la distanza è minore della somma dei raggi, c'è collisione
      if (dist < p1.raggio + p2.raggio) {
        // 1. Risoluzione dell'overlap (impedisce che si sovrappongano)
        let angolo = Math.atan2(dy, dx);
        let overlap = p1.raggio + p2.raggio - dist + 1;
        p1.x -= overlap * Math.cos(angolo) * 0.5;
        p1.y -= overlap * Math.sin(angolo) * 0.5;
        p2.x += overlap * Math.cos(angolo) * 0.5;
        p2.y += overlap * Math.sin(angolo) * 0.5;

        // Collisione elastica semplificata
        let v1 = Math.sqrt(p1.vx ** 2 + p1.vy ** 2),
          v2 = Math.sqrt(p2.vx ** 2 + p2.vy ** 2);
        let d1 = Math.atan2(p1.vy, p1.vx),
          d2 = Math.atan2(p2.vy, p2.vx);
        let vx1New = v1 * Math.cos(d1 - angolo),
          vy1New = v1 * Math.sin(d1 - angolo);
        let vx2New = v2 * Math.cos(d2 - angolo),
          vy2New = v2 * Math.sin(d2 - angolo);
        let fVx1 =
          ((p1.massa - p2.massa) * vx1New + 2 * p2.massa * vx2New) /
          (p1.massa + p2.massa);
        let fVx2 =
          (2 * p1.massa * vx1New + (p2.massa - p1.massa) * vx2New) /
          (p1.massa + p2.massa);
        p1.vx =
          Math.cos(angolo) * fVx1 + Math.cos(angolo + Math.PI / 2) * vy1New;
        p1.vy =
          Math.sin(angolo) * fVx1 + Math.sin(angolo + Math.PI / 2) * vy1New;
        p2.vx =
          Math.cos(angolo) * fVx2 + Math.cos(angolo + Math.PI / 2) * vy2New;
        p2.vy =
          Math.sin(angolo) * fVx2 + Math.sin(angolo + Math.PI / 2) * vy2New;
      }
    }
  }
}

function loop() {
  ctx.clearRect(0, 0, W, H);
  // Buche
  ctx.fillStyle = '#1a1a1a';
  [0, W].forEach((x) =>
    [0, H].forEach((y) => {
      ctx.beginPath();
      ctx.arc(x, y, RAGGIO_BUCA, 0, Math.PI * 2);
      ctx.fill();
    })
  );

  pezzi.forEach((p) => {
    p.aggiorna();
    p.disegna();
  });
  striker.aggiorna();
  striker.disegna();
  gestisciCollisioni();

  if (isDragging) {
    ctx.beginPath();
    ctx.moveTo(striker.x, striker.y);
    ctx.lineTo(dragStartX, dragStartY);
    ctx.strokeStyle = 'rgba(255,255,255,0.6)';
    ctx.lineWidth = 3;
    ctx.setLineDash([5, 5]);
    ctx.stroke();
    ctx.setLineDash([]);
  }
  requestAnimationFrame(loop);
}

function getMousePos(e) {
  let r = canvas.getBoundingClientRect();
  return {
    x: (e.clientX - r.left) * (canvas.width / r.width),
    y: (e.clientY - r.top) * (canvas.height / r.height),
  };
}

let isDragging = false,
  dragStartX,
  dragStartY;

canvas.addEventListener('mousedown', (e) => {
  let p = getMousePos(e);
  if (
    Math.hypot(p.x - striker.x, p.y - striker.y) < striker.raggio + 15 &&
    Math.abs(striker.vx) < 0.2
  ) {
    isDragging = true;
    dragStartX = p.x;
    dragStartY = p.y;
  }
});

canvas.addEventListener('mousemove', (e) => {
  if (isDragging) {
    let p = getMousePos(e);
    dragStartX = p.x;
    dragStartY = p.y;
  }
});

canvas.addEventListener('mouseup', () => {
  if (isDragging) {
    isDragging = false;
    striker.vx = (striker.x - dragStartX) * 0.15;
    striker.vy = (striker.y - dragStartY) * 0.15;

    // --- NUOVO CONTROLLO LANCI ---
    numeroTiri++;
    comboTiro = 0; // Reset combo per il nuovo tiro
    aggiornaUI();
  }
});

function init() {
  pezzi = []; // Svuota tutto l'array per evitare duplicati
  punteggioPartita = 0;
  giocoFinito = false;

  // 1. REGINA AL CENTRO (Rossa - 50 Punti)
  pezzi.push(new Pedina(CENTER_X, CENTER_Y, 15, '#ff4444', 1, 50));

  // 2. SOLO UN CERCHIO (6 pedine attorno)
  // È sufficiente per mostrare che la fisica e la logica funzionano
  for (let i = 0; i < 6; i++) {
    let angolo = i * ((Math.PI * 2) / 6); // 60 gradi
    let dist = 34; // Distanza dal centro (un po' più larghe per non incastrarsi)

    // Alterniamo i colori
    let colore = i % 2 === 0 ? 'white' : '#222';
    let punti = i % 2 === 0 ? 20 : 10;

    pezzi.push(
      new Pedina(
        CENTER_X + Math.cos(angolo) * dist,
        CENTER_Y + Math.sin(angolo) * dist,
        14,
        colore,
        1,
        punti
      )
    );
  }

  // Imposta il numero di pedine da mandare in buca (saranno 7)
  pezziRimanenti = pezzi.length;

  // Striker (Giallo) in posizione di partenza
  striker = new Pedina(CENTER_X, H - 100, 21, '#ffcc00', 2.5, 0);

  aggiornaUI();
}

init();
loop();
