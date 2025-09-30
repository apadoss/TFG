const nomenclatureInfo = {
    intel: {
        letters: {
            "F": "Procesador sin gráficos integrados",
            "K": "Desbloquedo para overclocking",
            "T": "Procesador de bajo consumo",
            "S": "Edición especial"
        },
        levels:{
            "i3": "Gama de entrada",
            "i5": "Gama media",
            "i7": "Gama alta",
            "i9": "Entusiasta"
        }
    },
    amd: {
        letters: {
            "X": "Alto rendimiento",
            "X3D": "Procesador con tecnología de caché L3 apilada. Reduce latencia y es ideal para juegos.",
            "G": "Procesador con gráficos integrados",
        },
        levels:{
            "Ryzen 3": "Gama de entrada",
            "Ryzen 5": "Gama media",
            "Ryzen 7": "Gama alta",
            "Ryzen 9": "Entusiasta"
        }
    },
    nvidia: {
        letters: {
            "Ti": "Modelo mejorado",
            "Super": "Modelo revisado/intermedio",
        },
        levels: {
            "50": "Gama de entrada",
            "60": "Gama media",
            "70": "Gama alta",
            "80": "Gama entusiasta",
            "90": "Tope de gama"
        }
    }  
}

document.addEventListener('DOMContentLoaded', () => {
    const icons = document.querySelectorAll('.nomenclature-info');

    icons.forEach(icon => {
        const brand = icon.dataset.brand.toLowerCase();
        const model = icon.dataset.model;
        let infoText = "Información no disponible";

        if (nomenclatureInfo[brand]) {
            const { letters, levels } = nomenclatureInfo[brand];

            // Detectar letras
            let letterMatches = [];
            Object.keys(letters).forEach(letter => {
                if (model.includes(letter)) {
                    letterMatches.push(`${letter}: ${letters[letter]}`);
                }
            });

            // Detectar nivel
            let levelMatch = null;
            if (brand === "nvidia") {
                const match = model.match(/(\d{3,4})/);
                if (match) {
                    const lastTwo = match[1].slice(-2);
                    if (levels[lastTwo]) {
                        levelMatch = `${match[1]} → ${levels[lastTwo]}`;
                    }
                }
            } else {
                Object.keys(levels).forEach(level => {
                    if (model.includes(level)) {
                        levelMatch = `${level} → ${levels[level]}`;
                    }
                });
            }

            // Texto final
            infoText = `
                <strong>Modelo:</strong> ${model}<br>
                ${levelMatch ? `<strong>Nivel:</strong> ${levelMatch}<br>` : ""}
                ${letterMatches.length ? `<strong>Letras:</strong> ${letterMatches.join(", ")}` : ""}
            `;
        }

        icon.setAttribute("data-bs-toggle", "tooltip");
        icon.setAttribute("data-bs-html", "true");
        icon.setAttribute("title", infoText);
        icon.setAttribute("data-bs-placement", "bottom");

        new bootstrap.Tooltip(icon);
    });
});