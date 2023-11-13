DROP MATERIALIZED VIEW IF EXISTS "snipe_all_site_data_cleaned" CASCADE;

CREATE MATERIALIZED VIEW "public"."snipe_all_site_data_cleaned"
AS
SELECT row_number() OVER (ORDER BY snipe_all_site_data.date)                                                     AS id,
       snipe_all_site_data.season,
       snipe_all_site_data.date,
       snipe_all_site_data."norm.date",
       snipe_all_site_data.site,
       snipe_all_site_data."reporting.area",
       snipe_all_site_data.state,
       snipe_all_site_data.observer,
       snipe_all_site_data.transect,
       snipe_all_site_data."surv.area",
       snipe_all_site_data.count,
       snipe_all_site_data.snipedens,
       snipe_all_site_data.site_newlookup,
       snipe_all_site_data.region,
       snipe_all_site_data.lat,
       snipe_all_site_data.long,
       snipe_all_site_data.landuse,
       snipe_all_site_data.protected,
       snipe_all_site_data."av.spr.rain",
       snipe_all_site_data."wetarea.ha",
       snipe_all_site_data."look.ok",
       snipe_all_site_data.reason_no,
       snipe_all_site_data."no.surveys",
       snipe_all_site_data."1per5rule",
       ST_Point((snipe_all_site_data.long)::double precision, (snipe_all_site_data.lat)::double precision, 4326) AS geom
FROM snipe_all_site_data

WITH NO DATA;

CREATE INDEX "snipe_all_site_data_cleaned_geom_idx"
    ON "public"."snipe_all_site_data_cleaned" USING gist ("geom" "public"."gist_geometry_ops_2d");


