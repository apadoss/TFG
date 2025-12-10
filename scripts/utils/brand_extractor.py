"""
Utilidades para extracción de marcas y fabricantes.
Compartido entre todos los scrapers.
"""

from typing import Dict, List

# Patrones de marcas por categoría
BRAND_PATTERNS: Dict[str, Dict[str, List[str]]] = {
    "procesadores": {
        "Intel": ["intel", "core"],
        "AMD": ["amd", "ryzen"]
    },
    "tarjetas-graficas": {
        "NVIDIA": ["nvidia", "geforce", "rtx", "gtx"],
        "AMD": ["amd", "radeon", "rx"],
        "Intel": ["intel", "arc"]
    },
    "placas-base": {
        "ASRock": ["asrock"],
        "MSI": ["msi"],
        "ASUS": ["asus"],
        "Gigabyte": ["gigabyte"],
        "Biostar": ["biostar"]
    },
    "discos-hdd": {
        "Seagate": ["seagate"],
        "Western Digital": ["western digital", "wd"],
        "Toshiba": ["toshiba"],
        "Dell": ["dell"],
        "Synology": ["synology"],
        "HP": ["hp"],
        "Philips": ["philips"]
    },
    "discos-ssd": {
        "Samsung": ["samsung"],
        "Crucial": ["crucial"],
        "Kingston": ["kingston"],
        "Western Digital": ["western digital", "wd"],
        "Corsair": ["corsair"],
        "SanDisk": ["sandisk"],
        "Gigabyte": ["gigabyte"],
        "MSI": ["msi"],
        "Seagate": ["seagate"],
        "Lexar": ["lexar"],
        "Adata": ["adata"],
        "Acer": ["acer"],
        "HP": ["hp"],
        "Synology": ["synology"],
        "Emtec": ["emtec"],
        "PNY": ["pny"],
    },
    "memorias-ram": {
        "Corsair": ["corsair"],
        "G.Skill": ["g.skill", "gskill"],
        "Kingston": ["kingston"],
        "Crucial": ["crucial"],
        "MSI": ["msi"],
        "Gigabyte": ["gigabyte"],
        "ASUS": ["asus"],
        "HyperX": ["hyperx"],
        "Samsung": ["samsung"],
        "Lexar": ["lexar"],
        "Adata": ["adata"],
        "Acer": ["acer"],
        "Team Group": ["team group", "teamgroup"],
        "Silicon Power": ["silicon power"],
        "Synology": ["synology"],
        "Apacer": ["apacer"],
        "Dell": ["dell"],
        "Goodram": ["goodram"],
        "Patriot": ["patriot"],
    },
    "fuentes-alimentacion": {
        "Corsair": ["corsair"],
        "Cooler Master": ["cooler master"],
        "Seasonic": ["seasonic"],
        "NZXT": ["nzxt"],
        "MSI": ["msi"],
        "ASUS": ["asus"],
        "Gigabyte": ["gigabyte"]
    }
}

# Fabricantes de tarjetas gráficas
GRAPHICS_CARD_MANUFACTURERS = {
    "asus": "Asus",
    "msi": "MSI",
    "gigabyte": "Gigabyte",
    "zotac": "Zotac",
    "evga": "EVGA",
    "sapphire": "Sapphire",
    "xfx": "XFX",
    "powercolor": "PowerColor",
    "asrock": "ASRock",
    "palit": "Palit",
    "inno3d": "INNO3D",
    "pny": "PNY",
    "gainward": "Gainward",
    "kfa2": "KFA2",
    "galax": "GALAX"
}


def extract_brand(name: str, category: str) -> str:
    """
    Extrae la marca de un producto basándose en su nombre.
    
    Args:
        name: Nombre del producto
        category: Categoría del producto
    
    Returns:
        Nombre de la marca o "unknown" si no se encuentra
    """
    if category not in BRAND_PATTERNS:
        return "unknown"
    
    name_lower = name.lower()
    
    for brand, keywords in BRAND_PATTERNS[category].items():
        if any(keyword in name_lower for keyword in keywords):
            return brand
    
    return "unknown"


def extract_manufacturer(name: str) -> str:
    """
    Extrae el fabricante de una tarjeta gráfica.
    
    Args:
        name: Nombre de la tarjeta gráfica
    
    Returns:
        Nombre del fabricante o "unknown" si no se encuentra
    """
    name_lower = name.lower()
    
    for keyword, manufacturer in GRAPHICS_CARD_MANUFACTURERS.items():
        if keyword in name_lower:
            return manufacturer
    
    return "unknown"


def add_brand(category: str, brand_name: str, keywords: List[str]):
    """
    Añade una nueva marca al diccionario de patrones.
    
    Args:
        category: Categoría del producto
        brand_name: Nombre de la marca
        keywords: Lista de palabras clave para identificar la marca
    """
    if category not in BRAND_PATTERNS:
        BRAND_PATTERNS[category] = {}
    
    BRAND_PATTERNS[category][brand_name] = keywords


def add_manufacturer(keyword: str, manufacturer_name: str):
    """
    Añade un nuevo fabricante de tarjetas gráficas.
    
    Args:
        keyword: Palabra clave para identificar el fabricante
        manufacturer_name: Nombre del fabricante
    """
    GRAPHICS_CARD_MANUFACTURERS[keyword.lower()] = manufacturer_name