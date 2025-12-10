
"""
Orquestador del proceso de scraping.
Gestiona múltiples scrapers y categorías, coordinando la extracción
y el guardado de datos.
"""
import logging
import json
from pathlib import Path
from typing import List, Dict, Optional, Any
from .base_scraper import BaseScraper
from .database.manager import DatabaseManager, database_connection
from .extractors import (
    CPUExtractor, GPUExtractor, MotherboardExtractor,
    StorageDeviceExtractor, RAMExtractor, PowerSupplyExtractor
)
from .utils.patterns import SEARCH_PATTERNS

logger = logging.getLogger(__name__)


class ScraperOrchestrator:
    """Coordina el scraping de múltiples tiendas y categorías."""
    
    def __init__(
        self,
        scrapers: List[BaseScraper],
        categories: List[str],
        db_config: Optional[Dict] = None,
        save_json: bool = True,
        output_dir: str = "output"
    ):
        """
        Inicializa el orquestador.
        
        Args:
            scrapers: Lista de instancias de scrapers a ejecutar.
            categories: Lista de nombres de categorías a scrapear.
            db_config: Configuración de la base de datos (opcional).
            save_json: Si es True, guarda los resultados en archivos JSON.
            output_dir: Directorio para guardar archivos JSON.
        """
        self.scrapers = scrapers
        self.categories = categories
        self.db_config = db_config
        self.save_json = save_json
        self.output_dir = Path(output_dir)
        
        # Crear directorio de salida si no existe
        if self.save_json:
            self.output_dir.mkdir(exist_ok=True)
        
        self._validate_categories()
    
    def _validate_categories(self):
        """Valida que todas las categorías sean soportadas."""
        valid_categories = SEARCH_PATTERNS.keys()
        invalid = [cat for cat in self.categories if cat not in valid_categories]
        
        if invalid:
            logger.warning(f"Categorías no soportadas (se ignorarán): {invalid}")
            self.categories = [cat for cat in self.categories if cat in valid_categories]
    
    def _get_extractor(self, category: str):
        """
        Crea la instancia del extractor apropiado para una categoría.
        
        Args:
            category: Nombre de la categoría
        
        Returns:
            Instancia del extractor configurado
        
        Raises:
            ValueError: Si la categoría no es válida
        """
        if category not in SEARCH_PATTERNS:
            raise ValueError(f"Categoría no válida: {category}")
        
        # Obtener los patrones para esta categoría
        patterns = SEARCH_PATTERNS[category]
        
        # Crear instancia del extractor apropiado
        if category == "procesadores":
            return CPUExtractor(patterns)
        
        elif category == "tarjetas-graficas":
            return GPUExtractor(patterns)
        
        elif category == "placas-base":
            return MotherboardExtractor(patterns)
        
        elif category == "discos-hdd":
            return StorageDeviceExtractor(patterns, "HDD")
        
        elif category == "discos-ssd":
            return StorageDeviceExtractor(patterns, "SSD")
        
        elif category == "memorias-ram":
            return RAMExtractor(patterns)
        
        elif category == "fuentes-alimentacion":
            return PowerSupplyExtractor(patterns)
        
        else:
            raise ValueError(f"No hay extractor para la categoría: {category}")
    
    def run(self) -> Dict[str, Dict[str, Dict]]:
        """
        Inicia el proceso de scraping orquestado.
        
        Returns:
            Diccionario con todos los resultados:
            {
                "vendor1": {
                    "categoria1": {productos},
                    "categoria2": {productos}
                },
                "vendor2": {...}
            }
        """
        logger.info("="*70)
        logger.info("INICIANDO PROCESO DE SCRAPING ORQUESTADO")
        logger.info("="*70)
        
        all_results = {}
        
        # Procesar cada scraper (vendor)
        for scraper in self.scrapers:
            vendor_name = scraper.VENDOR_NAME
            logger.info(f"\n{'='*70}")
            logger.info(f"VENDOR: {vendor_name.upper()}")
            logger.info(f"{'='*70}")
            
            vendor_results = {}
            
            # Procesar cada categoría
            for category in self.categories:
                logger.info(f"\n--- Categoría: {category} ---")
                
                try:
                    extractor = self._get_extractor(category)
                    
                    # Ejecutar scraping
                    results = scraper.scrape_category(
                        category=category,
                        extractor=extractor,
                        db_manager=DatabaseManager if self.db_config else None,
                        db_config=self.db_config
                    )
                    
                    # Guardar resultados
                    vendor_results[category] = results
                    
                    # Guardar en JSON individual si está habilitado
                    if self.save_json and results:
                        self._save_json(results, vendor_name, category)
                    
                    logger.info(f"✓ {category}: {len(results)} productos extraídos")
                    
                except Exception as e:
                    logger.error(
                        f"Error procesando {category} en {vendor_name}: {e}",
                        exc_info=True
                    )
                    vendor_results[category] = {}
            
            # Guardar todos los resultados del vendor
            all_results[vendor_name] = vendor_results
            
            # Guardar JSON consolidado del vendor
            if self.save_json:
                self._save_json_vendor(vendor_results, vendor_name)
        
        # Resumen final
        self._print_summary(all_results)
        
        logger.info("\n" + "="*70)
        logger.info("PROCESO DE SCRAPING FINALIZADO")
        logger.info("="*70)
        
        return all_results
    
    def _save_json(self, data: Dict, vendor: str, category: str):
        """
        Guarda resultados en JSON individual.
        
        Args:
            data: Diccionario con los productos
            vendor: Nombre del vendor
            category: Categoría de productos
        """
        filename = self.output_dir / f"{vendor}_{category}.json"
        
        try:
            with open(filename, "w", encoding="utf-8") as f:
                json.dump(data, f, indent=2, ensure_ascii=False)
            logger.info(f"Guardado JSON: {filename}")
        except Exception as e:
            logger.error(f"Error guardando JSON {filename}: {e}")
    
    def _save_json_vendor(self, data: Dict, vendor: str):
        """
        Guarda todos los resultados de un vendor en un solo JSON.
        
        Args:
            data: Diccionario con todas las categorías
            vendor: Nombre del vendor
        """
        filename = self.output_dir / f"{vendor}_all_categories.json"
        
        try:
            with open(filename, "w", encoding="utf-8") as f:
                json.dump(data, f, indent=2, ensure_ascii=False)
            logger.info(f"Guardado JSON consolidado: {filename}")
        except Exception as e:
            logger.error(f"Error guardando JSON consolidado {filename}: {e}")
    
    def _print_summary(self, results: Dict[str, Dict[str, Dict]]):
        """
        Imprime un resumen de los resultados.
        
        Args:
            results: Diccionario con todos los resultados
        """
        logger.info("\n" + "="*70)
        logger.info("RESUMEN DE RESULTADOS")
        logger.info("="*70)
        
        grand_total = 0
        
        for vendor, categories in results.items():
            vendor_total = sum(len(products) for products in categories.values())
            grand_total += vendor_total
            
            logger.info(f"\n{vendor.upper()}:")
            for category, products in categories.items():
                count = len(products)
                logger.info(f"  - {category}: {count} productos")
            logger.info(f"  TOTAL: {vendor_total} productos")
        
        logger.info(f"\n{'='*70}")
        logger.info(f"TOTAL GENERAL: {grand_total} productos")
        logger.info(f"{'='*70}")


# ============================================================================
# FUNCIÓN DE UTILIDAD PARA CREAR EL ORQUESTADOR
# ============================================================================

def create_orchestrator(
    scrapers: List[BaseScraper],
    categories: Optional[List[str]] = None,
    db_config: Optional[Dict] = None,
    save_json: bool = True,
    output_dir: str = "output"
) -> ScraperOrchestrator:
    """
    Función helper para crear un orquestrador con configuración por defecto.
    
    Args:
        scrapers: Lista de scrapers a usar
        categories: Categorías a scrapear (None = todas)
        db_config: Config de BD (None = solo JSON)
        save_json: Guardar resultados en JSON
        output_dir: Directorio para JSONs
    
    Returns:
        Instancia de ScraperOrchestrator configurado
    
    Example:
        from scrapers import CoolmodScraper
        from scrapers.orchestrator import create_orchestrator
        
        orchestrator = create_orchestrator(
            scrapers=[CoolmodScraper(headless=True)],
            categories=["procesadores", "tarjetas-graficas"]
        )
        results = orchestrator.run()
    """
    # Si no se especifican categorías, usar todas
    if categories is None:
        categories = list(SEARCH_PATTERNS.keys())
    
    return ScraperOrchestrator(
        scrapers=scrapers,
        categories=categories,
        db_config=db_config,
        save_json=save_json,
        output_dir=output_dir
    )