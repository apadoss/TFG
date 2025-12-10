document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('priceHistoryModal');
    
    if (!modal) return;
    
    let monthlyData = {};
    let isDataLoaded = false;
    let isLoading = false;
    
    // Colores por tienda
    const vendorColors = {
        'pccomponentes': {
            border: 'rgb(75, 192, 192)',
            background: 'rgba(75, 192, 192, 0.2)'
        },
        'coolmod': {
            border: 'rgb(255, 99, 132)',
            background: 'rgba(255, 99, 132, 0.2)'
        },
        'amazon': {
            border: 'rgb(255, 205, 86)',
            background: 'rgba(255, 205, 86, 0.2)'
        },
        'neobyte': {
            border: 'rgb(153, 102, 255)',
            background: 'rgba(153, 102, 255, 0.2)'
        }
    };
    
    modal.addEventListener('show.bs.modal', async function (event) {
        const button = event.relatedTarget;
        
        const componentType = button.getAttribute('data-component-type');
        const componentId = button.getAttribute('data-component-id');
        
        if (!componentType || !componentId) {
            console.error('Faltan datos del componente');
            return;
        }

        if (!isDataLoaded && !isLoading) {
            isLoading = true;
            const chartContainer = document.getElementById('priceChart') 
                ? document.getElementById('priceChart').parentElement 
                : document.getElementById('chartContainer');
            
            chartContainer.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
            
            try {
                console.log(`/api/price-history?component_type=${encodeURIComponent(componentType)}&component_id=${componentId}`);
                const response = await fetch(`/api/v1/price-history?component_type=${encodeURIComponent(componentType)}&component_id=${componentId}`);
                
                if (!response.ok) {
                    throw new Error('Error al cargar los datos');
                }
                
                const rawData = await response.json();
                
                // Verificar si hay datos
                if (Object.keys(rawData).length === 0) {
                    chartContainer.innerHTML = '<p class="text-center text-muted my-4">No hay historial de precios disponible para este componente.</p>';
                    return;
                }
                
                // Transformar los datos al formato esperado por Chart.js
                monthlyData = transformData(rawData);
                isDataLoaded = true;
                
                chartContainer.innerHTML = '<canvas id="priceChart"></canvas>';
                
                // Inicializar gráfica
                initializeChart();
            } catch (error) {
                console.error('Error cargando datos:', error);
                chartContainer.innerHTML = '<div class="alert alert-danger" role="alert">Error al cargar el historial de precios. Por favor, inténtalo de nuevo.</div>';
            } finally {
                isLoading = false;
            }
        } else if (isDataLoaded) {
            initializeChart();
        }
    });
    
    function transformData(rawData) {
        const transformed = {};
        const monthNames = {
            'Enero': 'Ene', 'Febrero': 'Feb', 'Marzo': 'Mar', 'Abril': 'Abr',
            'Mayo': 'May', 'Junio': 'Jun', 'Julio': 'Jul', 'Agosto': 'Ago',
            'Septiembre': 'Sep', 'Octubre': 'Oct', 'Noviembre': 'Nov', 'Diciembre': 'Dic'
        };
        
        for (const [monthYear, vendorData] of Object.entries(rawData)) {
            const [month, year] = monthYear.split(' ');
            const shortMonth = monthNames[month];
            
            transformed[monthYear] = {
                labels: [],
                datasets: {}
            };
            
            const allDays = new Set();
            for (const [vendor, prices] of Object.entries(vendorData)) {
                prices.forEach(record => allDays.add(record.day));
            }
            
            const sortedDays = Array.from(allDays).sort((a, b) => a - b);
            transformed[monthYear].labels = sortedDays.map(day => `${day} ${shortMonth}`);
            
            // Procesar datos por vendor
            for (const [vendor, prices] of Object.entries(vendorData)) {
                const dayMap = {};
                prices.forEach(record => {
                    if (!dayMap[record.day]) {
                        dayMap[record.day] = [];
                    }
                    dayMap[record.day].push(parseFloat(record.price));
                });
                
                // Crear array de precios para cada día
                const priceArray = sortedDays.map(day => {
                    if (dayMap[day]) {
                        const dayPrices = dayMap[day];
                        return dayPrices.reduce((sum, p) => sum + p, 0) / dayPrices.length;
                    }
                    return null;
                });
                
                transformed[monthYear].datasets[vendor] = priceArray;
            }
        }
        
        return transformed;
    }
    
    function initializeChart() {
        const ctx = document.getElementById('priceChart');
        if (!ctx) return;
        
        const chartContainer = ctx.parentElement;
        
        if (Object.keys(monthlyData).length === 0) {
            chartContainer.innerHTML = '<p class="text-center text-muted my-4">No hay datos de historial disponibles</p>';
            return;
        }
        
        // Selector de mes
        let monthSelector = document.getElementById('monthSelector');
        if (!monthSelector) {
            monthSelector = document.createElement('select');
            monthSelector.id = 'monthSelector';
            monthSelector.className = 'form-select mb-3';
            monthSelector.style.maxWidth = '200px';
            
            const sortedMonths = Object.keys(monthlyData).sort((a, b) => {
                const [monthA, yearA] = a.split(' ');
                const [monthB, yearB] = b.split(' ');
                const monthOrder = {
                    'Enero': 1, 'Febrero': 2, 'Marzo': 3, 'Abril': 4,
                    'Mayo': 5, 'Junio': 6, 'Julio': 7, 'Agosto': 8,
                    'Septiembre': 9, 'Octubre': 10, 'Noviembre': 11, 'Diciembre': 12
                };
                
                if (yearA !== yearB) {
                    return parseInt(yearA) - parseInt(yearB);
                }
                return monthOrder[monthA] - monthOrder[monthB];
            });
            
            sortedMonths.forEach(month => {
                const option = document.createElement('option');
                option.value = month;
                option.textContent = month;
                monthSelector.appendChild(option);
            });
            
            chartContainer.insertBefore(monthSelector, ctx);
            
            // Seleccionar el mes más reciente por defecto
            monthSelector.value = sortedMonths[sortedMonths.length - 1];
        }
        
        function updateChart(selectedMonth) {
            const monthData = monthlyData[selectedMonth];
            
            if (!monthData) return;
            
            if (window.priceChartInstance) {
                window.priceChartInstance.destroy();
            }
            
            // Crear datasets para Chart.js
            const datasets = [];
            for (const [vendor, prices] of Object.entries(monthData.datasets)) {
                const colors = vendorColors[vendor] || {
                    border: `hsl(${Math.random() * 360}, 70%, 50%)`,
                    background: `hsla(${Math.random() * 360}, 70%, 50%, 0.2)`
                };
                
                datasets.push({
                    label: vendor,
                    data: prices,
                    borderColor: colors.border,
                    backgroundColor: colors.background,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    spanGaps: true
                });
            }
            
            // Calcular min y max para el eje Y
            const allPrices = datasets.flatMap(d => d.data.filter(p => p !== null));
            const minPrice = Math.min(...allPrices);
            const maxPrice = Math.max(...allPrices);
            
            let padding;
            if (minPrice === maxPrice) {
                padding = maxPrice * 0.1;
            } else {
                padding = (maxPrice - minPrice) * 0.1;
            }
            
            window.priceChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthData.labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'right',
                            onClick: function(e, legendItem, legend) {
                                const index = legendItem.datasetIndex;
                                const chart = legend.chart;
                                const meta = chart.getDatasetMeta(index);
                                
                                meta.hidden = meta.hidden === null ? !chart.data.datasets[index].hidden : null;
                                
                                chart.update();
                            },
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (context.parsed.y === null) return null;
                                    return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + '€';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: Math.max(0, minPrice - padding),
                            max: maxPrice + padding,
                            ticks: {
                                callback: function(value) {
                                    return value.toFixed(0) + '€';
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxTicksLimit: 15,
                                autoSkip: true
                            }
                        }
                    }
                }
            });
        }
        
        updateChart(monthSelector.value);
        
        // Listener para cambio de mes
        monthSelector.removeEventListener('change', handleMonthChange);
        monthSelector.addEventListener('change', handleMonthChange);
        
        function handleMonthChange() {
            updateChart(this.value);
        }
    }
});