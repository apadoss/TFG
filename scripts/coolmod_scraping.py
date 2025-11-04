from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, WebDriverException
import time
import json
from bs4 import BeautifulSoup
import re
import mysql.connector
from mysql.connector import Error as MySQLError
import datetime
import logging
from pathlib import Path
import os
from dotenv import load_dotenv
from typing import Dict, List, Optional, Any
from contextlib import contextmanager
from dataclasses import dataclass, asdict
from abc import ABC, abstractmethod


# ============================================================================
# CONFIGURACIÓN Y PATRONES
# ============================================================================

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

BRAND_PATTERNS = {
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


# ============================================================================
# CONFIGURACIÓN DE LOGGING
# ============================================================================

def setup_logging(log_file: str = "scraper.log") -> logging.Logger:
    """Configura el sistema de logging."""
    logger = logging.getLogger(__name__)
    logger.setLevel(logging.INFO)
    
    # Evitar duplicados
    if logger.handlers:
        return logger
    
    formatter = logging.Formatter(
        "%(asctime)s - %(levelname)s - [%(funcName)s] - %(message)s"
    )
    
    file_handler = logging.FileHandler(log_file)
    file_handler.setFormatter(formatter)
    
    console_handler = logging.StreamHandler()
    console_handler.setFormatter(formatter)
    
    logger.addHandler(file_handler)
    logger.addHandler(console_handler)
    
    return logger


logger = setup_logging()


# ============================================================================
# EXCEPCIONES PERSONALIZADAS
# ============================================================================

class ScraperError(Exception):
    """Excepción base para errores del scraper."""
    pass


class DriverSetupError(ScraperError):
    """Error al configurar el driver de Selenium."""
    pass


class DatabaseConnectionError(ScraperError):
    """Error al conectar con la base de datos."""
    pass


# ============================================================================
# DATACLASSES
# ============================================================================

@dataclass
class ProductData:
    """Datos básicos de un producto."""
    name: str
    url: str
    price: str
    vendor: str
    brand: str
    in_stock: str
    image_url: str
    category: str


# ============================================================================
# GESTORES DE CONTEXTO
# ============================================================================

@contextmanager
def selenium_driver(options: Options):
    """Context manager para el driver de Selenium."""
    driver = None
    try:
        driver = webdriver.Chrome(options=options)
        yield driver
    except WebDriverException as e:
        raise DriverSetupError(f"Error al inicializar el driver: {e}")
    finally:
        if driver:
            driver.quit()


@contextmanager
def database_connection(config: Dict[str, Any]):
    """Context manager para la conexión a MySQL."""
    connection = None
    cursor = None
    try:
        connection = mysql.connector.connect(**config)
        cursor = connection.cursor()
        yield connection, cursor
        connection.commit()
    except MySQLError as e:
        if connection:
            connection.rollback()
        raise DatabaseConnectionError(f"Error de base de datos: {e}")
    finally:
        if cursor:
            cursor.close()
        if connection:
            connection.close()


# ============================================================================
# EXTRACTOR BASE
# ============================================================================

class BaseExtractor(ABC):
    """Clase base para extractores de datos de productos."""
    
    def __init__(self, patterns: Dict[str, List[str]]):
        self.patterns = patterns
    
    @abstractmethod
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        """Extrae detalles específicos del producto."""
        pass
    
    def _extract_with_patterns(
        self, 
        specs_text: str, 
        key: str, 
        default: str = ""
    ) -> str:
        """Extrae un valor usando los patrones definidos."""
        if key not in self.patterns:
            return default
        
        for pattern in self.patterns[key]:
            match = re.search(pattern, specs_text, re.IGNORECASE)
            if match:
                return match.group(1) if match.lastindex else match.group(0)
        
        return default


class CPUExtractor(BaseExtractor):
    """Extractor de datos para procesadores."""
    
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        details = {
            "n_cores": "0",
            "n_threads": "0",
            "socket": "",
            "tdp": "0",
            "clock_speed": ""
        }
        
        # Extraer con patrones
        for key in details.keys():
            value = self._extract_with_patterns(specs_text, key, details[key])
            details[key] = value
        
        # Fallbacks para socket
        if not details["socket"]:
            details["socket"] = self._fallback_socket(specs_text)
        
        # Fallback para clock_speed
        if not details["clock_speed"]:
            clock_matches = re.findall(r"(\d+(?:\.\d+)?)\s*GHz", specs_text, re.IGNORECASE)
            if clock_matches:
                details["clock_speed"] = clock_matches[0]
        
        logger.info(f"CPU {name} - Extraídos: {details}")
        return details
    
    def _fallback_socket(self, text: str) -> str:
        """Búsqueda de respaldo para el socket."""
        text_lower = text.lower()
        sockets = {
            "AM4": ["am4"],
            "AM5": ["am5"],
            "LGA1700": ["lga1700", "lga 1700"],
            "LGA1851": ["lga1851", "lga 1851"],
            "LGA1200": ["lga1200", "lga 1200"]
        }
        
        for socket_name, keywords in sockets.items():
            if any(kw in text_lower for kw in keywords):
                return socket_name
        
        return ""


class GPUExtractor(BaseExtractor):
    """Extractor de datos para tarjetas gráficas."""
    
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        details = {
            "vram": "",
            "mem_type": "",
            "tdp": "0"
        }
        
        # Intentar extraer VRAM del nombre primero
        name_vram = re.search(r"(\d+)\s*gb", name.lower())
        if name_vram:
            details["vram"] = name_vram.group(1)
        else:
            details["vram"] = self._extract_with_patterns(specs_text, "vram", "")
        
        # Tipo de memoria
        mem_matches = re.findall(r"(gddr\d+(?:x)?|hbm\d+)", specs_text, re.IGNORECASE)
        if mem_matches:
            details["mem_type"] = mem_matches[0].upper()
        
        # TDP
        details["tdp"] = self._extract_with_patterns(specs_text, "tdp", "0")
        
        logger.info(f"GPU {name} - Extraídos: {details}")
        return details


class StorageDeviceExtractor(BaseExtractor):
    """Extractor de datos para dispositivos de almacenamiento."""
    def __init__(self, patterns: Dict[str, List[str]], storage_type: str):
        super().__init__(patterns)
        self.storage_type = storage_type.upper()
    

    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        details = {
            "type": self.storage_type,
            "storage": ""
        }
        
        # Extraer capacidad del nombre primero
        name_capacity = re.search(r"(\d+(?:\.\d+)?)\s*(tb|gb)", name, re.IGNORECASE)
        if name_capacity:
            capacity_value = float(name_capacity.group(1))
            capacity_unit = name_capacity.group(2).upper()
            
            # Convertir a TB
            if capacity_unit == "GB":
                capacity_value = capacity_value / 1000
            
            details["storage"] = str(capacity_value)
        else:
            # Buscar en especificaciones
            for pattern in self.patterns.get("storage", []):
                match = re.search(pattern, specs_text, re.IGNORECASE)
                if match:
                    capacity_value = float(match.group(1))
                    capacity_unit = match.group(2).upper() if match.lastindex >= 2 else "GB"
                    
                    # Convertir a TB
                    if capacity_unit == "GB":
                        capacity_value = capacity_value / 1000
                    
                    details["storage"] = str(capacity_value)
                    break
        
        logger.info(f"{self.storage_type} {name} - Extraídos: {details}")
        return details

class RAMExtractor(BaseExtractor):
    """Extractor de datos para memorias RAM."""
    
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        details = {
            "type": "",
            "n_modules": "",
            "module_capacity": "",
            "frequency": "",
            "latency": ""
        }
        
        combined_text = f"{name} {specs_text}"
        
        type_match = re.search(r"\b(ddr[345]x?)\b", combined_text, re.IGNORECASE)
        if type_match:
            details["type"] = type_match.group(1).upper()
        
        module_config = re.search(r"(\d+)\s*x\s*(\d+)\s*gb", combined_text, re.IGNORECASE)
        if module_config:
            details["n_modules"] = module_config.group(1)
            details["module_capacity"] = module_config.group(2)
        else:
            total_capacity = re.search(r"\b(\d+)\s*gb\b", combined_text, re.IGNORECASE)
            if total_capacity:
                details["n_modules"] = "1"
                details["module_capacity"] = total_capacity.group(1)
        
        freq_match = re.search(r"(\d{4,5})\s*mhz", combined_text, re.IGNORECASE)
        if freq_match:
            details["frequency"] = freq_match.group(1)
        
        latency_match = re.search(r"\bcl\s*(\d+)\b", combined_text, re.IGNORECASE)
        if latency_match:
            details["latency"] = latency_match.group(1)
        else:
            # Intentar con formato alternativo "C30", "C16"
            latency_alt = re.search(r"\bc(\d+)\b", combined_text, re.IGNORECASE)
            if latency_alt:
                details["latency"] = latency_alt.group(1)
        
        # Fallbacks desde especificaciones si no se encontró en el nombre
        if not details["type"]:
            details["type"] = self._extract_with_patterns(specs_text, "type", "")
            if details["type"]:
                details["type"] = details["type"].upper()
        
        if not details["frequency"]:
            details["frequency"] = self._extract_with_patterns(specs_text, "frequency", "")
        
        if not details["latency"]:
            details["latency"] = self._extract_with_patterns(specs_text, "latency", "")
        
        logger.info(f"RAM {name} - Extraídos: {details}")
        return details


class MotherboardExtractor(BaseExtractor):
    """Extractor de datos para placas base."""
    
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        details = {
            "socket": "",
            "chipset": "",
            "form_factor": ""
        }
        
        for key in details.keys():
            details[key] = self._extract_with_patterns(specs_text, key, "")
        
        # Fallback para socket
        if not details["socket"]:
            cpu_extractor = CPUExtractor({})
            details["socket"] = cpu_extractor._fallback_socket(specs_text)
        
        logger.info(f"Motherboard {name} - Extraídos: {details}")
        return details


class PowerSupplyExtractor(BaseExtractor):
    """Extractor de datos para fuentes de alimentación."""
    
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        details = {
            "power": "0",
            "certification": ""
        }
        
        # Intentar extraer potencia del nombre
        name_power = re.search(r"(\d{3,4})\s*[Ww]", name)
        if name_power:
            details["power"] = name_power.group(1)
        else:
            power = self._extract_with_patterns(specs_text, "power", "0")
            if int(power) >= 300:
                details["power"] = power
        
        # Certificación
        cert_match = self._extract_with_patterns(specs_text, "certification", "")
        if cert_match:
            details["certification"] = f"80 Plus {cert_match.capitalize()}"
        
        logger.info(f"Power Supply {name} - Extraídos: {details}")
        return details


# ============================================================================
# GESTOR DE BASE DE DATOS
# ============================================================================

class DatabaseManager:
    """Gestiona las operaciones de base de datos."""
    
    BATCH_SIZE = 50
    
    TABLE_SCHEMAS = {
        "procesadores": {
            "table": "cpus",
            "columns": "(created_at, updated_at, name, vendor, brand, price, in_stock, url, image, clock_speed, n_cores, n_threads, socket, tdp)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), clock_speed=VALUES(clock_speed), n_cores=VALUES(n_cores), n_threads=VALUES(n_threads), socket=VALUES(socket), tdp=VALUES(tdp), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        },
        "tarjetas-graficas": {
            "table": "graphic_cards",
            "columns": "(name, vendor, brand, price, in_stock, url, image, manufacturer, vram, mem_type, tdp, created_at, updated_at)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), manufacturer=VALUES(manufacturer), vram=VALUES(vram), mem_type=VALUES(mem_type), tdp=VALUES(tdp), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        },
        "discos-hdd": {
            "table": "storage_devices",
            "columns": "(name, vendor, brand, price, in_stock, url, image, type, storage, created_at, updated_at)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), type=VALUES(type), storage=VALUES(storage), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        },
        "discos-ssd": {
            "table": "storage_devices",
            "columns": "(name, vendor, brand, price, in_stock, url, image, type, storage, created_at, updated_at)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), type=VALUES(type), storage=VALUES(storage), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        },
        "memorias-ram": {
            "table": "rams",
            "columns": "(name, vendor, brand, price, in_stock, url, image, type, n_modules, module_capacity, frequency, latency, created_at, updated_at)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), type=VALUES(type), n_modules=VALUES(n_modules), module_capacity=VALUES(module_capacity), frequency=VALUES(frequency), latency=VALUES(latency), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        },
        "placas-base": {
            "table": "motherboards",
            "columns": "(name, vendor, brand, price, in_stock, url, image, socket, chipset, size_format, created_at, updated_at)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), socket=VALUES(socket), chipset=VALUES(chipset), size_format=VALUES(size_format), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        },
        "fuentes-alimentacion": {
            "table": "power_supplies",
            "columns": "(name, vendor, brand, price, in_stock, url, image, power, certification, created_at, updated_at)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), power=VALUES(power), certification=VALUES(certification), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        }
    }
    
    @staticmethod
    def save_batch(cursor, connection, results: Dict, category: str):
        """Guarda un lote de productos en la base de datos."""
        if category not in DatabaseManager.TABLE_SCHEMAS:
            raise ValueError(f"Categoría no soportada: {category}")
        
        schema = DatabaseManager.TABLE_SCHEMAS[category]
        query = f"""
            INSERT INTO {schema['table']} {schema['columns']}
            VALUES {schema['values']}
            ON DUPLICATE KEY UPDATE {schema['update']}
        """
        
        now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        batch = []
        
        for name, data in results.items():
            values = DatabaseManager._prepare_values(category, name, data, now)
            batch.append(values)
            
            if len(batch) >= DatabaseManager.BATCH_SIZE:
                DatabaseManager._execute_batch(cursor, query, batch)
                connection.commit()
                batch = []
        
        # Insertar registros restantes
        if batch:
            DatabaseManager._execute_batch(cursor, query, batch)
            connection.commit()
        
        logger.info(f"Se guardaron {len(results)} productos de {category}")
    
    @staticmethod
    def _prepare_values(category: str, name: str, data: Dict, now: str) -> tuple:
        """Prepara los valores según la categoría."""
        if category == "procesadores":
            return (
                now, now, name, data.get("vendor", "coolmod"),
                data.get("brand", "unknown"), data.get("price", ""),
                "1", data.get("url", ""), data.get("image_url", ""),
                data.get("clock_speed", ""), data.get("n_cores", "0"),
                data.get("n_threads", "0"), data.get("socket", ""),
                data.get("tdp", "0")
            )
        elif category == "tarjetas-graficas":
            return (
                name, data.get("vendor", "coolmod"),
                data.get("brand", "unknown"), data.get("price", ""),
                "1", data.get("url", ""), data.get("image_url", ""),
                data.get("manufacturer", ""), data.get("vram", ""),
                data.get("mem_type", ""), data.get("tdp", "0"),
                now, now
            )
        elif category == "placas-base":
            return (
                name, data.get("vendor", "coolmod"),
                data.get("brand", "unknown"), data.get("price", ""),
                "1", data.get("url", ""), data.get("image_url", ""),
                data.get("socket", ""), data.get("chipset", ""),
                data.get("form_factor", ""), now, now
            )
        elif category in ["discos-hdd", "discos-ssd"]:
            return (
                name, data.get("vendor", "coolmod"),
                data.get("brand", "unknown"), data.get("price", ""),
                "1", data.get("url", ""), data.get("image_url", ""),
                data.get("type", ""), data.get("storage", ""),
                now, now
            )
        elif category == "memorias-ram":
            return (
                name, data.get("vendor", "coolmod"),
                data.get("brand", "unknown"), data.get("price", ""),
                "1", data.get("url", ""), data.get("image_url", ""),
                data.get("type", ""), data.get("n_modules", ""),
                data.get("module_capacity", ""), data.get("frequency", ""),
                data.get("latency", ""), now, now
            )
        elif category == "fuentes-alimentacion":
            return (
                name, data.get("vendor", "coolmod"),
                data.get("brand", "unknown"), data.get("price", ""),
                "1", data.get("url", ""), data.get("image_url", ""),
                data.get("power", ""), data.get("certification", ""),
                now, now
            )
    
    @staticmethod
    def _execute_batch(cursor, query: str, batch: List[tuple]):
        """Ejecuta un lote de inserciones."""
        try:
            cursor.executemany(query, batch)
        except MySQLError as e:
            logger.error(f"Error en batch insert: {e}")
            # Intentar inserciones individuales
            for values in batch:
                try:
                    cursor.execute(query, values)
                except MySQLError as err:
                    logger.error(f"Error al insertar registro: {err}")


# ============================================================================
# SCRAPER PRINCIPAL
# ============================================================================

class CoolmodScraper:
    """Scraper principal para Coolmod."""
    
    BASE_URL = "https://www.coolmod.com"
    WAIT_TIME = 3
    MAX_RETRIES = 3
    
    CATEGORY_URLS = {
        "procesadores": "/componentes-pc-procesadores/",
        "tarjetas-graficas": "/tarjetas-graficas/",
        "placas-base": "/componentes-pc-placas-base/",
        "discos-hdd": "/componentes-pc-discos-hdd/",
        "discos-ssd": "/componentes-pc-discos-ssd/",
        "memorias-ram": "/componentes-pc-memorias-ram/",
        "fuentes-alimentacion": "/componentes-pc-fuentes-alimentacion/"
    }
    
    EXTRACTORS = {
        "procesadores": lambda: CPUExtractor(SEARCH_PATTERNS["procesadores"]),
        "tarjetas-graficas": lambda: GPUExtractor(SEARCH_PATTERNS["tarjetas-graficas"]),
        "placas-base": lambda: MotherboardExtractor(SEARCH_PATTERNS["placas-base"]),
        "discos-hdd": lambda: StorageDeviceExtractor(SEARCH_PATTERNS["discos-hdd"], "HDD"),
        "discos-ssd": lambda: StorageDeviceExtractor(SEARCH_PATTERNS["discos-ssd"], "SSD"),
        "memorias-ram": lambda: RAMExtractor(SEARCH_PATTERNS["memorias-ram"]),
        "fuentes-alimentacion": lambda: PowerSupplyExtractor(SEARCH_PATTERNS["fuentes-alimentacion"])
    }
    
    def __init__(self, headless: bool = True, chrome_binary: Optional[str] = None):
        self.options = Options()
        self.options.add_argument(
            "user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
            "AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36"
        )
        self.options.headless = headless
        
        if chrome_binary:
            self.options.binary_location = chrome_binary
    
    def scrape_category(
        self, 
        category: str, 
        db_config: Optional[Dict] = None
    ) -> Dict[str, Any]:
        """
        Scrapea una categoría completa de productos.
        
        Args:
            category: Categoría a scrapear
            db_config: Configuración de base de datos (opcional)
        
        Returns:
            Diccionario con los datos de los productos
        """
        if category not in self.CATEGORY_URLS:
            raise ValueError(f"Categoría no válida: {category}")
        
        results = {}
        
        with selenium_driver(self.options) as driver:
            results = self._scrape_all_pages(driver, category)
            logger.info(f"Se encontraron {len(results)} productos en {category}")
            
            # Obtener detalles de cada producto
            logger.info("Obteniendo detalles de productos...")
            self._enrich_products(driver, results, category)
        
        # Guardar en base de datos si está configurada
        if db_config:
            with database_connection(db_config) as (conn, cursor):
                DatabaseManager.save_batch(cursor, conn, results, category)
        
        return results
    
    def _scrape_all_pages(self, driver, category: str) -> Dict[str, Any]:
        """Scrapea todas las páginas de una categoría."""
        base_url = f"{self.BASE_URL}{self.CATEGORY_URLS[category]}"
        results = {}
        page_num = 1
        
        while True:
            url = f"{base_url}?pagina={page_num}"
            logger.info(f"Procesando página {page_num}: {url}")
            
            try:
                driver.get(url)
                time.sleep(self.WAIT_TIME)
                
                soup = BeautifulSoup(driver.page_source, "html.parser")
                products = soup.find_all("article", class_='product-card')
                
                if not products:
                    logger.info(f"No hay más productos en página {page_num}")
                    break
                
                for product in products:
                    product_data = self._extract_product(product, category)
                    if product_data:
                        results[product_data["name"]] = product_data
                
                # Verificar si hay más páginas
                if not self._has_next_page(soup):
                    break
                
                page_num += 1
                
            except Exception as e:
                logger.error(f"Error en página {page_num}: {e}")
                break
        
        return results
    
    def _extract_product(self, html_product, category: str) -> Optional[Dict]:
        """Extrae los datos básicos de un producto."""
        try:
            # Nombre y URL
            title_elem = html_product.find("p", "card-title")
            if not title_elem:
                return None
            
            a_elem = title_elem.find("a")
            if not a_elem:
                return None
            
            name = a_elem.get_text(strip=True)
            name = self._clean_product_name(name, category)
            
            url = a_elem.get("href", "")
            if url and not url.startswith("http"):
                url = self.BASE_URL + url
            
            # Precio
            price_int = html_product.find("span", "product_price")
            price_dec = html_product.find("span", "dec_price")
            
            int_part = price_int.get_text(strip=True).replace(".", "") if price_int else "0"
            dec_part = price_dec.get_text(strip=True) if price_dec else "00"
            price = f"{int_part}.{dec_part}"
            
            # Imagen
            img_tag = html_product.find("img")
            image_url = ""
            if img_tag and img_tag.has_attr("src"):
                image_url = img_tag["src"]
                if image_url.startswith("//"):
                    image_url = "https:" + image_url
            
            # Brand y manufacturer
            brand = self._extract_brand(name, category)
            manufacturer = self._extract_manufacturer(name) if category == "tarjetas-graficas" else ""
            
            return {
                "name": name,
                "url": url,
                "price": price,
                "vendor": "coolmod",
                "brand": brand,
                "manufacturer": manufacturer,
                "in_stock": "1",
                "image_url": image_url,
                "category": category
            }
            
        except Exception as e:
            logger.error(f"Error extrayendo producto: {e}")
            return None
    
    def _enrich_products(self, driver, results: Dict, category: str):
        """Enriquece los productos con detalles adicionales."""
        extractor = self.EXTRACTORS[category]()
        
        for name, data in results.items():
            try:
                specs_text = self._get_specs_text(driver, data["url"])
                details = extractor.extract(specs_text, name)
                data.update(details)
            except Exception as e:
                logger.error(f"Error enriqueciendo {name}: {e}")
    
    def _get_specs_text(self, driver, url: str) -> str:
        """Obtiene el texto de especificaciones de un producto."""
        driver.get(url)
        time.sleep(self.WAIT_TIME)
        
        soup = BeautifulSoup(driver.page_source, "html.parser")
        specs_divs = soup.find_all("div", class_="collapse-content")
        
        return "\n".join(div.get_text(strip=True) for div in specs_divs)
    
    def _has_next_page(self, soup: BeautifulSoup) -> bool:
        """Verifica si hay una página siguiente."""
        pagination = soup.find("ul", class_="pagination-container")
        if not pagination:
            return False
        
        next_button = pagination.find("button", class_="next-button")
        return next_button and "disabled" not in next_button.get("class", [])
    
    def _clean_product_name(self, name: str, category: str) -> str:
        """Limpia el nombre del producto según la categoría."""
        if category == "procesadores":
            return self._clean_processor_name(name)
        elif category == "tarjetas-graficas":
            return self._clean_graphics_card_name(name)
        return name
    
    def _clean_processor_name(self, raw_name: str) -> str:
        """Limpia el nombre de un procesador."""
        patterns = [
            r"(Intel\s+Core(?:\s+\w+)?\s+\w+\s+\d+(?:\w+)?)",
            r"(AMD\s+Ryzen(?:\s+\w+)?\s+\d+(?:\s+\w+\d+)?(?:\s*(?:X3D|X|GT))?)"
        ]
        
        for pattern in patterns:
            match = re.search(pattern, raw_name, re.IGNORECASE)
            if match:
                return match.group(1)
        
        # Limpieza genérica
        cleaned = raw_name
        remove_terms = [
            r"Procesador\s+",
            r"\s+Socket\s+\w+",
            r"\s+\d+\.\d+GHz",
            r"\s+Boxed",
            r"\s+-\s+Procesador.*$",
            r"\s+BOX",
            r"\s+Bulk"
        ]
        
        for term in remove_terms:
            cleaned = re.sub(term, "", cleaned, flags=re.IGNORECASE)
        
        return cleaned.strip()
    
    def _clean_graphics_card_name(self, raw_name: str) -> str:
        """Limpia el nombre de una tarjeta gráfica."""
        patterns = [
            r"((?:Gigabyte|MSI|Asus|Zotac|EVGA|Palit|INNO3D|ASRock)?\s+(?:GeForce\s+)?(?:RTX|GTX)\s+\d+(?:\s+(?:Ti|SUPER|XT))?\s+(?:(?:WindForce|Gaming|TUF|ROG|VENTUS|EAGLE|AERO|TWIN|AMP)(?:\s+\w+)?)?)",
            r"((?:Gigabyte|MSI|Asus|XFX|PowerColor|Sapphire)?\s+(?:Radeon\s+)?(?:RX)\s+\d+(?:\s+(?:XT|PRO))?\s+(?:(?:NITRO|PULSE|GAMING|TUF|ROG|AORUS)(?:\s+\w+)?)?)"
        ]
        
        for pattern in patterns:
            match = re.search(pattern, raw_name, re.IGNORECASE)
            if match:
                return match.group(1)
        
        # Limpieza genérica
        cleaned = raw_name
        remove_terms = [
            r"Tarjeta\s+[Gg]ráfica\s+",
            r"\s+-\s+Tarjeta\s+gráfica.*$",
            r"\s+\d+GB\s+GDDR\d+",
            r"\s+DLSS\d+",
            r"\s+OC\s+\d+GB"
        ]
        
        for term in remove_terms:
            cleaned = re.sub(term, "", cleaned, flags=re.IGNORECASE)
        
        return cleaned.strip()
    
    def _extract_brand(self, name: str, category: str) -> str:
        """Extrae la marca del nombre del producto."""
        if category not in BRAND_PATTERNS:
            return "unknown"
        
        name_lower = name.lower()
        for brand, keywords in BRAND_PATTERNS[category].items():
            if any(kw in name_lower for kw in keywords):
                return brand
        
        return "unknown"
    
    def _extract_manufacturer(self, name: str) -> str:
        """Extrae el fabricante de una tarjeta gráfica."""
        name_lower = name.lower()
        manufacturers = {
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
            "inno3d": "INNO3D"
        }
        
        for key, value in manufacturers.items():
            if key in name_lower:
                return value
        
        return "unknown"


# ============================================================================
# UTILIDADES
# ============================================================================

def save_to_json(data: Dict, filename: str):
    """Guarda los resultados en un archivo JSON."""
    with open(filename, "w", encoding="utf-8") as f:
        json.dump(data, f, indent=2, ensure_ascii=False)
    logger.info(f"Resultados guardados en {filename}")


def load_env_config() -> Dict[str, Any]:
    """Carga la configuración desde variables de entorno."""
    env_path = Path(__file__).parent.parent / ".env"
    load_dotenv(dotenv_path=env_path)
    
    return {
        "host": os.getenv("DB_HOST", "localhost"),
        "port": int(os.getenv("DB_PORT", 3306)),
        "user": os.getenv("DB_USERNAME"),
        "password": os.getenv("DB_PASSWORD"),
        "database": os.getenv("DB_DATABASE")
    }


# ============================================================================
# EJECUCIÓN PRINCIPAL
# ============================================================================

def main():
    """Función principal de ejecución."""
    # Configuración
    chrome_binary = "/usr/bin/chromium-browser"
    categories = ["procesadores", "tarjetas-graficas", "discos-hdd", "discos-ssd", "memorias-ram", "placas-base", "fuentes-alimentacion"]
    # categories = ["memorias-ram"]
    
    # Cargar configuración de base de datos
    try:
        db_config = load_env_config()
        use_database = all([
            db_config.get("user"),
            db_config.get("password"),
            db_config.get("database")
        ])
    except Exception as e:
        logger.warning(f"No se pudo cargar configuración de BD: {e}")
        use_database = False
    
    # Crear scraper
    scraper = CoolmodScraper(headless=False, chrome_binary=chrome_binary)
    
    # Procesar cada categoría
    for category in categories:
        try:
            logger.info(f"\n{'='*60}")
            logger.info(f"Procesando categoría: {category}")
            logger.info(f"{'='*60}\n")
            
            # Scrapear
            results = scraper.scrape_category(
                category,
                db_config=db_config if use_database else None
            )
            
            # Guardar en archivo JSON
            filename = f"{category}_results.json"
            save_to_json(results, filename)
            
            logger.info(f"\n✓ Categoría {category} completada")
            logger.info(f"  - Productos encontrados: {len(results)}")
            logger.info(f"  - Archivo JSON: {filename}")
            if use_database:
                logger.info(f"  - Guardado en base de datos: Sí")
            
        except Exception as e:
            logger.error(f"Error procesando {category}: {e}", exc_info=True)
            continue
    
    logger.info(f"\n{'='*60}")
    logger.info("Scraping completado")
    logger.info(f"{'='*60}")


if __name__ == "__main__":
    main()