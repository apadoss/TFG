const brandSelector = document.getElementById("brand-selector");
const socketSelector = document.getElementById("socket-selector");
const formFactorSelector = document.getElementById("form-factor-selector");
const chipsetSelector = document.getElementById("chipset-selector");
const priceMin = document.getElementById("priceMin");
const priceMax = document.getElementById("priceMax");
const integratedGraphics = document.querySelector("input[type='checkbox']");

// actualizar URL con filtros seleccionados
function updateURL() {
    const params = new URLSearchParams();

    if (brandSelector.value !== "Seleccione...") params.set("brand", brandSelector.value);
    if (socketSelector.value !== "Seleccione...") params.set("socket", socketSelector.value);
    if (formFactorSelector.value !== "Seleccione...") params.set("form_factor", formFactorSelector.value);
    if (chipsetSelector.value !== "Seleccione...") params.set("chipset", chipsetSelector.value);
    if (priceMin.value) params.set("price_min", priceMin.value);
    if (priceMax.value) params.set("price_max", priceMax.value);

    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.location.href = newUrl;
}

function restoreFilters() {
    const params = new URLSearchParams(window.location.search);

    if (params.get("brand")) brandSelector.value = params.get("brand");
    if (params.get("socket")) socketSelector.value = params.get("socket");
    if (params.get("form_factor")) formFactorSelector.value = params.get("form_factor");
    if (params.get("chipset")) chipsetSelector.value = params.get("chipset");
    if (params.get("price_min")) priceMin.value = params.get("price_min");
    if (params.get("price_max")) priceMax.value = params.get("price_max");
}

[brandSelector, socketSelector, formFactorSelector, chipsetSelector, priceMin, priceMax]
    .forEach(el => el.addEventListener("change", updateURL));

document.addEventListener("DOMContentLoaded", () => {
    restoreFilters();
});