"""
Paquete principal de scrapers.
Contiene scrapers específicos de cada vendor y componentes compartidos.
"""

from .coolmod_scraper import CoolmodScraper

__all__ = [
    'CoolmodScraper',
    'NeobyteScraper',
]