CREATE USER "foss4g" LOGIN PASSWORD 'foss4g';
CREATE DATABASE "foss4g";
GRANT ALL PRIVILEGES ON DATABASE "foss4g" TO "foss4g";
\c foss4g
CREATE EXTENSION postgis;
