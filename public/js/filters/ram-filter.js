const brandSelector = document.getElementById("brand-selector");
const typeSelector = document.getElementById("type-selector");
const speedMin = document.getElementById("speedMin");
const speedMax = document.getElementById("speedMax");
const latencyMin = document.getElementById("latencyMin");
const latencyMax = document.getElementById("latencyMax");
const capacityMin = document.getElementById("capacityMin");
const capacityMax = document.getElementById("capacityMax");
const priceMin = document.getElementById("priceMin");
const priceMax = document.getElementById("priceMax");

// actualizar URL con filtros seleccionados
function updateURL() {
    const params = new URLSearchParams();

    if (brandSelector.value !== "Seleccione...") params.set("brand", brandSelector.value);
    if (typeSelector.value !== "Seleccione...") params.set("type", typeSelector.value);
    if (speedMin.value) params.set("speed_min", speedMin.value);
    if (speedMax.value) params.set("speed_max", speedMax.value);
    if (latencyMin.value) params.set("latency_min", latencyMin.value);
    if (latencyMax.value) params.set("latency_max", latencyMax.value);
    if (capacityMin.value) params.set("capacity_min", capacityMin.value);
    if (capacityMax.value) params.set("capacity_max", capacityMax.value);
    if (priceMin.value) params.set("price_min", priceMin.value);
    if (priceMax.value) params.set("price_max", priceMax.value);

    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.location.href = newUrl;
}

function restoreFilters() {
    const params = new URLSearchParams(window.location.search);

    if (params.get("brand")) brandSelector.value = params.get("brand");
    if (params.get("type")) typeSelector.value = params.get("type");
    if (params.get("speed_min")) speedMin.value = params.get("speed_min");
    if (params.get("speed_max")) speedMax.value = params.get("speed_max");
    if (params.get("latency_min")) latencyMin.value = params.get("latency_min");
    if (params.get("latency_max")) latencyMax.value = params.get("latency_max");
    if (params.get("capacity_min")) capacityMin.value = params.get("capacity_min");
    if (params.get("capacity_max")) capacityMax.value = params.get("capacity_max");
    if (params.get("price_min")) priceMin.value = params.get("price_min");
    if (params.get("price_max")) priceMax.value = params.get("price_max");
}

[brandSelector, typeSelector, speedMin, speedMax, latencyMin, latencyMax, capacityMin, capacityMax, priceMin, priceMax]
    .forEach(el => el.addEventListener("change", updateURL));

document.addEventListener("DOMContentLoaded", () => {
    restoreFilters();
});