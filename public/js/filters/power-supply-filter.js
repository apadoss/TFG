const brandSelector = document.getElementById("brand-selector");
const powerMin = document.getElementById("powerMin");
const powerMax = document.getElementById("powerMax");
const certificationSelector = document.getElementById("certification");
const priceMin = document.getElementById("priceMin");
const priceMax = document.getElementById("priceMax");

// actualizar URL con filtros seleccionados
function updateURL() {
    const params = new URLSearchParams();

    if (brandSelector.value !== "Seleccione...") params.set("brand", brandSelector.value);
    if (powerMin.value) params.set("power_min", powerMin.value);
    if (powerMax.value) params.set("power_max", powerMax.value);
    if (certificationSelector.value !== "Seleccione...") params.set("certification", certificationSelector.value);
    if (priceMin.value) params.set("price_min", priceMin.value);
    if (priceMax.value) params.set("price_max", priceMax.value);

    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.location.href = newUrl;
}

function restoreFilters() {
    const params = new URLSearchParams(window.location.search);

    if (params.get("brand")) brandSelector.value = params.get("brand");
    if (params.get("power_min")) powerMin.value = params.get("power_min");
    if (params.get("power_max")) powerMax.value = params.get("power_max");
    if (params.get("certification")) certificationSelector.value = params.get("certification");
    if (params.get("price_min")) priceMin.value = params.get("price_min");
    if (params.get("price_max")) priceMax.value = params.get("price_max");
}

[brandSelector, powerMin, powerMax, certificationSelector, priceMin, priceMax]
    .forEach(el => el.addEventListener("change", updateURL));

document.addEventListener("DOMContentLoaded", () => {
    restoreFilters();
});