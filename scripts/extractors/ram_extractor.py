import re
import logging
from typing import Dict, Any
from .base_extractor import BaseExtractor

logger = logging.getLogger(__name__)

class RAMExtractor(BaseExtractor):
    """Extractor de datos para memorias RAM."""
    
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        """
        Extrae especificaciones de memoria RAM.
        
        Campos extraídos:
        - type: Tipo de RAM (DDR4, DDR5)
        - n_modules: Número de módulos
        - module_capacity: Capacidad por módulo en GB
        - frequency: Frecuencia en MHz
        - latency: Latencia CAS
        """
        details = {
            "type": "",
            "n_modules": "",
            "module_capacity": "",
            "frequency": "",
            "latency": ""
        }
        
        # Combinar nombre y specs para mejor detección
        combined_text = f"{name} {specs_text}"
        
        # Tipo de RAM
        type_match = re.search(r"\b(ddr[345]x?)\b", combined_text, re.IGNORECASE)
        if type_match:
            details["type"] = type_match.group(1).upper()
        
        # Configuración de módulos (ej: 2x8GB)
        module_config = re.search(
            r"(\d+)\s*x\s*(\d+)\s*gb", 
            combined_text, 
            re.IGNORECASE
        )
        if module_config:
            details["n_modules"] = module_config.group(1)
            details["module_capacity"] = module_config.group(2)
        else:
            # Intentar detectar capacidad total
            total_capacity = re.search(
                r"\b(\d+)\s*gb\b", 
                combined_text, 
                re.IGNORECASE
            )
            if total_capacity:
                details["n_modules"] = "1"
                details["module_capacity"] = total_capacity.group(1)
        
        # Frecuencia
        freq_match = re.search(
            r"(\d{4,5})\s*mhz", 
            combined_text, 
            re.IGNORECASE
        )
        if freq_match:
            details["frequency"] = freq_match.group(1)
        
        # Latencia (CL16, CL18, etc.)
        latency_match = re.search(r"\bcl\s*(\d+)\b", combined_text, re.IGNORECASE)
        if latency_match:
            details["latency"] = latency_match.group(1)
        else:
            # Formato alternativo: C16, C18
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
        
        logger.debug(f"RAM {name[:50]} - Extraídos: {details}")
        return details
