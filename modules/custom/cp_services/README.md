# CP Services - a catalogue of services for ICOS

## Dependencies

- Relies on module `cp_contacts` for `field_cpservices_contacts` and its associated template, `field--node--field-cpservices-contacts.html.twig`, which loads the CSS library from the `cp_contacts` module.
- Relies on module [`better_exposed_filters`](https://www.drupal.org/project/better_exposed_filters), which is necessary for the Service Catalogue view to function properly.

## Post installation, manual configuration

1. You must define the taxonomy term vocabularies for organisations (`/admin/structure/taxonomy/manage/organisations/add`)
2. Default images are provided (in the `img` directory) but not installed automatically. You should configure these as well:
    - Header (`/admin/structure/types/manage/service/fields/node.service.field_cpservices_header`): `default_header.png`, alt: Service header: ICOS Integrated Carbon Observation System
    - Thumbnail (`/admin/structure/types/manage/service/fields/node.service.field_cpservices_thumbnail`): `default_thumbnail.png`, alt: Service thumbnail: ICOS Integrated Carbon Observation System

## Usage

Services are created through the "Add content" menu. Once created, the service catalogue view is available at `/service-catalogue`. Individual services will have pages at `/service-catalogue/service-name`, where the `service-name` is replaced with a trimmed version of the service name.
