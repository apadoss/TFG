import re
from abc import ABC, abstractmethod
from typing import Dict, List, Any
import logging

logger = logging.getLogger(__name__)

class BaseExtractor(ABC):
    """Clase base para extractores de datos de productos."""
    
    def __init__(self, patterns: Dict[str, List[str]]):
        """
        Inicializa el extractor con patrones de búsqueda.
        
        Args:
            patterns: Diccionario con patrones regex por campo.
                     Ejemplo: {"n_cores": [r"núcleos.*?(\d+)", ...], ...}
        """
        self.patterns = patterns
    
    @abstractmethod
    def extract(self, specs_text: str, name: str) -> Dict[str, Any]:
        """
        Extrae detalles específicos del producto.
        
        Args:
            specs_text: Texto con las especificaciones del producto
            name: Nombre del producto
        
        Returns:
            Diccionario con los campos extraídos
        """
        pass
    
    def _extract_with_patterns(
        self, 
        specs_text: str, 
        key: str, 
        default: str = ""
    ) -> str:
        """
        Extrae un valor usando los patrones definidos.
        
        Args:
            specs_text: Texto donde buscar
            key: Clave del campo a extraer
            default: Valor por defecto si no se encuentra
        
        Returns:
            Valor extraído o default
        """
        if key not in self.patterns:
            return default
        
        for pattern in self.patterns[key]:
            match = re.search(pattern, specs_text, re.IGNORECASE)
            if match:
                # Si el patrón tiene grupos, retornar el primer grupo
                return match.group(1) if match.lastindex else match.group(0)
        
        return default