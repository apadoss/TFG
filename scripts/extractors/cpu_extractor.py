import re
import logging
from typing import Dict, Any
from .base_extractor import BaseExtractor

logger = logging.getLogger(__name__)

class CPUExtractor(BaseExtractor):
    """Extractor de datos para procesadores."""
    
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        """
        Extrae especificaciones de un procesador.
        
        Campos extraídos:
        - n_cores: Número de núcleos
        - n_threads: Número de hilos
        - socket: Tipo de socket (AM5, LGA1700, etc.)
        - tdp: Consumo en vatios
        - clock_speed: Frecuencia base en GHz
        """
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
        
        # Fallback para socket si no se encontró
        if not details["socket"]:
            details["socket"] = self._fallback_socket(specs_text)
        
        # Fallback para clock_speed
        if not details["clock_speed"]:
            clock_matches = re.findall(
                r"(\d+(?:\.\d+)?)\s*GHz", 
                specs_text, 
                re.IGNORECASE
            )
            if clock_matches:
                details["clock_speed"] = clock_matches[0]
        
        logger.debug(f"CPU {name[:50]} - Extraídos: {details}")
        return details
    
    def _fallback_socket(self, text: str) -> str:
        """Búsqueda de respaldo para el socket."""
        text_lower = text.lower()
        
        # Sockets conocidos
        sockets = {
            "AM4": ["am4"],
            "AM5": ["am5"],
            "LGA1700": ["lga1700", "lga 1700"],
            "LGA1851": ["lga1851", "lga 1851"],
            "LGA1200": ["lga1200", "lga 1200"],
            "LGA1151": ["lga1151", "lga 1151"],
            "TR4": ["tr4", "threadripper tr4"],
            "sTRX4": ["strx4", "threadripper strx4"]
        }
        
        for socket_name, keywords in sockets.items():
            if any(kw in text_lower for kw in keywords):
                return socket_name
        
        return ""