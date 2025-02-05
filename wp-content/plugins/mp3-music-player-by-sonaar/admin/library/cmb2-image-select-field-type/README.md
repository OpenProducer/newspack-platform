# CMB2 Image_Select Field Type
CMB2 Image_select field type. Ready to integrated with a theme

## Use
`image_select` as a field type.
```php
array(	
    'name' => __('Image Select', 'cmb2'),
    'desc' => __('page layout using image_select', 'cmb2'),
    'id'      => $prefix . 'page_custom_layout',
    'type' => 'image_select',
    'options' => array(
        'disabled' => array('title' => 'Full Width', 'alt' => 'Full Width', 'img' => $image_path . 'img/sidebar-disabled.gif'),
        'sidebar-left' => array('title' => 'Sidebar Left', 'alt' => 'Sidebar Left', 'img' => $image_path . 'img/sidebar-left.gif'),
        'sidebar-right' => array('title' => 'Sidebar Right', 'alt' => 'Sidebar Right', 'img' => $image_path . 'img/sidebar-right.gif'),
        'sidebar-leftright' => array('title' => 'Both Sidebars', 'alt' => 'Both Sidebars', 'img' => $image_path . 'img/sidebar-both.gif'),
    ),
    'default' => 'default',    
)
```
## Screenshots
<img src="https://github.com/improy/CMB2-Image_Select-Field-Type/blob/master/CMB2-Image_select-Field-Type.jpg" alt="CMB2 Image_select Field Type"/>

## Tutorial on how to integrate Image_select field in to a WordPress theme 
http://www.proy.info/how-to-create-cmb2-image-select-field-type/


