DROP MATERIALIZED VIEW IF EXISTS "wetlands" CASCADE;

CREATE MATERIALIZED VIEW "public"."wetlands"
AS
SELECT wetlands.id,
       wetlands.norm_sitnm                            AS name,
       wetlands.geom,
       JSON_AGG(DISTINCT protection_status.type)      AS protection_status,
       JSON_AGG(DISTINCT state_boundaries.ste_name21) AS states,
       JSON_AGG(DISTINCT vluis_3857.alumv8_l2)        AS land_use
FROM (((melbourne_wetlands_3857 wetlands
    LEFT JOIN protection_status ON (st_intersects(protection_status.geom, wetlands.geom)))
    LEFT JOIN state_boundaries ON (st_intersects(state_boundaries.geom, wetlands.geom)))
    LEFT JOIN vluis_3857 ON (ST_Intersects(wetlands.geom, vluis_3857.geom)))
GROUP BY wetlands.id, wetlands.geom, wetlands.norm_sitnm

WITH NO DATA;

CREATE UNIQUE INDEX "wetlands_id_idx"
    ON "public"."wetlands" USING btree ("id" "pg_catalog"."int4_ops" ASC NULLS LAST);

CREATE INDEX "wetlands_geom_idx"
    ON "public"."wetlands" USING gist ("geom" "public"."gist_geometry_ops_2d");


