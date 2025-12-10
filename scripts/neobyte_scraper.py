"""
Scraper específico para NeoByte.
"""

from .base_scraper import BaseScraper
from bs4 import BeautifulSoup
from typing import Dict, Optional
import re
import time
import logging

logger = logging.getLogger(__name__)

class NeoByteScraper(BaseScraper):
    """Scraper específico para NeoByte."""

    BASE_URL = "https://www.neobyte.es"
    VENDOR_NAME = "neobyte"
    WAIT_TIME = 3

    CATEGORY_URLS = {
        "procesadores": "/procesadores-107",
        "tarjetas-graficas": "/tarjetas-graficas-111",
        "placas-base": "/placas-base-106",
        "discos-hdd": "/discos-duros-3-5-sata-142",
        "discos-ssd": "/discos-duros-ssd-144",
        "memorias-ram": "/memorias-ram-108",
        "fuentes-alimentacion": "/fuentes-de-alimentacion-113"
    }

    def _get_category_url(self, category: str, page: int = 1) -> str:
        """Construye la URL para NeoByte con paginación."""
        if category not in self.CATEGORY_URLS:
            raise ValueError(f"Categoría no válida: {category}")

        base_url = f"{self.BASE_URL}{self.CATEGORY_URLS[category]}"
        return f"{base_url}?page={page}"

    def _get_product_container_selector(self) -> tuple:
        """
        Selector de productos en NeoByte.
        Retorna (tag, attributes) para usar en soup.find_all()
        """
        return ("article", {"class": "product-miniature"})

    def _extract_product_from_html(self, html_product, category: str) -> Optional[Dict]:
        """
        Extrae datos de un producto de NeoByte.
        """
        try:
            # ============================================================
            # 1. NOMBRE Y URL
            # ============================================================
            title_span = html_product.find("span", class_="product-title")
            if not title_span:
                logger.warning("No se encontró elemento 'product-title'")
                return None
        
            a_elem = title_span.find("a")
            if not a_elem:
                logger.warning("No se encontró enlace en 'product-title'")
                return None
            
            # Nombre del producto
            name = a_elem.get_text(strip=True)
            
            # URL del producto
            url = self._normalize_url(a_elem.get("href", ""))
            
            # ============================================================
            # 2. PRECIO
            # ============================================================
            price_span = html_product.find("span", class_="product-price")
            
            if price_span:
                price_raw = price_span.get("content", "")
                
                if price_raw:
                    price = price_raw
                else:
                    price_text = price_span.get_text(strip=True)
                    price = self._normalize_price(price_text)
            else:
                price = "0.00"
                logger.warning(f"No se encontró precio para {name}")
            
            # ============================================================
            # 3. IMAGEN
            # ============================================================
            img_tag = html_product.find("img", class_="js-lazy-product-image")
            image_url = ""

            if img_tag:
                image_url = img_tag.get("data-src") or img_tag.get("src", "")
                image_url = self._normalize_url(image_url)
            
            # ============================================================
            # 4. DISPONIBILIDAD
            # ============================================================
            stock_span = html_product.find("span", class_="product-available")

            in_stock = "0"
            if stock_span:
                stock_text = stock_span.get_text(strip=True).lower()
                if "en stock" in stock_text or "disponible" in stock_text:
                    in_stock = "1"
            else:
                in_stock = "1"
            
            # ============================================================
            # 5. MARCA Y FABRICANTE
            # ============================================================
            from .utils.brand_extractor import extract_brand, extract_manufacturer
            
            brand = extract_brand(name, category)

            manufacturer = ""
            if category == "tarjetas-graficas":
                manufacturer = extract_manufacturer(name)
            
            # ============================================================
            # 6. LIMPIAR NOMBRE
            # ============================================================
            name = self._clean_product_name(name, category)

            # ============================================================
            # 7. RETORNAR DATOS
            # ============================================================
            return {
                "name": name,
                "url": url,
                "price": price,
                "vendor": self.VENDOR_NAME,
                "brand": brand,
                "manufacturer": manufacturer,
                "in_stock": in_stock,
                "image_url": image_url,
                "category": category
            }

        except Exception as e:
            logger.error(f"Error extrayendo producto: {e}")
            return None
    
    def _get_product_specs_text(self, driver, url: str) -> str:
        driver.get(url)
        time.sleep(self.WAIT_TIME)
        
        soup = BeautifulSoup(driver.page_source, "html.parser")
        specs_sections = []

        product_description = soup.find("div", class_="product-description")
        
        if product_description:
            # Extraer todas las listas <ul> dentro de product-description
            specs_lists = product_description.find_all("ul")
            
            for ul in specs_lists:
                # Extraer cada <li> de la lista
                items = ul.find_all("li")
                for item in items:
                    specs_sections.append(item.get_text(strip=True))
        
        # ============================================================
        # FALLBACK 1: Buscar en toda la sección product-description-section
        # ============================================================
        if not specs_sections:
            description_section = soup.find("section", class_="product-description-section")
            if description_section:
                # Buscar listas en toda la sección
                all_lists = description_section.find_all("ul")
                for ul in all_lists:
                    items = ul.find_all("li")
                    for item in items:
                        text = item.get_text(strip=True)
                        # Evitar duplicados
                        if text and text not in specs_sections:
                            specs_sections.append(text)
        
        # ============================================================
        # FALLBACK 2: Buscar en el div rte-content (descripción completa)
        # ============================================================
        if not specs_sections:
            rte_content = soup.find("div", class_="rte-content")
            if rte_content:
                specs_sections.append(rte_content.get_text(strip=True))
        
        # Unir todas las especificaciones con saltos de línea
        specs_text = "\n".join(specs_sections)
        
        if not specs_text:
            logger.warning(f"No se encontraron especificaciones en {url}")
        
        return specs_text

    def _has_next_page(self, soup: BeautifulSoup) -> bool:
        next_link = soup.find("a", class_="next")
        if next_link:
            # Verificar que no esté deshabilitado
            if "disabled" not in next_link.get("class", []):
                return True

    
    def _clean_product_name(self, name: str, category: str) -> str:
        """
        Limpia el nombre del producto eliminando sufijos innecesarios.
        
        Args:
            name: Nombre original del producto
            category: Categoría del producto
        
        Returns:
            Nombre limpio sin sufijos
        """
        cleaned = name
        
        # Sufijos comunes a eliminar según categoría
        if category == "tarjetas-graficas":
            remove_terms = [
                r"\s*-\s*Tarjeta gráfica$",
                r"\s+\d+GB\s+GDDR\d+X?",           # " 12GB GDDR6X"
                r"\s+GDDR\d+X?",                   # " GDDR6X"
                r"\s+\d+GB",                       # " 12GB"
                r"\s+DLSS\s?\d*",                  # " DLSS3", " DLSS"
                r"\s+Ray\s+Tracing",               # " Ray Tracing"
                r"\s+RGB",                         # " RGB"
            ]
        elif category == "procesadores":
            remove_terms = [
                r"\s*-\s*Procesador$",
                r"\s*-\s*Procesador\s+\w+$",
                r"\s+Socket\s+\w+",
                r"\s+\d+\.\d+GHz",
                r"\s+Boxed",
                r"\s+BOX",
                r"\s+Bulk",
            ]
        else:
            # Sufijos genéricos para otras categorías
            remove_terms = [
                r"\s*-\s*Tarjeta gráfica$",
                r"\s*-\s*Procesador$",
                r"\s*-\s*Procesador\s+\w+$"
                r"\s*-\s*Placa base$",
                r"\s*-\s*Disco duro$",
                r"\s*-\s*SSD$",
                r"\s*-\s*Memoria RAM$",
                r"\s*-\s*Fuente de alimentación$",
            ]
        
        # Aplicar limpieza
        for pattern in remove_terms:
            cleaned = re.sub(pattern, "", cleaned, flags=re.IGNORECASE)
        
        return cleaned.strip()


    def _normalize_price(self, price_str: str) -> str:
        """
        Normaliza el precio de Neobyte.
        
        Neobyte usa formato español: "65,90 €"
        Lo convertimos a: "65.90"
        """
        # Eliminar símbolo de euro y espacios
        price_str = re.sub(r'[€\s]', '', price_str)
        
        if '.' in price_str and ',' in price_str:
            price_str = price_str.replace('.', '')
            price_str = price_str.replace(',', '.')
        elif ',' in price_str:
            price_str = price_str.replace(',', '.')
        
        try:
            # Validar que sea un número válido
            float(price_str)
            return price_str
        except ValueError:
            logger.warning(f"Precio inválido: {price_str}")
            return "0.00"