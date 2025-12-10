import re
import logging
from typing import Dict, List, Any
from .base_extractor import BaseExtractor

logger = logging.getLogger(__name__)

class StorageDeviceExtractor(BaseExtractor):
    """Extractor de datos para dispositivos de almacenamiento."""
    
    def __init__(self, patterns: Dict[str, List[str]], storage_type: str):
        """
        Inicializa el extractor de almacenamiento.
        
        Args:
            patterns: Patrones de búsqueda
            storage_type: Tipo de almacenamiento ("HDD" o "SSD")
        """
        super().__init__(patterns)
        self.storage_type = storage_type.upper()
    
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        """
        Extrae especificaciones de un dispositivo de almacenamiento.
        
        Campos extraídos:
        - type: Tipo (HDD o SSD)
        - storage: Capacidad en TB
        """
        details = {
            "type": self.storage_type,
            "storage": ""
        }
        
        # Extraer capacidad del nombre primero
        name_capacity = re.search(
            r"(\d+(?:\.\d+)?)\s*(tb|gb)", 
            name, 
            re.IGNORECASE
        )
        
        if name_capacity:
            capacity_value = float(name_capacity.group(1))
            capacity_unit = name_capacity.group(2).upper()
            
            # Convertir GB a TB
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
                    
                    # Convertir GB a TB
                    if capacity_unit == "GB":
                        capacity_value = capacity_value / 1000
                    
                    details["storage"] = str(capacity_value)
                    break
        
        logger.debug(f"{self.storage_type} {name[:50]} - Extraídos: {details}")
        return details