<label for="brand-selector">Marca:</label>
<select id="brand-selector" class="form-select">
    <option selected>Seleccione...</option>
    <option value="Western Digital">Western Digital</option>
    <option value="Samsung">Samsung</option>
    <option value="Seagate">Seagate</option>
    <option value="Kingston">Kingston</option>
    <option value="Toshiba">Toshiba</option>
</select>

<label for="type-selector">Tipo:</label>
<select id="type-selector" class="form-select">
    <option selected>Seleccione...</option>
    <option value="HDD">HDD</option>
    <option value="SSD">SSD</option>
</select>

<p class="mb-2">Capacidad (TB):</p>
<div class="d-flex align-items-center gap-2">
    <input 
        id="capacityMin" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Mín" 
        min="0">
    <span class="text-muted">-</span>
    <input 
        id="capacityMax" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Máx" 
        min="0">
</div>

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

<script src="{{ asset('js/filters/storage-filter.js') }}"></script>