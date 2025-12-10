from .patterns import SEARCH_PATTERNS, KNOWN_SOCKETS, add_pattern, get_patterns
from .brand_extractor import (
    BRAND_PATTERNS,
    GRAPHICS_CARD_MANUFACTURERS,
    extract_brand,
    extract_manufacturer,
    add_brand,
    add_manufacturer
)

__all__ = [
    'SEARCH_PATTERNS',
    'KNOWN_SOCKETS',
    'add_pattern',
    'get_patterns',

    'BRAND_PATTERNS',
    'GRAPHICS_CARD_MANUFACTURERS',
    'extract_brand',
    'extract_manufacturer',
    'add_brand',
    'add_manufacturer'
]