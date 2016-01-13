# phine-core
This is the core bundle of the Phine CMS. It comes with base logic for all modules and some backend modules, but no frontend modules. You additionaly need the phine-buitlin bundle for the most basic frontend modules.

The phine CMS works with MySql database and Apache webservice. 

In detail, the phine core includes the following things.

- An installer to setup bundles including their sql scripts, and generate ORM classes
- The Phine backend itself
- Backend modules for setting
- Backend modules for managing websites and their pages
- Backend modules for layouts with areas (where to put frontend modules)
- Backend module for containers to gather re-usable elements
- Backend mmodule to manage users and frontend members and groups
- Base classes and traits for backend and frontend modules; containing methods to assign groups and add adjustable texts (wording)
