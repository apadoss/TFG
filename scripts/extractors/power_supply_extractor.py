import re
import logging
from typing import Dict, Any
from .base_extractor import BaseExtractor

logger = logging.getLogger(__name__)

class PowerSupplyExtractor(BaseExtractor):
    """Extractor de datos para fuentes de alimentación."""
    
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        """
        Extrae especificaciones de una fuente de alimentación.
        
        Campos extraídos:
        - power: Potencia en vatios
        - certification: Certificación (80 Plus Bronze, Gold, etc.)
        """
        details = {
            "power": "0",
            "certification": ""
        }
        
        # Intentar extraer potencia del nombre primero
        name_power = re.search(r"(\d{3,4})\s*[Ww]", name)
        if name_power:
            details["power"] = name_power.group(1)
        else:
            power = self._extract_with_patterns(specs_text, "power", "0")
            # Validar que sea un valor razonable (300W+)
            try:
                if int(power) >= 300:
                    details["power"] = power
            except ValueError:
                pass
        
        # Certificación
        cert_match = self._extract_with_patterns(specs_text, "certification", "")
        if cert_match:
            details["certification"] = f"80 Plus {cert_match.capitalize()}"
        
        logger.debug(f"PSU {name[:50]} - Extraídos: {details}")
        return details