DROP MATERIALIZED VIEW IF EXISTS "ala_snipe_records_cleaned" CASCADE;

CREATE MATERIALIZED VIEW "public"."ala_snipe_records_cleaned"
AS
SELECT row_number() OVER (ORDER BY ala_snipe_records."eventDate")   AS id,
       (
           ST_Transform(
               ST_Point(
                   (ala_snipe_records."decimalLongitude")::double precision,
                   (ala_snipe_records."decimalLatitude")::double precision,
                   (
                       RIGHT(
                           (ala_snipe_records."geodeticDatum")::text, 4)
                       )::integer
               ),
               7844
           )
           )::geometry(Point, 7844)                                 AS geom,
       ala_snipe_records."individualCount",
       (ala_snipe_records."eventDate")::timestamp without time zone AS "eventDate",
       ala_snipe_records.country,
       ala_snipe_records."stateProvince",
       ala_snipe_records.county,
       ala_snipe_records.locality,
       ala_snipe_records.species,
       CASE
           WHEN ((ala_snipe_records.month)::integer <= 6) THEN
               CONCAT(
                   (((ala_snipe_records.year)::integer - 1))::text,
                   '-',
                   ala_snipe_records.year
               )
           WHEN ((ala_snipe_records.month)::integer > 6) THEN
               CONCAT(
                   ala_snipe_records.year, '-',
                   (((ala_snipe_records.year)::integer + 1))::text
               )
           ELSE
               NULL::text
           END                                                      AS season
FROM ala_snipe_records
WHERE ((ala_snipe_records."decimalLatitude")::text <> ''::text)
  AND ((ala_snipe_records."eventDate")::text <> ''::text)

WITH NO DATA;

ALTER MATERIALIZED VIEW "public"."ala_snipe_records_cleaned" OWNER TO "aurin_user";

CREATE UNIQUE INDEX "ala_snipe_records_cleaned_id_idx"
    ON "public"."ala_snipe_records_cleaned" USING btree ("id" "pg_catalog"."int8_ops" ASC NULLS LAST);

CREATE INDEX "ala_snipe_records_cleaned_geom_idx"
    ON "public"."ala_snipe_records_cleaned" USING gist ("geom" "public"."gist_geometry_ops_2d");


