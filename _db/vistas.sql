USE emergencia_db;

-- GET ALL ROOM_BED
DROP VIEW view_room_bed;
CREATE VIEW view_room_bed AS SELECT
r.id_room ,r.label as room_label, b.id_bed, b.label as bed_label
FROM room_bed as rb
INNER JOIN bed as b
ON b.id_bed = rb.bed_id
INNER JOIN room as r
ON r.id_room = rb.room_id;