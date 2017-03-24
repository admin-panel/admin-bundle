# Basic usage

After successful installation you can start from register Elements which will be handled by admin bundle.
Element is just object which allow admin panel to handling your website resources.

At this moment we have handle list and batch elements.

## To generate list of our resources we need do following steps:

### 1. Create element

We will create element which allow to list and batch delete users.
In element you can define data grid fields (fields visible on list) and you can define data source
so place when you decide from where element will fetch data. We have two built-in data sources right now:

### Doctrine entity
  We supporting typically doctirne entities with getters and setters
  Example of element for typically **doctrine entity** with setters is [here](doctrine.md)

### Dbal
  We supporting not only entites, but results of custom dbal queries as well.
  To use dbal queries we need to create element which use connection and dbal driver from admin panel. 
  Example of element where we fetch data directly from database using **dbal** is [here](dbal.md)

Element id is used in routing so after creating and registering element we are be able to enter `http://example.com/admin/list/admin_users` to see our `admin_users` element list.

### 2. Adding element to menu

You can add element to menu by adding such configuration into your `app/config/config.yml` file:

```yaml
# app/config/config.yml

admin_panel:
    menu:
        - {"id": "admin_users", "name": "Users"}
```

we need to give "id" of the element and "name" which will be shown on the menu as label.

Bundle allows to add menu items which are not elements with custom routes and allows to create submenu as well.
Example:

```yaml
# app/config/config.yml

admin_panel:
    menu:
        - {"id": "admin_users", "name": "Users"}
        - {"route": "my_route", "name": "Custom"},
        - 
            "name": "Sub menu",
            "children":
              - {"route": "my_other_route", "name": "Other route"}
```
