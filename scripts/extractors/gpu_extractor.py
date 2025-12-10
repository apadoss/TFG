import re
import logging
from typing import Dict, Any
from .base_extractor import BaseExtractor

logger = logging.getLogger(__name__)

class GPUExtractor(BaseExtractor):
    """Extractor de datos para tarjetas gráficas."""
    
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        """
        Extrae especificaciones de una tarjeta gráfica.
        
        Campos extraídos:
        - vram: Cantidad de memoria en GB
        - mem_type: Tipo de memoria (GDDR6, GDDR6X, etc.)
        - tdp: Consumo en vatios
        """
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
        mem_matches = re.findall(
            r"(gddr\d+(?:x)?|hbm\d+)", 
            specs_text, 
            re.IGNORECASE
        )
        if mem_matches:
            details["mem_type"] = mem_matches[0].upper()
        
        # TDP
        details["tdp"] = self._extract_with_patterns(specs_text, "tdp", "0")
        
        logger.debug(f"GPU {name[:50]} - Extraídos: {details}")
        return details