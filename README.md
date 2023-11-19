# Dependencies
- Spatial delivery is reliant on GeoServer with a PostGIS database store
- PHP 8.2

# Deployment
## Create database
- Defaults expect PostgreSQL + PostGIS database
  - Name: `valuing_urban_wetlands`
  - Owner: `vuw_user`
  - All data imported in tables as outlined in datasets below

## Setup .env
- Copy `.env.example` to `.env`
- Provide a valid ALA API Client ID and Secret
- Provide a valid GOOGLE_ANALYTICS_KEY

## Run local file cache commands
Below console commands should to be run to generate needed cache files.

```bash
php8.2 artisan frog:info
php8.2 artisan waterbird:info
```

## Run database seeder
> **Note**: This requires all tables to be created as per datasets below

> **Warning**: This will take around an hour to run
```bash
php8.2 artisan migrate --step
```


# Datasets
##  Melbourne Water Corporation
- URL: https://datashare.maps.vic.gov.au/search?md=9ee9d41a-93dc-5e13-9ad8-69c64c22249d
- Projection: 3857
- As table in PostGIS: mw_boundary_simplified

## Latham's Snipe records - Occurrence records download on 2023-08-23
- URL: https://doi.ala.org.au/doi/10.26197/ala.70bfe50b-ff75-4e0b-99c7-4242b11e76b3
- As table in PostGIS: ala_snipe_records

## States and Territories - 2021 - Shapefile
- URL: https://www.abs.gov.au/statistics/standards/australian-statistical-geography-standard-asgs-edition-3/jul2021-jun2026/access-and-downloads/digital-boundary-files
- Projection: 3857
- As table in PostGIS: state_boundaries

## Collaborative Australian Protected Areas Database (CAPAD) 2020 - Terrestrial
- URL: https://www.environment.gov.au/fed/catalog/search/resource/details.page?uuid=%7B58B661D1-2C9C-492C-9868-CE5285AA036A%7D
- Projection: 3857
- As table in PostGIS: CAPAD2020_terrestrial

## Ramsar Wetlands of Australia
- URL: http://www.environment.gov.au/fed/catalog/search/resource/details.page?uuid=%7BF49BFC55-4306-4185-85A9-A5F8CD2380CF%7D
- Projection: 4283
- As table in PostGIS: ramsar_update_nov22


## Latham's Snipe Project data
- URL: https://data2.cerdi.edu.au/dataset/45f4e18b-88cc-458e-a04e-0153acd74c57
- Projection: 4326
- As table in PostGIS: snipe_all_site_data

## !! Wetlands 
- URL: To be supplied to AURIN
- Projection: 3857
- As table in PostGIS: melbourne_wetlands_3857


## Vicmap Planning - Planning Scheme Zone Polygon
- URL: https://datashare.maps.vic.gov.au/search?md=e61471a1-277d-54ea-9a75-3cc9b1a86800
- Projection: GDA94 VicGrid / 3111
- As table in PostGIS: aurin_plan_zone_3111

## Vicmap Planning - Planning Scheme Overlay Polygon
- URL: https://datashare.maps.vic.gov.au/search?md=47847f20-b7ab-5cb9-8baf-c88ed6500988
- Projection: GDA94 VicGrid / 3111
- As table in PostGIS: aurin_plan_overlay_3111

## Victorian Land Use Information System 2016-2017
- URL: https://datashare.maps.vic.gov.au/search?md=0ba63dff-edac-5a95-99af-1cd616a81f76
- Projection: GDA2020 VicGrid / 7899
- As table in PostGIS: aurin_vluis2017_7899


## Configuring GeoServer
The portal requires a GeoServer instance to deliver map tiles for the wetlands and boundary.

This application requires the GeoServer Workspace be called `valuing_urban_wetlands`

See reference configuration files in `resources/geoserver`. Except database (above PostGIS) configuration
params in `resources/geoserver/valuing_urban_wetlands/postgis_valuing_urban_wetlands/datastore.xml` the
entire `valuing_urban_wetlands` can be dropped into you GeoServer workspaces directory.

# TODO

[//]: # (Link to datasets &#40;wetlands and snipe data&#41; on AURIN)

