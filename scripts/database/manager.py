"""
Gestor de base de datos para almacenamiento de productos.
Maneja inserciones, actualizaciones y operaciones por lotes.
"""

import mysql.connector
from mysql.connector import Error as MySQLError
import datetime
import logging
from contextlib import contextmanager
from typing import Dict, List, Any, Tuple

logger = logging.getLogger(__name__)


# ============================================================================
# CONTEXT MANAGER PARA CONEXIÓN A BD
# ============================================================================

@contextmanager
def database_connection(config: Dict[str, Any]):
    """
    Context manager para la conexión a MySQL.
    
    Uso:
        with database_connection(db_config) as (conn, cursor):
            cursor.execute("SELECT * FROM cpus")
            results = cursor.fetchall()
    
    Args:
        config: Diccionario con configuración de conexión:
            - host: Host de la BD
            - port: Puerto (default: 3306)
            - user: Usuario
            - password: Contraseña
            - database: Nombre de la base de datos
    
    Yields:
        Tupla (connection, cursor)
    
    Raises:
        MySQLError: Si hay error de conexión o consulta
    """
    connection = None
    cursor = None
    try:
        # Conectar a la base de datos
        connection = mysql.connector.connect(**config)
        cursor = connection.cursor()
        
        logger.info(f"Conexión establecida a BD: {config.get('database')}")
        
        # Yield para el código que use el context manager
        yield connection, cursor
        
        # Si todo fue bien, hacer commit
        connection.commit()
        logger.info("Commit exitoso")
        
    except MySQLError as e:
        # Si hay error, hacer rollback
        if connection:
            connection.rollback()
            logger.error(f"Rollback ejecutado debido a error: {e}")
        raise
        
    finally:
        # Siempre cerrar cursor y conexión
        if cursor:
            cursor.close()
        if connection:
            connection.close()
            logger.info("Conexión a BD cerrada")


# ============================================================================
# GESTOR DE BASE DE DATOS
# ============================================================================

class DatabaseManager:
    """
    Gestiona las operaciones de base de datos para productos.
    """
    
    # Tamaño de lote para inserciones masivas
    BATCH_SIZE = 50
    
    # Esquemas de tablas por categoría
    # Define las columnas, valores y actualizaciones para cada tipo de producto
    TABLE_SCHEMAS = {
        "procesadores": {
            "table": "cpus",
            "columns": "(created_at, updated_at, name, vendor, brand, price, in_stock, url, image, clock_speed, n_cores, n_threads, socket, tdp)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), clock_speed=VALUES(clock_speed), n_cores=VALUES(n_cores), n_threads=VALUES(n_threads), socket=VALUES(socket), tdp=VALUES(tdp), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        },
        "tarjetas-graficas": {
            "table": "graphic_cards",
            "columns": "(name, vendor, brand, price, in_stock, url, image, manufacturer, vram, mem_type, tdp, created_at, updated_at)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), manufacturer=VALUES(manufacturer), vram=VALUES(vram), mem_type=VALUES(mem_type), tdp=VALUES(tdp), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        },
        "placas-base": {
            "table": "motherboards",
            "columns": "(name, vendor, brand, price, in_stock, url, image, socket, chipset, size_format, created_at, updated_at)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), socket=VALUES(socket), chipset=VALUES(chipset), size_format=VALUES(size_format), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        },
        "discos-hdd": {
            "table": "storage_devices",
            "columns": "(name, vendor, brand, price, in_stock, url, image, type, storage, created_at, updated_at)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), type=VALUES(type), storage=VALUES(storage), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        },
        "discos-ssd": {
            "table": "storage_devices",
            "columns": "(name, vendor, brand, price, in_stock, url, image, type, storage, created_at, updated_at)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), type=VALUES(type), storage=VALUES(storage), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        },
        "memorias-ram": {
            "table": "rams",
            "columns": "(name, vendor, brand, price, in_stock, url, image, type, n_modules, module_capacity, frequency, latency, created_at, updated_at)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), type=VALUES(type), n_modules=VALUES(n_modules), module_capacity=VALUES(module_capacity), frequency=VALUES(frequency), latency=VALUES(latency), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        },
        "fuentes-alimentacion": {
            "table": "power_supplies",
            "columns": "(name, vendor, brand, price, in_stock, url, image, power, certification, created_at, updated_at)",
            "values": "(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            "update": "updated_at=VALUES(updated_at), price=VALUES(price), power=VALUES(power), certification=VALUES(certification), in_stock=VALUES(in_stock), url=VALUES(url), image=VALUES(image)"
        }
    }
    
    # ========================================================================
    # MÉTODO PRINCIPAL
    # ========================================================================
    
    @staticmethod
    def save_batch(cursor, connection, results: Dict, category: str):
        """
        Guarda un lote de productos en la base de datos.
        
        Usa INSERT ... ON DUPLICATE KEY UPDATE para:
        - Insertar productos nuevos
        - Actualizar productos existentes (basado en el nombre)
        
        Args:
            cursor: Cursor de MySQL
            connection: Conexión de MySQL
            results: Diccionario con productos {nombre: datos}
            category: Categoría de productos (procesadores, tarjetas-graficas, etc.)
        
        Raises:
            ValueError: Si la categoría no es válida
        """
        if category not in DatabaseManager.TABLE_SCHEMAS:
            raise ValueError(f"Categoría no soportada: {category}")
        
        # Obtener esquema de la tabla
        schema = DatabaseManager.TABLE_SCHEMAS[category]
        
        # Construir query SQL
        query = f"""
            INSERT INTO {schema['table']} {schema['columns']}
            VALUES {schema['values']}
            ON DUPLICATE KEY UPDATE {schema['update']}
        """
        
        logger.info(f"Guardando {len(results)} productos de {category} en tabla {schema['table']}")

        now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        
        batch = []
        total_saved = 0
        
        for name, data in results.items():
            # Preparar valores según la categoría
            values = DatabaseManager._prepare_values(category, name, data, now)
            batch.append(values)
            
            # Si el lote alcanza el tamaño máximo, insertar
            if len(batch) >= DatabaseManager.BATCH_SIZE:
                DatabaseManager._execute_batch(cursor, query, batch)
                connection.commit()
                total_saved += len(batch)
                logger.info(f"Guardados {total_saved}/{len(results)} productos")
                batch = []
        
        # Insertar registros restantes
        if batch:
            DatabaseManager._execute_batch(cursor, query, batch)
            connection.commit()
            total_saved += len(batch)
        
        logger.info(f"Se guardaron {total_saved} productos de {category} en la BD")
    
    # ========================================================================
    # MÉTODOS AUXILIARES PRIVADOS
    # ========================================================================
    
    @staticmethod
    def _prepare_values(category: str, name: str, data: Dict, now: str) -> tuple:
        """
        Prepara los valores para inserción según la categoría.
        
        Args:
            category: Categoría del producto
            name: Nombre del producto
            data: Diccionario con datos del producto
            now: Timestamp actual
        
        Returns:
            Tupla con valores en el orden correcto para la categoría
        """
        if category == "procesadores":
            return (
                now, now,  # created_at, updated_at
                name,
                data.get("vendor", "unknown"),
                data.get("brand", "unknown"),
                data.get("price", "0.00"),
                data.get("in_stock", "1"),
                data.get("url", ""),
                data.get("image_url", ""),
                data.get("clock_speed", ""),
                data.get("n_cores", "0"),
                data.get("n_threads", "0"),
                data.get("socket", ""),
                data.get("tdp", "0")
            )
        
        elif category == "tarjetas-graficas":
            return (
                name,
                data.get("vendor", "unknown"),
                data.get("brand", "unknown"),
                data.get("price", "0.00"),
                data.get("in_stock", "1"),
                data.get("url", ""),
                data.get("image_url", ""),
                data.get("manufacturer", ""),
                data.get("vram", ""),
                data.get("mem_type", ""),
                data.get("tdp", "0"),
                now, now  # created_at, updated_at
            )
        
        elif category == "placas-base":
            return (
                name,
                data.get("vendor", "unknown"),
                data.get("brand", "unknown"),
                data.get("price", "0.00"),
                data.get("in_stock", "1"),
                data.get("url", ""),
                data.get("image_url", ""),
                data.get("socket", ""),
                data.get("chipset", ""),
                data.get("form_factor", ""),
                now, now  # created_at, updated_at
            )
        
        elif category in ["discos-hdd", "discos-ssd"]:
            return (
                name,
                data.get("vendor", "unknown"),
                data.get("brand", "unknown"),
                data.get("price", "0.00"),
                data.get("in_stock", "1"),
                data.get("url", ""),
                data.get("image_url", ""),
                data.get("type", ""),
                data.get("storage", ""),
                now, now  # created_at, updated_at
            )
        
        elif category == "memorias-ram":
            return (
                name,
                data.get("vendor", "unknown"),
                data.get("brand", "unknown"),
                data.get("price", "0.00"),
                data.get("in_stock", "1"),
                data.get("url", ""),
                data.get("image_url", ""),
                data.get("type", ""),
                data.get("n_modules", ""),
                data.get("module_capacity", ""),
                data.get("frequency", ""),
                data.get("latency", ""),
                now, now  # created_at, updated_at
            )
        
        elif category == "fuentes-alimentacion":
            return (
                name,
                data.get("vendor", "unknown"),
                data.get("brand", "unknown"),
                data.get("price", "0.00"),
                data.get("in_stock", "1"),
                data.get("url", ""),
                data.get("image_url", ""),
                data.get("power", ""),
                data.get("certification", ""),
                now, now  # created_at, updated_at
            )
        
        else:
            raise ValueError(f"Categoría no soportada: {category}")
    
    @staticmethod
    def _execute_batch(cursor, query: str, batch: List[tuple]):
        """
        Ejecuta un lote de inserciones.
        
        Si falla el batch completo, intenta insertar uno por uno.
        
        Args:
            cursor: Cursor de MySQL
            query: Query SQL a ejecutar
            batch: Lista de tuplas con valores
        """
        try:
            # Intentar inserción masiva
            cursor.executemany(query, batch)
            logger.debug(f"Batch de {len(batch)} registros insertado")
            
        except MySQLError as e:
            logger.error(f"Error en batch insert: {e}")
            logger.info("Intentando inserciones individuales...")
            
            # Si falla el batch, intentar uno por uno
            failed_count = 0
            for values in batch:
                try:
                    cursor.execute(query, values)
                except MySQLError as err:
                    logger.error(f"Error al insertar registro: {err}")
                    logger.error(f"Valores: {values[2] if len(values) > 2 else 'unknown'}")  # Nombre del producto
                    failed_count += 1
            
            if failed_count > 0:
                logger.warning(f"{failed_count}/{len(batch)} registros fallaron")
            else:
                logger.info(f"Todos los registros insertados individualmente")


# ============================================================================
# FUNCIONES DE UTILIDAD ADICIONALES
# ============================================================================

def test_connection(config: Dict[str, Any]) -> bool:
    """
    Prueba la conexión a la base de datos.
    
    Args:
        config: Configuración de conexión
    
    Returns:
        True si la conexión es exitosa, False en caso contrario
    """
    try:
        with database_connection(config) as (conn, cursor):
            cursor.execute("SELECT 1")
            result = cursor.fetchone()
            logger.info("✓ Conexión a BD exitosa")
            return result[0] == 1
    except Exception as e:
        logger.error(f"✗ Error al conectar a BD: {e}")
        return False


def get_product_count(cursor, category: str) -> int:
    """
    Obtiene el número de productos en una categoría.
    
    Args:
        cursor: Cursor de MySQL
        category: Categoría a consultar
    
    Returns:
        Número de productos
    """
    if category not in DatabaseManager.TABLE_SCHEMAS:
        raise ValueError(f"Categoría no válida: {category}")
    
    table = DatabaseManager.TABLE_SCHEMAS[category]["table"]
    cursor.execute(f"SELECT COUNT(*) FROM {table}")
    result = cursor.fetchone()
    return result[0] if result else 0