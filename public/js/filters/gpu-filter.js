const brandSelector = document.getElementById("brand-selector");
const vramMin = document.getElementById("vramRangeMin");
const vramMax = document.getElementById("vramRangeMax");
const memTypeSelector = document.getElementById("mem-type-selector");
const interfaceSelector = document.getElementById("interface-selector");
const priceMin = document.getElementById("priceMin");
const priceMax = document.getElementById("priceMax");

// actualizar URL con filtros seleccionados
function updateURL() {
    const params = new URLSearchParams();

    if (brandSelector.value !== "Seleccione...") params.set("brand", brandSelector.value);
    if (vramMin.value) params.set("vram_min", vramMin.value);
    if (vramMax.value) params.set("vram_max", vramMax.value);
    if (memTypeSelector.value !== "Seleccione...") params.set("mem_type", memTypeSelector.value);
    if (interfaceSelector.value !== "Seleccione...") params.set("interface", interfaceSelector.value);
    if (priceMin.value) params.set("price_min", priceMin.value);
    if (priceMax.value) params.set("price_max", priceMax.value);

    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.location.href = newUrl;
}

function restoreFilters() {
    const params = new URLSearchParams(window.location.search);

    if (params.get("brand")) brandSelector.value = params.get("brand");
    if (params.get("vram_min")) vramMin.value = params.get("vram_min");
    if (params.get("vram_max")) vramMax.value = params.get("vram_max");
    if (params.get("mem_type")) memTypeSelector.value = params.get("mem_type");
    if (params.get("interface")) interfaceSelector.value = params.get("interface");
    if (params.get("price_min")) priceMin.value = params.get("price_min");
    if (params.get("price_max")) priceMax.value = params.get("price_max");
}

[brandSelector, vramMin, vramMax, memTypeSelector, interfaceSelector, priceMin, priceMax]
    .forEach(el => el.addEventListener("change", updateURL));

document.addEventListener("DOMContentLoaded", () => {
    restoreFilters();
});

