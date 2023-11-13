DROP MATERIALIZED VIEW IF EXISTS "capad_greater_melbourne" CASCADE;

CREATE MATERIALIZED VIEW "public"."capad_greater_melbourne"
AS
SELECT "CAPAD2020_terrestrial".id,
       "CAPAD2020_terrestrial".geom,
       "CAPAD2020_terrestrial".objectid,
       "CAPAD2020_terrestrial".pa_id,
       "CAPAD2020_terrestrial".pa_pid,
       "CAPAD2020_terrestrial".name,
       "CAPAD2020_terrestrial".type,
       "CAPAD2020_terrestrial".type_abbr,
       "CAPAD2020_terrestrial".iucn,
       "CAPAD2020_terrestrial".nrs_pa,
       "CAPAD2020_terrestrial".gaz_area,
       "CAPAD2020_terrestrial".gis_area,
       "CAPAD2020_terrestrial".gaz_date,
       "CAPAD2020_terrestrial".latest_gaz,
       "CAPAD2020_terrestrial".state,
       "CAPAD2020_terrestrial".authority,
       "CAPAD2020_terrestrial".datasource,
       "CAPAD2020_terrestrial".governance,
       "CAPAD2020_terrestrial".comments,
       "CAPAD2020_terrestrial".environ,
       "CAPAD2020_terrestrial".overlap,
       "CAPAD2020_terrestrial".mgt_plan,
       "CAPAD2020_terrestrial".res_number,
       "CAPAD2020_terrestrial".epbc,
       "CAPAD2020_terrestrial".longitude,
       "CAPAD2020_terrestrial".latitude,
       "CAPAD2020_terrestrial".shape_leng,
       "CAPAD2020_terrestrial".shape_area
FROM "CAPAD2020_terrestrial",
     mw_boundary_simplified
WHERE ST_Intersects("CAPAD2020_terrestrial".geom, mw_boundary_simplified.geom)

WITH NO DATA;

CREATE INDEX "capad_greater_melbourne_geom_idx"
    ON "public"."capad_greater_melbourne" USING gist ("geom" "public"."gist_geometry_ops_2d");


