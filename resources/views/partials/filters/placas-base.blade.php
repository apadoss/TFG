<label for="brand-selector">Marca:</label>
<select id="brand-selector" class="form-select">
    <option selected>Seleccione...</option>
    <option value="1">Asus</option>
    <option value="2">MSI</option>
    <option value="3">Gigabyte</option>
</select>

<label for="socket-selector" class="mb-2">Socket:</label>
<select id="socket-selector" class="form-select">
    <option selected>Seleccione...</option>
    <option value="1">LGA 1700</option>
    <option value="2">FCLGA1851</option>
    <option value="3">AM4</option>
    <option value="4">AM5</option>
</select>

<label for="form-factor-selector" class="mb-2">Factor de forma:</label>
<select id="form-factor-selector" class="form-select">
    <option selected>Seleccione...</option>
    <option value="1">ATX</option>
    <option value="2">Micro ATX</option>
    <option value="3">Mini ITX</option>
    <option value="4">Extended ATX</option>
</select>

<label for="chipset-selector" class="mb-2">Chipset:</label>
<select id="chipset-selector" class="form-select">
    <option selected>Seleccione...</option>
    <option value="1">AMD B550</option>
    <option value="2">Intel Z790</option>
    <option value="3">AMD B650</option>
    <option value="4">Intel B760</option>
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