DROP MATERIALIZED VIEW IF EXISTS "vluis_3857" CASCADE;

CREATE MATERIALIZED VIEW "public"."vluis_3857"
AS
SELECT aurin_vluis2017_7899.id,
       ST_Transform(aurin_vluis2017_7899.geom, 3857) AS geom,
       aurin_vluis2017_7899.lga,
       aurin_vluis2017_7899.cma,
       aurin_vluis2017_7899.lu_code,
       aurin_vluis2017_7899.lc_code_16,
       aurin_vluis2017_7899.lu_desc,
       aurin_vluis2017_7899.alumv8,
       aurin_vluis2017_7899.lu_code_a,
       aurin_vluis2017_7899.lc_desc_16,
       aurin_vluis2017_7899.lu_description_a,
       aurin_vluis2017_7899.lc_code_17,
       aurin_vluis2017_7899.lc_desc_17,
       alumv8.name                                   AS alumv8_l2
FROM aurin_vluis2017_7899
    LEFT JOIN alumv8 ON
        (alumv8.code)::text = (SUBSTRING((aurin_vluis2017_7899.lu_code_a)::text, 0, 4) || '.0'::text)

WITH NO DATA;

ALTER MATERIALIZED VIEW "public"."vluis_3857" OWNER TO "aurin_user";

CREATE UNIQUE INDEX "vluis_3857_id_idx"
    ON "public"."vluis_3857" USING btree ("id" "pg_catalog"."int4_ops" ASC NULLS LAST);

CREATE INDEX "vluis_3857_geom_idx"
    ON "public"."vluis_3857" USING gist ("geom" "public"."gist_geometry_ops_2d");


