<label for="brand-selector">Marca:</label>
<select id="brand-selector" class="form-select">
    <option selected>Seleccione...</option>
    <option value="Corsair">Corsair</option>
    <option value="Seasonic">Seasonic</option>
    <option value="MSI">MSI</option>
    <option value="EVGA">EVGA</option>
    <option value="Asus">Asus</option>
    <option value="Cooler Master">Cooler Master</option>
</select>

<p class="mb-2">Potencia (W):</p>
<div class="d-flex align-items-center gap-2">
    <input
        id="powerMin" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Mín" 
        min="0">
    <span class="text-muted">-</span>
    <input 
        id="powerMax" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Máx" 
        min="0">
</div>

<p class="mb-2">Certificación:</p>
<select name="certification" id="certification" class="form-select">
    <option selected>Seleccione...</option>
    <option value="1">80 Plus Standard</option>
    <option value="2">80 Plus Bronze</option>
    <option value="3">80 Plus Silver</option>
    <option value="4">80 Plus Gold</option>
    <option value="5">80 Plus Platinum</option>
    <option value="6">80 Plus Titanium</option>
</select>

<p class="mb-2">Precio:</p>
<div class="d-flex align-items-center gap-2">
    <input 
        id="priceMin" 
        type="number"                
        class="form-control form-control-sm" 
        placeholder="Mín" 
        min="0">
    <span class="text-muted">-</span>
    <input 
        id="priceMax" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Máx" 
        min="0">
</div>

<script src="{{ asset('js/filters/power-supply-filter.js') }}"></script>