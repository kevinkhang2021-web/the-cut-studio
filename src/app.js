const products = [
  { id: "fc25", name: "EA SPORTS FC 25", kind: "Jeu de sport", platform: "PS5", price: 75000, theme: ["#d6a876", "#cf684d", "#55694d"], art: "FC 25", shape: "disc", availability: "En salle" },
  { id: "spider", name: "Marvel’s Spider-Man 2", kind: "Action · aventure", platform: "PS5", price: 80000, theme: ["#d9b08f", "#d94c46", "#384f63"], art: "SPIDER", shape: "", availability: "En salle" },
  { id: "gta", name: "Grand Theft Auto V", kind: "Action · aventure", platform: "PS4", price: 45000, theme: ["#d5b477", "#e17b4e", "#334d47"], art: "V", shape: "disc", availability: "En salle" },
  { id: "dualsense", name: "Manette DualSense", kind: "Accessoire", platform: "Accessoire", price: 89000, theme: ["#c6d2c4", "#678778", "#344b40"], art: "DUAL", shape: "pad", availability: "Sur commande" },
  { id: "mk1", name: "Mortal Kombat 1", kind: "Combat", platform: "PS5", price: 70000, theme: ["#dabd85", "#ba6049", "#474337"], art: "MK 1", shape: "", availability: "En salle" },
  { id: "rdr2", name: "Red Dead Redemption 2", kind: "Aventure", platform: "PS4", price: 55000, theme: ["#dbc193", "#a64c3d", "#474438"], art: "RDR 2", shape: "disc", availability: "En salle" },
  { id: "console", name: "PlayStation 5", kind: "Console", platform: "Accessoire", price: 0, theme: ["#c5d1cc", "#647d78", "#273e38"], art: "PS5", shape: "console", availability: "Sur commande", quote: true },
  { id: "headset", name: "Casque gaming", kind: "Accessoire", platform: "Accessoire", price: 65000, theme: ["#d8cec1", "#927d6e", "#50473f"], art: "AUDIO", shape: "pad", availability: "Sur commande" },
];

const state = { filter: "all", query: "", selectedTime: "", duration: 1, cart: [] };
const grid = document.querySelector("#catalogue-grid");
const search = document.querySelector("#catalogue-search");
const toast = document.querySelector("#toast");
const bookingForm = document.querySelector("#booking-form");
const dateInput = document.querySelector("#booking-date");
const timeOptions = document.querySelector("#time-options");
let toastTimer;

function money(amount) {
  return `${new Intl.NumberFormat("fr-FR").format(amount)} CDF`;
}

function productCard(product) {
  const [background, accent, secondary] = product.theme;
  const badge = product.platform === "Accessoire" ? "GEAR" : product.platform;
  const price = product.quote ? "Sur demande" : money(product.price);
  return `<article class="product-card">
    <div class="product-art" style="--art-bg:${background};--art-accent:${accent};--art-secondary:${secondary}">
      <span class="product-badge">${badge}</span><span class="product-availability">${product.availability}</span>
      <span class="product-object ${product.shape ? `is-${product.shape}` : ""}" aria-hidden="true">${product.art}</span>
    </div>
    <div class="product-meta"><span>${product.kind}</span><span>${product.platform === "Accessoire" ? "Boutique" : product.platform}</span></div>
    <h3>${product.name}</h3>
    <div class="product-bottom"><strong>${price}</strong><button class="add-button" type="button" data-add="${product.id}" aria-label="Ajouter ${product.name} au panier">+</button></div>
  </article>`;
}

function renderProducts() {
  const visible = products.filter((product) => {
    const categoryMatch = state.filter === "all" || product.platform === state.filter;
    const queryMatch = `${product.name} ${product.kind} ${product.platform}`.toLocaleLowerCase("fr").includes(state.query);
    return categoryMatch && queryMatch;
  });
  grid.innerHTML = visible.length ? visible.map(productCard).join("") : '<p class="catalogue-empty">Aucun article ne correspond à ta recherche. Essaie un autre mot-clé.</p>';
}

function notify(message) {
  toast.textContent = message;
  toast.classList.add("is-visible");
  window.clearTimeout(toastTimer);
  toastTimer = window.setTimeout(() => toast.classList.remove("is-visible"), 3200);
}

function selectedPlatform() {
  return bookingForm.querySelector('input[name="platform"]:checked').value;
}

function hourlyRate() {
  return selectedPlatform() === "PS5" ? 5000 : 3000;
}

function updateBookingSummary() {
  const date = dateInput.value ? new Intl.DateTimeFormat("fr-FR", { day: "numeric", month: "short" }).format(new Date(`${dateInput.value}T12:00:00`)) : "Choisis une date";
  const range = state.selectedTime ? `${state.selectedTime} – ${String(Number(state.selectedTime.slice(0, 2)) + state.duration).padStart(2, "0")}:00` : "Choisis un créneau";
  document.querySelector("#booking-summary").textContent = `${selectedPlatform()} · ${date} · ${range}`;
  document.querySelector("#booking-price").textContent = state.selectedTime ? money(hourlyRate() * state.duration) : "— CDF";
}

function renderTimeOptions() {
  const slots = ["10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00"];
  timeOptions.innerHTML = slots.map((time, index) => {
    const unavailable = index === 2 || index === 7;
    const active = state.selectedTime === time;
    return `<button class="time-button ${active ? "is-selected" : ""}" type="button" data-time="${time}" ${unavailable ? "disabled aria-label='Créneau complet'" : `aria-pressed="${active}"`}>${time}</button>`;
  }).join("");
}

function initializeDate() {
  const today = new Date();
  const localToday = new Date(today.getTime() - today.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
  dateInput.min = localToday;
  dateInput.value = localToday;
}

document.querySelectorAll(".filter-button").forEach((button) => {
  button.addEventListener("click", () => {
    state.filter = button.dataset.filter;
    document.querySelectorAll(".filter-button").forEach((item) => {
      const selected = item === button;
      item.classList.toggle("is-selected", selected);
      item.setAttribute("aria-pressed", String(selected));
    });
    renderProducts();
  });
});

search.addEventListener("input", () => {
  state.query = search.value.trim().toLocaleLowerCase("fr");
  renderProducts();
});

grid.addEventListener("click", (event) => {
  const button = event.target.closest("[data-add]");
  if (!button) return;
  const product = products.find((item) => item.id === button.dataset.add);
  if (product) {
    state.cart.push(product.id);
    const label = product.quote ? "Ajouté à ta liste d’intérêt" : "Ajouté au panier de démonstration";
    notify(`${product.name} · ${label}.`);
  }
});

bookingForm.querySelectorAll('input[name="platform"]').forEach((input) => {
  input.addEventListener("change", () => {
    bookingForm.querySelectorAll(".platform-option").forEach((option) => option.classList.toggle("is-selected", option.contains(input) && input.checked));
    updateBookingSummary();
  });
});

dateInput.addEventListener("change", updateBookingSummary);

timeOptions.addEventListener("click", (event) => {
  const button = event.target.closest("[data-time]");
  if (!button || button.disabled) return;
  state.selectedTime = button.dataset.time;
  renderTimeOptions();
  updateBookingSummary();
});

bookingForm.addEventListener("submit", (event) => {
  event.preventDefault();
  if (!bookingForm.reportValidity()) return;
  if (!state.selectedTime) {
    notify("Choisis un créneau avant de continuer.");
    timeOptions.querySelector(".time-button:not(:disabled)")?.focus();
    return;
  }
  const booking = {
    platform: selectedPlatform(),
    date: dateInput.value,
    time: state.selectedTime,
    duration: state.duration,
    amount: hourlyRate() * state.duration,
    createdAt: new Date().toISOString(),
  };
  try {
    localStorage.setItem("the-cut-studio:last-booking", JSON.stringify(booking));
  } catch {
    // Le prototype reste utilisable si le navigateur bloque le stockage local.
  }
  notify(`Session de démonstration notée · ${booking.platform}, ${booking.time} · ${money(booking.amount)}. Rien n’a été transmis à la salle.`);
});

document.querySelectorAll(".nav-link").forEach((link) => {
  link.addEventListener("click", () => {
    document.querySelectorAll(".nav-link").forEach((item) => item.classList.toggle("is-active", item === link));
    document.querySelector("#main-nav").classList.remove("is-open");
    document.querySelector(".menu-toggle").setAttribute("aria-expanded", "false");
  });
});

document.querySelector(".menu-toggle").addEventListener("click", (event) => {
  const button = event.currentTarget;
  const expanded = button.getAttribute("aria-expanded") === "true";
  button.setAttribute("aria-expanded", String(!expanded));
  document.querySelector("#main-nav").classList.toggle("is-open", !expanded);
});

document.querySelector("#current-year").textContent = new Date().getFullYear();
initializeDate();
renderTimeOptions();
renderProducts();
updateBookingSummary();
