# Logica Lector

1. Mandar rfid
2. Verificar si ya existe (en la tabla rfid_read)
    SI
        UPDATE ALL enabled = 0
        UPDATE rfid enabled = 1 -- habilitar el rfid
    NO
        UPDATE ALL enabled = 0
        CREATE rfid y habilitar -- crear y habilitar rfid

3. Llamar al rfid habilitado
    // Verificar si ya esta en uso la tarjeta
    SI
        mandar un mensaje de ya es en uso por X usuario
        status false
    NO
        mandar el rfid y mensaje libre
        status true
