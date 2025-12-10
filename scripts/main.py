import os
import logging
from dotenv import load_dotenv
from pathlib import Path

from .coolmod_scraper import CoolmodScraper
from .neobyte_scraper import NeoByteScraper
from .orchestrator import ScraperOrchestrator


def setup_logging():
    """Configura el logging para la aplicación."""
    logging.basicConfig(
        level=logging.INFO,
        format="%(asctime)s - %(levelname)s - [%(name)s] - %(message)s",
        handlers=[
            logging.FileHandler("scraper.log"),
            logging.StreamHandler()
        ]
    )
    # Silenciar logs muy verbosos de selenium y otros módulos
    logging.getLogger("selenium").setLevel(logging.WARNING)
    logging.getLogger("urllib3").setLevel(logging.WARNING)


def load_env_config():
    """Carga la configuración de la base de datos desde el archivo .env."""
    env_path = Path(__file__).parent.parent / ".env"
    load_dotenv(dotenv_path=env_path)
    
    db_config = {
        "host": os.getenv("DB_HOST", "localhost"),
        "port": int(os.getenv("DB_PORT", 3306)),
        "user": os.getenv("DB_USERNAME"),
        "password": os.getenv("DB_PASSWORD"),
        "database": os.getenv("DB_DATABASE")
    }
    
    # Devuelve la configuración solo si todos los campos esenciales están presentes
    if all(db_config.values()):
        return db_config
    return None

def main():
    """Función principal para ejecutar el proceso de scraping."""
    setup_logging()
    
    # --- Configuración del Scraper ---
    chrome_binary = "/usr/bin/chromium-browser"
    categories_to_scrape = ["procesadores", "tarjetas-graficas", "placas-base", "discos-hdd", "discos-ssd", "memorias-ram", "fuentes-alimentacion"]
    # categories_to_scrape = ["procesadores"]
    db_config = load_env_config()
    
    scrapers = [
        CoolmodScraper(headless=False, chrome_binary=chrome_binary),
        NeoByteScraper(headless=False, chrome_binary=chrome_binary)
    ]
    
    orchestrator = ScraperOrchestrator(
        scrapers=scrapers,
        categories=categories_to_scrape,
        db_config=db_config,
        save_json=True
    )
    
    orchestrator.run()

if __name__ == "__main__":
    main()