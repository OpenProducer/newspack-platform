# newspack-supporters

Manage and display your site's supporters.

### Usage

- Add and manage Supporters using the Supporters section in WP Admin. 
- You can add a name, image, URL, and Supporter Type for a Supporter.
- You can render a grid of your Supporters using the `[supporters]` shortcode.
- You can render a grid of a specific type of Supporters using the `[supporters type='the-type-slug']` shortcode. For example, if you have a 'Grants' Supporter Type, you would use `[supporters type='grants']`.
- You can customize the number of columns using the `columns` attribute in the shortcode. For example, `[supporters columns="6"]` will render a grid 6 columns wide.
- You can hide the names with links on logos using the `show_links="false"` attribute in the shortcode. For example, `[supporters show_links="false"]` will render a logo grid without the supporter names.
- You can mix and match any of the above shortcode attributes to further customize the output.