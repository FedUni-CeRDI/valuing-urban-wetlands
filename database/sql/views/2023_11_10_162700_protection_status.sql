DROP MATERIALIZED VIEW IF EXISTS "protection_status" CASCADE;

CREATE MATERIALIZED VIEW "public"."protection_status"
AS
SELECT (protection_status.geom)::geometry(MultiPolygon, 3857) AS geom,
       protection_status.type
FROM (
    SELECT capad_greater_melbourne.geom,
           capad_greater_melbourne.type
    FROM capad_greater_melbourne
    UNION
    SELECT ST_Transform(ramsar_update_nov22.geom, 3857) AS geom,
           'Ramsar'::character varying                  AS type
    FROM ramsar_update_nov22
) protection_status

WITH NO DATA;

CREATE INDEX "protection_status_geom_idx"
    ON "public"."protection_status" USING gist ("geom" "public"."gist_geometry_ops_2d");


