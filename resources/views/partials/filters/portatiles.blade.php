<label for="brand-selector">Marca:</label>
<select id="brand-selector" class="form-select">
    <option selected>Seleccione...</option>
    <option value="1">Dell</option>
    <option value="2">HP</option>
    <option value="3">Lenovo</option>
    <option value="4">Acer</option>
    <option value="5">Asus</option>
</select>

<p class="mb-2">CPU:</p>
<select name="cpu" id="cpu" class="form-select">
    <option selected>Seleccione...</option>
    <option value="1">Intel Core i3</option>
    <option value="2">Intel Core i5</option>
    <option value="3">Intel Core i7</option>
    <option value="4">Intel Core i9</option>
    <option value="5">AMD Ryzen 5</option>
    <option value="6">AMD Ryzen 7</option>
    <option value="7">AMD Ryzen 9</option>
</select>

<p class="mb-2">RAM (GB):</p>
<div class="d-flex align-items-center gap-2">
    <input 
        id="ramMin" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Mín" 
        min="0">
    <span class="text-muted">-</span>
    <input 
        id="ramMax" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Máx" 
        min="0">
</div>

<p class="mb-2">Almacenamiento (TB):</p>
<div class="d-flex align-items-center gap-2">
    <input 
        id="storageMin" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Mín" 
        min="0">
    <span class="text-muted">-</span>
    <input 
        id="storageMax" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Máx" 
        min="0">
</div>

<p class="mb-2">GPU:</p>
<select name="gpu" id="gpu" class="form-select">
    <option selected>Seleccione...</option>
    <option value="1">NVIDIA</option>
    <option value="2">AMD</option>
</select>

<p class="mb-2">Vida batería (horas):</p>
<div class="d-flex align-items-center gap-2">
    <input 
        id="batteryLifeMin" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Mín"                
        min="0">
    <span class="text-muted">-</span>
    <input 
        id="batteryLifeMax" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Máx" 
        min="0">
</div>

<p class="mb-2">Peso (kg):</p>
<div class="d-flex align-items-center gap-2">
    <input 
        id="weightMin" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Mín" 
        min="0">
    <span class="text-muted">-</span>
    <input 
        id="weightMax" 
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