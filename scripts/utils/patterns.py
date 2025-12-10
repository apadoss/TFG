"""
Patrones de búsqueda para extracción de especificaciones.
Compartido entre todos los scrapers y extractores.
"""

# Patrones de búsqueda por categoría
SEARCH_PATTERNS = {
    "procesadores": {
        "n_cores": [
            r"(?:n.° de núcleos|núcleos de cpu|núcleos|cores|cantidad de núcleos|número de núcleos|\# de núcleos|processor cores)[^\d]*(\d+)",
            r"(?:núcleos|cores)(?:[^\d]*)(\d+)",
            r"[^\d](\d+)(?:\s*)(?:núcleos|cores)",
        ],
        "n_threads": [
            r"(?:hilos|threads|subprocesos)[^\d]*(\d+)",
            r"[^\d](\d+)(?:\s*)(?:hilos|threads)",
            r"n.° de subprocesos[^\d]*(\d+)"
        ],
        "socket": [
            r"(?:socket|zócalo)[^\w]*(am\d|lga\d{3,4})",
            r"socket[^\w]*(am\d)",
            r"socket[^\w]*(lga\d{3,4})"
        ],
        "tdp": [
            r"(?:tdp|potencia de diseño térmico|thermal design power)[^\d]*(\d+)(?:\s*)(?:w|watts)",
            r"consumo de energía[^\d]*(\d+)(?:\s*)(?:w)",
            r"potencia base[^\d]*(\d+)(?:\s*)(?:w)"
        ],
        "clock_speed": [
            r"(?:velocidad del reloj|frecuencia|reloj base|clock speed|base clock|frecuencia base)[^\d]*(\d+(?:\.\d+)?)(?:\s*)(?:ghz)",
        ],
    },
    "tarjetas-graficas": {
        "vram": [
            r"(?:memoria|vram|gddr\d+)[^\d]*(\d+)(?:\s*)(?:gb)",
            r"(\d+)(?:\s*)(?:gb)(?:\s*)(?:gddr\d+)",
        ],
        "mem_type": [
            r"(?:gddr\d+(?:[x])?)",
            r"(?:hbm\d+)"
        ],
        "tdp": [
            r"(?:tdp|consumo|potencia)[^\d]*(\d+)(?:\s*)(?:w|watts)",
        ],
    },
    "placas-base": {
        "socket": [
            r"(?:socket|zócalo)[^\w]*(am\d|lga\d{3,4})",
        ],
        "chipset": [
            r"Chipset\s*(?:AMD|Intel)?\s*([A-Z]\d{3,4}[A-Z]?)",
        ],
        "form_factor": [
            r"Formato\s*:?\s*((?:Micro|Mini|E)[ -]?ATX|Mini[ -]?ITX|DTX|ATX)",
        ],
    },
    "discos-hdd": {
        "storage": [
            r"(?:capacidad|almacenamiento)[^\d]*(\d+(?:\.\d+)?)\s*(tb|gb)",
            r"(\d+(?:\.\d+)?)\s*(tb|gb)(?:\s+hdd)?",
            r"disco duro[^\d]*(\d+(?:\.\d+)?)\s*(tb|gb)"
        ]
    },
    "discos-ssd": {
        "storage": [
            r"(?:capacidad|almacenamiento)[^\d]*(\d+(?:\.\d+)?)\s*(tb|gb)",
            r"(\d+(?:\.\d+)?)\s*(tb|gb)(?:\s+ssd)?",
            r"ssd[^\d]*(\d+(?:\.\d+)?)\s*(tb|gb)"
        ]
    },
    "memorias-ram": {
        "type": [
            r"\b(ddr[345]x?)\b",
            r"memoria\s+(ddr[345]x?)"
        ],
        "n_modules": [
            r"(\d+)\s*x\s*\d+gb",
            r"kit\s+de\s+(\d+)",
            r"(\d+)\s*módulos?"
        ],
        "module_capacity": [
            r"\d+\s*x\s*(\d+)gb",
            r"módulos?\s+de\s+(\d+)gb"
        ],
        "frequency": [
            r"(\d{4,5})\s*mhz",
            r"frecuencia[^\d]*(\d{4,5})\s*mhz",
            r"@\s*(\d{4,5})\s*mhz"
        ],
        "latency": [
            r"\bcl\s*(\d+)\b",
            r"cas\s*latency\s*(\d+)",
            r"latencia\s*(\d+)"
        ]
    },
    "fuentes-alimentacion": {
        "power": [
            r"(?:Potencia total|Vatios|Potencia)\s*-?\s*(\d{3,4})\s*W",
            r"\b(\d{3,4})\s*[Ww]att?s?\b"
        ],
        "certification": [
            r"80\s*\+?\s*(?:PLUS|Plus)\s+(Bronze|Silver|Gold|Platinum|Titanium)",
        ],
    }
}


# Sockets conocidos para fallback
KNOWN_SOCKETS = {
    "AM4": ["am4"],
    "AM5": ["am5"],
    "LGA1700": ["lga1700", "lga 1700"],
    "LGA1851": ["lga1851", "lga 1851"],
    "LGA1200": ["lga1200", "lga 1200"],
    "LGA1151": ["lga1151", "lga 1151"],
    "TR4": ["tr4", "threadripper"],
    "sTRX4": ["strx4", "threadripper"]
}


def add_pattern(category: str, field: str, pattern: str):
    """
    Añade un nuevo patrón de búsqueda.
    
    Args:
        category: Categoría del producto
        field: Campo a extraer
        pattern: Expresión regular
    """
    if category not in SEARCH_PATTERNS:
        SEARCH_PATTERNS[category] = {}
    
    if field not in SEARCH_PATTERNS[category]:
        SEARCH_PATTERNS[category][field] = []
    
    SEARCH_PATTERNS[category][field].append(pattern)


def get_patterns(category: str, field: str = None):
    """
    Obtiene los patrones para una categoría y campo específico.
    
    Args:
        category: Categoría del producto
        field: Campo específico (opcional)
    
    Returns:
        Lista de patrones o diccionario completo de la categoría
    """
    if category not in SEARCH_PATTERNS:
        return [] if field else {}
    
    if field:
        return SEARCH_PATTERNS[category].get(field, [])
    
    return SEARCH_PATTERNS[category]