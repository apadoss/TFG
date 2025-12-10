"""
Scraper base abstracto para sitios de componentes de PC.
Define la interfaz común que todos los scrapers deben implementar.
"""

from abc import ABC, abstractmethod
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from bs4 import BeautifulSoup
from typing import Dict, Optional, Any
import time
import logging
from contextlib import contextmanager

logger = logging.getLogger(__name__)


class BaseScraper(ABC):
    """
    Clase base abstracta para scrapers de tiendas online.
    
    Los scrapers específicos deben implementar:
    - _get_category_url: URL específica del vendor
    - _extract_product_from_html: Extracción específica del HTML
    - _has_next_page: Lógica de paginación
    - _get_product_specs_text: Obtención de especificaciones
    """
    
    # Constantes que cada scraper debe sobrescribir
    BASE_URL = None
    VENDOR_NAME = None
    WAIT_TIME = 3
    MAX_RETRIES = 3
    
    def __init__(self, headless: bool = True, chrome_binary: Optional[str] = None):
        """
        Inicializa el scraper.
        
        Args:
            headless: Si True, ejecuta el navegador sin interfaz gráfica
            chrome_binary: Ruta al binario de Chrome/Chromium (opcional)
        """
        if not self.BASE_URL or not self.VENDOR_NAME:
            raise NotImplementedError(
                f"{self.__class__.__name__} debe definir BASE_URL y VENDOR_NAME"
            )
        
        self.options = self._setup_driver_options()
        self.options.headless = headless
        
        if chrome_binary:
            self.options.binary_location = chrome_binary
    
    def _setup_driver_options(self) -> Options:
        """Configura las opciones del driver de Chrome."""
        options = Options()
        options.add_argument(
            "user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
            "AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36"
        )
        # Opciones adicionales para evitar detección
        options.add_argument("--disable-blink-features=AutomationControlled")
        options.add_experimental_option("excludeSwitches", ["enable-automation"])
        options.add_experimental_option('useAutomationExtension', False)
        
        return options
    
    @contextmanager
    def _get_driver(self):
        """Context manager para el driver de Selenium."""
        driver = None
        try:
            driver = webdriver.Chrome(options=self.options)
            yield driver
        finally:
            if driver:
                driver.quit()
    
    # ========================================================================
    # MÉTODOS ABSTRACTOS - Deben ser implementados por cada scraper
    # ========================================================================
    
    @abstractmethod
    def _get_category_url(self, category: str, page: int = 1) -> str:
        """
        Construye la URL para una categoría y página específica.
        
        Args:
            category: Nombre de la categoría
            page: Número de página
        
        Returns:
            URL completa
        """
        pass
    
    @abstractmethod
    def _extract_product_from_html(self, html_element, category: str) -> Optional[Dict]:
        """
        Extrae los datos básicos de un producto desde un elemento HTML.
        
        Args:
            html_element: Elemento BeautifulSoup del producto
            category: Categoría del producto
        
        Returns:
            Diccionario con los datos del producto o None si falla
        """
        pass
    
    @abstractmethod
    def _has_next_page(self, soup: BeautifulSoup) -> bool:
        """
        Determina si existe una página siguiente.
        
        Args:
            soup: Objeto BeautifulSoup de la página actual
        
        Returns:
            True si hay más páginas, False en caso contrario
        """
        pass
    
    @abstractmethod
    def _get_product_specs_text(self, driver, url: str) -> str:
        """
        Obtiene el texto de especificaciones de un producto.
        
        Args:
            driver: WebDriver de Selenium
            url: URL del producto
        
        Returns:
            Texto con las especificaciones
        """
        pass
    
    @abstractmethod
    def _get_product_container_selector(self) -> tuple:
        """
        Retorna el selector para encontrar los contenedores de productos.
        
        Returns:
            Tupla (tag, attributes_dict) para usar en soup.find_all()
            Ejemplo: ("article", {"class": "product-card"})
        """
        pass
    
    # ========================================================================
    # MÉTODOS COMUNES
    # ========================================================================
    
    def scrape_category(
        self, 
        category: str, 
        extractor,
        db_manager=None,
        db_config: Optional[Dict] = None
    ) -> Dict[str, Any]:
        """
        Scrapea una categoría completa de productos.
        
        Args:
            category: Categoría a scrapear
            extractor: Objeto extractor específico de la categoría
            db_manager: Gestor de base de datos (opcional)
            db_config: Configuración de BD (opcional)
        
        Returns:
            Diccionario con los datos de los productos
        """
        logger.info(f"Iniciando scraping de {category} en {self.VENDOR_NAME}")
        results = {}
        
        with self._get_driver() as driver:
            # Scrapear todas las páginas
            results = self._scrape_all_pages(driver, category)
            logger.info(f"Encontrados {len(results)} productos en {category}")
            
            # Enriquecer con detalles
            if results:
                logger.info("Obteniendo detalles de productos...")
                self._enrich_products(driver, results, category, extractor)
        
        # Guardar en BD si está configurada
        if db_manager and db_config:
            from .database.manager import database_connection
            with database_connection(db_config) as (conn, cursor):
                db_manager.save_batch(cursor, conn, results, category)
        
        return results
    
    def _scrape_all_pages(self, driver, category: str) -> Dict[str, Any]:
        """
        Scrapea todas las páginas de una categoría.
        
        Args:
            driver: WebDriver de Selenium
            category: Categoría a scrapear
        
        Returns:
            Diccionario con todos los productos encontrados
        """
        results = {}
        page_num = 1
        
        while True:
            url = self._get_category_url(category, page_num)
            logger.info(f"Procesando página {page_num}: {url}")
            
            try:
                driver.get(url)
                time.sleep(self.WAIT_TIME)
                
                soup = BeautifulSoup(driver.page_source, "html.parser")
                
                # Obtener selector de productos
                tag, attrs = self._get_product_container_selector()
                products = soup.find_all(tag, attrs)
                
                if not products:
                    logger.info(f"No hay más productos en página {page_num}")
                    break
                
                logger.info(f"Encontrados {len(products)} productos en página {page_num}")
                
                # Extraer cada producto
                for product in products:
                    product_data = self._extract_product_from_html(product, category)
                    if product_data:
                        results[product_data["name"]] = product_data
                
                # Verificar si hay más páginas
                if not self._has_next_page(soup):
                    logger.info("No hay más páginas disponibles")
                    break
                
                page_num += 1
                
            except Exception as e:
                logger.error(f"Error en página {page_num}: {e}")
                break
        
        return results
    
    def _enrich_products(self, driver, results: Dict, category: str, extractor):
        """
        Enriquece los productos con detalles adicionales.
        
        Args:
            driver: WebDriver de Selenium
            results: Diccionario de productos a enriquecer
            category: Categoría de productos
            extractor: Objeto extractor para procesar especificaciones
        """
        total = len(results)
        for idx, (name, data) in enumerate(results.items(), 1):
            try:
                logger.info(f"Procesando detalles {idx}/{total}: {name[:50]}...")
                specs_text = self._get_product_specs_text(driver, data["url"])
                details = extractor.extract(specs_text, name)
                data.update(details)
            except Exception as e:
                logger.error(f"Error enriqueciendo {name}: {e}")
    
    # ========================================================================
    # MÉTODOS DE UTILIDAD
    # ========================================================================
    
    def _normalize_price(self, price_str: str) -> str:
        """
        Normaliza el formato de precio.
        
        Args:
            price_str: Precio en formato string
        
        Returns:
            Precio normalizado (formato: "1234.56")
        """
        import re
        # Eliminar símbolos de moneda y espacios
        price_str = re.sub(r'[€$\s]', '', price_str)
        # Reemplazar coma por punto si es necesario
        price_str = price_str.replace(',', '.')
        return price_str
    
    def _normalize_url(self, url: str) -> str:
        """
        Normaliza una URL relativa a absoluta.
        
        Args:
            url: URL a normalizar
        
        Returns:
            URL absoluta
        """
        if not url:
            return ""
        
        if url.startswith("http"):
            return url
        elif url.startswith("//"):
            return "https:" + url
        elif url.startswith("/"):
            return self.BASE_URL + url
        else:
            return self.BASE_URL + "/" + url