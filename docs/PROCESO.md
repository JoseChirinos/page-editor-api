
Proceso:

1. Arduino(id_arduino) manda el id_cama al server

2. Llega al server
  TABLA -> EMERGENCIA
  - hora_inicio
  - id_cama
  RETORNAR
  id_emergencia

3. Habilitar Cama Frontend (esperar encargada | enfermera | Rojo)
  - Que se actualize cada 5seg

4. Llega (enfermera)  - pasa la tarjeta y se designa la enfermera con su codigo rfid
  TABLA -> EMERGENCIA
  - enfermeraID (UPDATE)


5. CRUD
  enfermeras - rfid
  emergencias
  camas - id_arduino
  salas

6. Pantalla principal
