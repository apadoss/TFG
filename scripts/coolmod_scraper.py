from .base_scraper import BaseScraper
from bs4 import BeautifulSoup
from typing import Dict, Optional
import re
import time
import logging

logger = logging.getLogger(__name__)


class CoolmodScraper(BaseScraper):
    """Scraper específico para Coolmod."""
    
    BASE_URL = "https://www.coolmod.com"
    VENDOR_NAME = "coolmod"
    WAIT_TIME = 3
    
    # Diccionario de URLs de categorías
    CATEGORY_URLS = {
        "procesadores": "/componentes-pc-procesadores/",
        "tarjetas-graficas": "/tarjetas-graficas/",
        "placas-base": "/componentes-pc-placas-base/",
        "discos-hdd": "/componentes-pc-discos-hdd/",
        "discos-ssd": "/componentes-pc-discos-ssd/",
        "memorias-ram": "/componentes-pc-memorias-ram/",
        "fuentes-alimentacion": "/componentes-pc-fuentes-alimentacion/"
    }
    
    # ========================================================================
    # MÉTODOS ABSTRACTOS OBLIGATORIOS
    # ========================================================================
    
    def _get_category_url(self, category: str, page: int = 1) -> str:
        """Construye la URL para Coolmod con paginación."""
        if category not in self.CATEGORY_URLS:
            raise ValueError(f"Categoría no válida: {category}")
        
        base_url = f"{self.BASE_URL}{self.CATEGORY_URLS[category]}"
        return f"{base_url}?pagina={page}"
    
    def _get_product_container_selector(self) -> tuple:
        """
        Selector de productos en Coolmod.
        Retorna (tag, attributes) para usar en soup.find_all()
        """
        return ("article", {"class": "product-card"})
    
    def _extract_product_from_html(self, html_product, category: str) -> Optional[Dict]:
        """
        Extrae datos de un producto de Coolmod.
        """
        try:
            # ============================================================
            # 1. NOMBRE Y URL
            # ============================================================
            title_elem = html_product.find("p", "card-title")
            if not title_elem:
                logger.warning("No se encontró elemento 'card-title'")
                return None
            
            a_elem = title_elem.find("a")
            if not a_elem:
                logger.warning("No se encontró enlace en 'card-title'")
                return None
            
            # Nombre del producto
            name = a_elem.get_text(strip=True)
            # Limpiar el nombre según la categoría
            name = self._clean_product_name(name, category)
            
            # URL del producto
            url = self._normalize_url(a_elem.get("href", ""))
            
            # ============================================================
            # 2. PRECIO
            # ============================================================
            price_int = html_product.find("span", "product_price")
            price_dec = html_product.find("span", "dec_price")
            
            # Coolmod separa parte entera y decimal
            int_part = price_int.get_text(strip=True).replace(".", "") if price_int else "0"
            dec_part = price_dec.get_text(strip=True) if price_dec else "00"
            price = f"{int_part}.{dec_part}"
            
            # ============================================================
            # 3. IMAGEN
            # ============================================================
            img_tag = html_product.find("img")
            image_url = ""
            if img_tag and img_tag.has_attr("src"):
                image_url = self._normalize_url(img_tag["src"])
            
            # ============================================================
            # 4. MARCA Y FABRICANTE
            # ============================================================
            from .utils.brand_extractor import extract_brand, extract_manufacturer
            
            brand = extract_brand(name, category)
            
            # Manufacturer solo para tarjetas gráficas
            manufacturer = ""
            if category == "tarjetas-graficas":
                manufacturer = extract_manufacturer(name)
            
            # ============================================================
            # 5. RETORNAR DATOS
            # ============================================================
            return {
                "name": name,
                "url": url,
                "price": price,
                "vendor": self.VENDOR_NAME,
                "brand": brand,
                "manufacturer": manufacturer,
                "in_stock": "1",
                "image_url": image_url,
                "category": category
            }
            
        except Exception as e:
            logger.error(f"Error extrayendo producto: {e}")
            return None
    
    def _get_product_specs_text(self, driver, url: str) -> str:
        """
        Obtiene las especificaciones de un producto en Coolmod.
        """
        driver.get(url)
        time.sleep(self.WAIT_TIME)
        
        soup = BeautifulSoup(driver.page_source, "html.parser")
        
        # En Coolmod, las especificaciones están en divs "collapse-content"
        specs_divs = soup.find_all("div", class_="collapse-content")
        
        # Unir todo el texto de especificaciones
        specs_text = "\n".join(div.get_text(strip=True) for div in specs_divs)
        
        return specs_text
    
    def _has_next_page(self, soup: BeautifulSoup) -> bool:
        """
        Verifica si hay página siguiente en Coolmod.
        """
        pagination = soup.find("ul", class_="pagination-container")
        if not pagination:
            return False
        
        next_button = pagination.find("button", class_="next-button")
        
        return next_button and "disabled" not in next_button.get("class", [])
    
    # ========================================================================
    # MÉTODOS AUXILIARES ESPECÍFICOS DE COOLMOD
    # ========================================================================
    
    def _clean_product_name(self, name: str, category: str) -> str:
        """
        Limpia el nombre del producto según la categoría.
        """
        if category == "procesadores":
            return self._clean_processor_name(name)
        elif category == "tarjetas-graficas":
            return self._clean_graphics_card_name(name)
        # Para otras categorías, retornar el nombre sin cambios
        return name
    
    def _clean_processor_name(self, raw_name: str) -> str:
        """
        Limpia el nombre de un procesador.
        
        Extrae solo la parte relevante del nombre (ej: "Intel Core i5-13600K")
        eliminando términos como "Procesador", "Boxed", etc.
        """
        # Patrones para capturar nombres de procesadores
        patterns = [
            # Intel Core i5-13600K, i7-14700K, etc.
            r"(Intel\s+Core(?:\s+\w+)?\s+\w+\s+\d+(?:\w+)?)",
            # AMD Ryzen 5 7600X, Ryzen 7 7700X3D, etc.
            r"(AMD\s+Ryzen(?:\s+\w+)?\s+\d+(?:\s+\w+\d+)?(?:\s*(?:X3D|X|GT))?)"
        ]
        
        for pattern in patterns:
            match = re.search(pattern, raw_name, re.IGNORECASE)
            if match:
                return match.group(1)
        
        # Si no coincide con ningún patrón, hacer limpieza genérica
        cleaned = raw_name
        
        # Términos a eliminar
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
        """
        Limpia el nombre de una tarjeta gráfica eliminando sufijos innecesarios.

        Entrada: "MSI GeForce RTX 4070 Gaming X Trio 12GB GDDR6X DLSS3"
        Salida:  "MSI GeForce RTX 4070 Gaming X Trio"
        """
        cleaned = raw_name

        # Términos a eliminar (sufijos y extras innecesarios)
        remove_terms = [
            r"Tarjeta\s+[Gg]ráfica\s+",
            r"\s+-\s+Tarjeta\s+gráfica.*$",
            r"\s+\d+GB\s+GDDR\d+X?",
            r"\s+GDDR\d+X?",
            r"\s+\d+GB",
            r"\s+DLSS\s?\d*",
            r"\s+OC\s+\d+GB",
            r"\s+-\s+\d+GB.*$",
            r"\s+Ray\s+Tracing",
            r"\s+RGB",
            r"\s+\d+\s*bits?",
        ]

        for term in remove_terms:
            cleaned = re.sub(term, "", cleaned, flags=re.IGNORECASE)

        return cleaned.strip()