const brandSelector = document.getElementById("brand-selector");
const socketSelector = document.getElementById("socket-selector");
const coreMin = document.getElementById("coreRangeMin");
const coreMax = document.getElementById("coreRangeMax");
const clockMin = document.getElementById("clockSpeedMin");
const clockMax = document.getElementById("clockSpeedMax");
const priceMin = document.getElementById("priceMin");
const priceMax = document.getElementById("priceMax");
const integratedGraphics = document.querySelector("input[type='checkbox']");

// actualizar URL con filtros seleccionados
function updateURL() {
    const params = new URLSearchParams();

    if (brandSelector.value !== "Seleccione...") params.set("brand", brandSelector.value);
    if (socketSelector.value !== "Seleccione...") params.set("socket", socketSelector.value);
    if (coreMin.value) params.set("cores_min", coreMin.value);
    if (coreMax.value) params.set("cores_max", coreMax.value);
    if (clockMin.value) params.set("clock_min", clockMin.value);
    if (clockMax.value) params.set("clock_max", clockMax.value);
    if (priceMin.value) params.set("price_min", priceMin.value);
    if (priceMax.value) params.set("price_max", priceMax.value);
    if (integratedGraphics.checked) params.set("igpu", "1");

    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.location.href = newUrl;
}

function restoreFilters() {
    const params = new URLSearchParams(window.location.search);

    if (params.get("brand")) brandSelector.value = params.get("brand");
    if (params.get("socket")) socketSelector.value = params.get("socket");
    if (params.get("cores_min")) coreMin.value = params.get("cores_min");
    if (params.get("cores_max")) coreMax.value = params.get("cores_max");
    if (params.get("clock_min")) clockMin.value = params.get("clock_min");
    if (params.get("clock_max")) clockMax.value = params.get("clock_max");
    if (params.get("price_min")) priceMin.value = params.get("price_min");
    if (params.get("price_max")) priceMax.value = params.get("price_max");
    if (params.get("igpu")) integratedGraphics.checked = true;
}

[brandSelector, socketSelector, coreMin, coreMax, clockMin, clockMax, priceMin, priceMax, integratedGraphics]
    .forEach(el => el.addEventListener("change", updateURL));

document.addEventListener("DOMContentLoaded", () => {
    restoreFilters();
});