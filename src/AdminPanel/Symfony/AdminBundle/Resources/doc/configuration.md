# FSiAdminBundle configuration

Below is reference for configuration of all bundle parameters, with default values:

```yml
# app/config/config.yml

fsi_admin:
    default_locale: %locale%
    locales:
        - %locale%
    menu_config_path: %kernel.root_dir%/config/admin_menu.yml
    templates:
        base: @AdminPanel/base.html.twig
        index_page: @AdminPanel/Admin/index.html.twig
        list: @AdminPanel/List/list.html.twig
        form: @AdminPanel/Form/form.html.twig
        crud_list: @AdminPanel/CRUD/list.html.twig
        crud_form: @AdminPanel/CRUD/form.html.twig
        resource: @AdminPanel/Resource/resource.html.twig
        display: @AdminPanel/Display/display.html.twig
        datagrid_theme: @AdminPanel/CRUD/datagrid.html.twig
        datasource_theme: @AdminPanel/CRUD/datasource.html.twig
        form_theme: @AdminPanel/Form/form_div_layout.html.twig
```

There is a separate [theme](../view/CRUD/datagrid_fsi_doctrine_extensions.html.twig) for datagrids you can use if
you register the [fsi/doctrine-extensions-bundle](https://github.com/fsi-open/doctrine-extensions-bundle).

```yml
# app/config/config.yml

fsi_admin:
    templates:
        datagrid_theme: @AdminPanel/CRUD/datagrid_fsi_doctrine_extensions.html.twig
```
