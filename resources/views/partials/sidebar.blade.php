<nav class="sidebar" style="margin-top: 70px; height: calc(100vh - 70px); overflow-y: auto;">
    <div class="p-4">
        <!-- Componentes -->
        <div class="accordion" id="componentesAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="componentesHeading">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#componentesCollapse" aria-expanded="true" aria-controls="componentesCollapse">
                        Componentes
                    </button>
                </h2>
                <div id="componentesCollapse" class="accordion-collapse collapse show" aria-labelledby="componentesHeading" data-bs-parent="#componentesAccordion">
                    <div class="accordion-body p-0">
                        <div class="list-group">
                            <a href="/componentes/procesadores" class="list-group-item list-group-item-action text-dark">
                                <i class="bi bi-cpu-fill me-2" style="font-size: 1.5rem;"></i> Procesadores
                            </a>
                            <a href="/componentes/tarjetas-graficas" class="list-group-item list-group-item-action text-dark">
                                <i class="bi bi-gpu-card me-2" style="font-size: 1.5rem;"></i>Tarjetas Gráficas
                            </a>
                            <a href="/componentes/placas-base" class="list-group-item list-group-item-action text-dark">
                                <i class="bi bi-motherboard-fill me-2" style="font-size: 1.5rem;"></i>Placas Base
                            </a>
                            <a href="/componentes/almacenamiento" class="list-group-item list-group-item-action text-dark">
                                <i class="bi bi-hdd-stack-fill me-2" style="font-size: 1.5rem;"></i>Almacenamiento
                            </a>
                            <a href="/componentes/ram" class="list-group-item list-group-item-action text-dark">
                                <i class="bi bi-memory me-2" style="font-size: 1.5rem;"></i>Memorias RAM
                            </a>
                            <a href="/componentes/fuentes-alimentacion" class="list-group-item list-group-item-action text-dark">
                              Fuentes de Alimentación
                            </a>
                            <a href="/componentes/portatiles" class="list-group-item list-group-item-action text-dark">
                                <i class="bi bi-laptop me-2" style="font-size: 1.5rem;"></i>Portátiles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="accordion mt-3" id="filtrosAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="filtrosHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosCollapse" aria-expanded="false" aria-controls="filtrosCollapse">
                        Filtros
                    </button>
                </h2>
                <div id="filtrosCollapse" class="accordion-collapse collapse" aria-labelledby="filtrosHeading" data-bs-parent="#filtrosAccordion">
                    <div class="accordion-body">
                        <div class="list-group">
                            <label for="brand-selector">Marca:</label>
                            <select id="brand-selector" class="form-select">
                                <option selected>Seleccione...</option>
                                <option value="1">Intel</option>
                                <option value="2">AMD</option>
                            </select>

                            <label for="socket-selector">Socket:</label>
                            <select id="socket-selector" class="form-select">
                                <option selected>Seleccione...</option>
                                <option value="1">LGA 1700</option>
                                <option value="2">FCLGA1851</option>
                                <option value="3">AM4</option>
                                <option value="4">AM5</option>
                            </select>

                            <p>Nº núcleos</p>
                            <div id="coreRange" class="mb-2"></div>
                            <p>
                                <span id="coreRangeMin"></span> - <span id="coreRangeMax"></span>
                            </p>

                            <div class="form-check">
                                <label class="form-check-label">
                                     <input type="checkbox" class="form-check-input" name="" id="" value="checkedValue"> Gráficos Integrados
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>