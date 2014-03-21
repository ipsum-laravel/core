# Instalation du package d'administration d'Ipsum


## Créations des tables

    php artisan migrate --package="ipsum/core"

## Population des tables

Actuelement impossible de la faire via le package
Mettre les fichier dans le repertoire seed

    php artisan db::seed

