# ENTITIES

- ENFERMERA
  - id_enfermera
  - rfid (id tarjeta)
  - nombre
  - apellido
  - ci
  - celular

- EMERGENCIA
  - id_emergencia
  - id_cama (id_arduino)
  - id_enfermera (la que atendio)
  - hora_inicio
  - hora_fin

- CAMA
  - id_cama (id_arduino)
  - label

- SALA
  - id_sala
  - label

- SALA_CAMA
  - id_sala_cama
  - id_sala
  - id_cama

- HISTORIAL
  - id_historial
  - id_emergencia
  - estado (true)

- RFID_DISPONABILIDAD
  - rfid
  - estado (ASIGNADO,LIBRE, BLOQUEADO)