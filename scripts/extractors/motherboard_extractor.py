import re
import logging
from typing import Dict, Any
from .base_extractor import BaseExtractor
from .cpu_extractor import CPUExtractor

logger = logging.getLogger(__name__)

class MotherboardExtractor(BaseExtractor):
    """Extractor de datos para placas base."""
    
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        """
        Extrae especificaciones de una placa base.
        
        Campos extraídos:
        - socket: Tipo de socket
        - chipset: Chipset (X670, Z790, etc.)
        - form_factor: Formato (ATX, Micro-ATX, Mini-ITX)
        """
        details = {
            "socket": "",
            "chipset": "",
            "form_factor": ""
        }
        
        # Extraer con patrones
        for key in details.keys():
            details[key] = self._extract_with_patterns(specs_text, key, "")
        
        # Fallback para socket
        if not details["socket"]:
            cpu_extractor = CPUExtractor({})
            details["socket"] = cpu_extractor._fallback_socket(specs_text)
        
        logger.debug(f"Motherboard {name[:50]} - Extraídos: {details}")
        return details
