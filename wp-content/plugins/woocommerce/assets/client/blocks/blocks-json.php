<?php
// This file is generated. Do not modify it manually.
return array(
	'active-filters' => array(
		'name' => 'woocommerce/active-filters',
		'version' => '1.0.0',
		'title' => 'Active Filters Controls',
		'description' => 'Display the currently active filters.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'html' => false,
			'multiple' => false,
			'inserter' => false,
			'color' => array(
				'text' => true,
				'background' => false
			),
			'lock' => false
		),
		'attributes' => array(
			'displayStyle' => array(
				'type' => 'string',
				'default' => 'list'
			),
			'headingLevel' => array(
				'type' => 'number',
				'default' => 3
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'add-to-cart-form' => array(
		'name' => 'woocommerce/add-to-cart-form',
		'version' => '1.0.0',
		'title' => 'Add to Cart with Options',
		'description' => 'Display a button so the customer can add a product to their cart. Options will also be displayed depending on product type. e.g. quantity, variation.',
		'category' => 'woocommerce-product-elements',
		'attributes' => array(
			'isDescendentOfSingleProductBlock' => array(
				'type' => 'boolean',
				'default' => false
			),
			'quantitySelectorStyle' => array(
				'type' => 'string',
				'enum' => array(
					'input',
					'stepper'
				),
				'default' => 'input'
			)
		),
		'keywords' => array(
			'WooCommerce'
		),
		'usesContext' => array(
			'postId'
		),
		'textdomain' => 'woocommerce',
		'supports' => array(
			'interactivity' => true
		),
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'add-to-cart-with-options' => array(
		'name' => 'woocommerce/add-to-cart-with-options',
		'version' => '1.0.0',
		'title' => 'Add to Cart with Options (Experimental)',
		'description' => 'Create an "Add To Cart" composition by using blocks',
		'category' => 'woocommerce-product-elements',
		'attributes' => array(
			'isDescendentOfSingleProductBlock' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'usesContext' => array(
			'postId'
		),
		'textdomain' => 'woocommerce',
		'supports' => array(
			'interactivity' => true
		),
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'add-to-cart-with-options-quantity-selector' => array(
		'name' => 'woocommerce/add-to-cart-with-options-quantity-selector',
		'version' => '1.0.0',
		'title' => 'Quantity Selector (Experimental)',
		'description' => 'Display an input field to select the number of products to add to cart.',
		'category' => 'woocommerce-product-elements',
		'attributes' => array(
			'quantitySelectorStyle' => array(
				'type' => 'string',
				'enum' => array(
					'input',
					'stepper'
				),
				'default' => 'input'
			)
		),
		'keywords' => array(
			'WooCommerce'
		),
		'usesContext' => array(
			'postId'
		),
		'ancestor' => array(
			'woocommerce/add-to-cart-with-options'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'add-to-cart-with-options-variation-selector' => array(
		'name' => 'woocommerce/add-to-cart-with-options-variation-selector',
		'version' => '1.0.0',
		'title' => 'Variation Selector (Experimental)',
		'description' => 'Display a dropdown to select a variation to add to cart.',
		'category' => 'woocommerce-product-elements',
		'keywords' => array(
			'WooCommerce'
		),
		'usesContext' => array(
			'postId'
		),
		'ancestor' => array(
			'woocommerce/add-to-cart-with-options'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'all-products' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'textdomain' => 'woocommerce',
		'name' => 'woocommerce/all-products',
		'title' => 'All Products',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'description' => 'Display products from your store in a grid layout.',
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'multiple' => false,
			'inserter' => false
		),
		'attributes' => array(
			'columns' => array(
				'type' => 'number'
			),
			'rows' => array(
				'type' => 'number'
			),
			'alignButtons' => array(
				'type' => 'boolean'
			),
			'contentVisibility' => array(
				'type' => 'object'
			),
			'orderby' => array(
				'type' => 'string'
			),
			'layoutConfig' => array(
				'type' => 'array'
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			)
		)
	),
	'attribute-filter' => array(
		'name' => 'woocommerce/attribute-filter',
		'version' => '1.0.0',
		'title' => 'Filter by Attribute Controls',
		'description' => 'Enable customers to filter the product grid by selecting one or more attributes, such as color.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'html' => false,
			'color' => array(
				'text' => true,
				'background' => false
			),
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'attributeId' => array(
				'type' => 'number',
				'default' => 0
			),
			'showCounts' => array(
				'type' => 'boolean',
				'default' => false
			),
			'queryType' => array(
				'type' => 'string',
				'default' => 'or'
			),
			'headingLevel' => array(
				'type' => 'number',
				'default' => 3
			),
			'displayStyle' => array(
				'type' => 'string',
				'default' => 'list'
			),
			'showFilterButton' => array(
				'type' => 'boolean',
				'default' => false
			),
			'selectType' => array(
				'type' => 'string',
				'default' => 'multiple'
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'breadcrumbs' => array(
		'name' => 'woocommerce/breadcrumbs',
		'version' => '1.0.0',
		'title' => 'Store Breadcrumbs',
		'description' => 'Enable customers to keep track of their location within the store and navigate back to parent pages.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'attributes' => array(
			'contentJustification' => array(
				'type' => 'string'
			),
			'fontSize' => array(
				'type' => 'string',
				'default' => 'small'
			),
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			)
		),
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'color' => array(
				'background' => false,
				'link' => true
			),
			'html' => false,
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true
			)
		),
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'cart-link' => array(
		'name' => 'woocommerce/cart-link',
		'version' => '1.0.0',
		'title' => 'Cart Link',
		'icon' => 'cart',
		'description' => 'Display a link to the cart.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'supports' => array(
			'html' => false,
			'multiple' => false,
			'typography' => array(
				'fontSize' => true
			),
			'color' => array(
				'text' => false,
				'link' => true
			),
			'spacing' => array(
				'padding' => true
			)
		),
		'example' => array(
			'attributes' => array(
				'isPreview' => true,
				'cartIcon' => 'cart',
				'content' => 'Cart'
			)
		),
		'attributes' => array(
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			),
			'cartIcon' => array(
				'type' => 'string',
				'default' => 'cart'
			),
			'content' => array(
				'type' => 'string',
				'default' => null
			)
		),
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'catalog-sorting' => array(
		'name' => 'woocommerce/catalog-sorting',
		'version' => '1.0.0',
		'title' => 'Catalog Sorting',
		'description' => 'Enable customers to change the sorting order of the products.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'color' => array(
				'text' => true,
				'background' => false
			),
			'typography' => array(
				'fontSize' => true
			)
		),
		'attributes' => array(
			'fontSize' => array(
				'type' => 'string',
				'default' => 'small'
			),
			'useLabel' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'checkout' => array(
		'name' => 'woocommerce/checkout',
		'version' => '1.0.0',
		'title' => 'Checkout',
		'description' => 'Display a checkout form so your customers can submit orders.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'align' => array(
				'wide'
			),
			'html' => false,
			'multiple' => false
		),
		'example' => array(
			'attributes' => array(
				'isPreview' => true
			),
			'viewportWidth' => 800
		),
		'attributes' => array(
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false,
				'save' => false
			),
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			),
			'showFormStepNumbers' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'classic-shortcode' => array(
		'name' => 'woocommerce/classic-shortcode',
		'version' => '1.0.0',
		'title' => 'Classic Shortcode',
		'description' => 'Renders classic WooCommerce shortcodes.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'align' => true,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => true
		),
		'attributes' => array(
			'shortcode' => array(
				'type' => 'string',
				'default' => 'cart',
				'enum' => array(
					'cart',
					'checkout'
				)
			),
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'coming-soon' => array(
		'name' => 'woocommerce/coming-soon',
		'category' => 'woocommerce',
		'title' => 'Coming Soon',
		'attributes' => array(
			'color' => array(
				'type' => 'string'
			),
			'storeOnly' => array(
				'type' => 'boolean',
				'default' => false
			),
			'comingSoonPatternId' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'supports' => array(
			'color' => array(
				'background' => true,
				'text' => true
			),
			'inserter' => false
		)
	),
	'customer-account' => array(
		'name' => 'woocommerce/customer-account',
		'version' => '1.0.0',
		'title' => 'Customer account',
		'description' => 'A block that allows your customers to log in and out of their accounts in your store.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce',
			'My Account'
		),
		'supports' => array(
			'align' => true,
			'color' => array(
				'text' => true
			),
			'typography' => array(
				'fontSize' => true,
				'__experimentalFontFamily' => true
			),
			'spacing' => array(
				'margin' => true,
				'padding' => true
			)
		),
		'attributes' => array(
			'displayStyle' => array(
				'type' => 'string',
				'default' => 'icon_and_text'
			),
			'iconStyle' => array(
				'type' => 'string',
				'default' => 'default'
			),
			'iconClass' => array(
				'type' => 'string',
				'default' => 'icon'
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'featured-category' => array(
		'name' => 'woocommerce/featured-category',
		'version' => '1.0.0',
		'title' => 'Featured Category',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'description' => 'Visually highlight a product category and encourage prompt action.',
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'color' => array(
				'background' => true,
				'text' => true
			),
			'spacing' => array(
				'padding' => true,
				'__experimentalDefaultControls' => array(
					'padding' => true
				),
				'__experimentalSkipSerialization' => true
			),
			'__experimentalBorder' => array(
				'color' => true,
				'radius' => true,
				'width' => true,
				'__experimentalSkipSerialization' => true
			)
		),
		'attributes' => array(
			'alt' => array(
				'type' => 'string',
				'default' => ''
			),
			'contentAlign' => array(
				'type' => 'string',
				'default' => 'center'
			),
			'dimRatio' => array(
				'type' => 'number',
				'default' => 50
			),
			'editMode' => array(
				'type' => 'boolean',
				'default' => true
			),
			'focalPoint' => array(
				'type' => 'object',
				'default' => array(
					'x' => 0.5,
					'y' => 0.5
				)
			),
			'imageFit' => array(
				'type' => 'string',
				'default' => 'none'
			),
			'hasParallax' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isRepeated' => array(
				'type' => 'boolean',
				'default' => false
			),
			'mediaId' => array(
				'type' => 'number',
				'default' => 0
			),
			'mediaSrc' => array(
				'type' => 'string',
				'default' => ''
			),
			'minHeight' => array(
				'type' => 'number',
				'default' => 500
			),
			'linkText' => array(
				'default' => 'Shop now',
				'type' => 'string'
			),
			'categoryId' => array(
				'type' => 'number'
			),
			'overlayColor' => array(
				'type' => 'string',
				'default' => '#000000'
			),
			'overlayGradient' => array(
				'type' => 'string'
			),
			'previewCategory' => array(
				'type' => 'object',
				'default' => null
			),
			'showDesc' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'featured-product' => array(
		'name' => 'woocommerce/featured-product',
		'version' => '1.0.0',
		'title' => 'Featured Product',
		'description' => 'Highlight a product or variation.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'color' => array(
				'background' => true,
				'text' => true
			),
			'spacing' => array(
				'padding' => true,
				'__experimentalDefaultControls' => array(
					'padding' => true
				),
				'__experimentalSkipSerialization' => true
			),
			'__experimentalBorder' => array(
				'color' => true,
				'radius' => true,
				'width' => true,
				'__experimentalSkipSerialization' => true
			),
			'multiple' => true
		),
		'attributes' => array(
			'alt' => array(
				'type' => 'string',
				'default' => ''
			),
			'contentAlign' => array(
				'type' => 'string',
				'default' => 'center'
			),
			'dimRatio' => array(
				'type' => 'number',
				'default' => 50
			),
			'editMode' => array(
				'type' => 'boolean',
				'default' => true
			),
			'focalPoint' => array(
				'type' => 'object',
				'default' => array(
					'x' => 0.5,
					'y' => 0.5
				)
			),
			'imageFit' => array(
				'type' => 'string',
				'default' => 'none'
			),
			'hasParallax' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isRepeated' => array(
				'type' => 'boolean',
				'default' => false
			),
			'mediaId' => array(
				'type' => 'number',
				'default' => 0
			),
			'mediaSrc' => array(
				'type' => 'string',
				'default' => ''
			),
			'minHeight' => array(
				'type' => 'number',
				'default' => 500
			),
			'linkText' => array(
				'type' => 'string',
				'default' => 'Shop now'
			),
			'overlayColor' => array(
				'type' => 'string',
				'default' => '#000000'
			),
			'overlayGradient' => array(
				'type' => 'string'
			),
			'productId' => array(
				'type' => 'number'
			),
			'previewProduct' => array(
				'type' => 'object',
				'default' => null
			),
			'showDesc' => array(
				'type' => 'boolean',
				'default' => true
			),
			'showPrice' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'filter-wrapper' => array(
		'name' => 'woocommerce/filter-wrapper',
		'version' => '1.0.0',
		'title' => 'Filter Block',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'attributes' => array(
			'filterType' => array(
				'type' => 'string'
			),
			'heading' => array(
				'type' => 'string'
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'handpicked-products' => array(
		'name' => 'woocommerce/handpicked-products',
		'title' => 'Hand-picked Products',
		'category' => 'woocommerce',
		'keywords' => array(
			'Handpicked Products',
			'WooCommerce'
		),
		'description' => 'Display a selection of hand-picked products in a grid.',
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'inserter' => false
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string'
			),
			'columns' => array(
				'type' => 'number',
				'default' => 3
			),
			'contentVisibility' => array(
				'type' => 'object',
				'default' => array(
					'image' => true,
					'title' => true,
					'price' => true,
					'rating' => true,
					'button' => true
				),
				'properties' => array(
					'image' => array(
						'type' => 'boolean',
						'image' => true
					),
					'title' => array(
						'type' => 'boolean',
						'title' => true
					),
					'price' => array(
						'type' => 'boolean',
						'price' => true
					),
					'rating' => array(
						'type' => 'boolean',
						'rating' => true
					),
					'button' => array(
						'type' => 'boolean',
						'button' => true
					)
				)
			),
			'orderby' => array(
				'type' => 'string',
				'enum' => array(
					'date',
					'popularity',
					'price_asc',
					'price_desc',
					'rating',
					'title',
					'menu_order'
				),
				'default' => 'date'
			),
			'products' => array(
				'type' => 'array',
				'default' => array(
					
				)
			),
			'alignButtons' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'mini-cart' => array(
		'name' => 'woocommerce/mini-cart',
		'version' => '1.0.0',
		'title' => 'Mini-Cart',
		'icon' => 'miniCartAlt',
		'description' => 'Display a button for shoppers to quickly view their cart.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'supports' => array(
			'html' => false,
			'multiple' => false,
			'typography' => array(
				'fontSize' => true
			)
		),
		'example' => array(
			'attributes' => array(
				'isPreview' => true,
				'className' => 'wc-block-mini-cart--preview'
			)
		),
		'attributes' => array(
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			),
			'miniCartIcon' => array(
				'type' => 'string',
				'default' => 'cart'
			),
			'addToCartBehaviour' => array(
				'type' => 'string',
				'default' => 'none'
			),
			'onCartClickBehaviour' => array(
				'type' => 'string',
				'default' => 'open_drawer'
			),
			'hasHiddenPrice' => array(
				'type' => 'boolean',
				'default' => true
			),
			'cartAndCheckoutRenderStyle' => array(
				'type' => 'string',
				'default' => 'hidden'
			),
			'priceColor' => array(
				'type' => 'object'
			),
			'priceColorValue' => array(
				'type' => 'string'
			),
			'iconColor' => array(
				'type' => 'object'
			),
			'iconColorValue' => array(
				'type' => 'string'
			),
			'productCountColor' => array(
				'type' => 'object'
			),
			'productCountColorValue' => array(
				'type' => 'string'
			),
			'productCountVisibility' => array(
				'type' => 'string',
				'default' => 'greater_than_zero'
			)
		),
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-additional-fields' => array(
		'name' => 'woocommerce/order-confirmation-additional-fields',
		'version' => '1.0.0',
		'title' => 'Additional Field List',
		'description' => 'Display the list of additional field values from the current order.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'__experimentalBorder' => array(
				'color' => true,
				'radius' => true,
				'width' => true,
				'style' => true,
				'__experimentalDefaultControls' => array(
					'width' => true,
					'color' => true
				)
			),
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			),
			'className' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-additional-fields-wrapper' => array(
		'name' => 'woocommerce/order-confirmation-additional-fields-wrapper',
		'version' => '1.0.0',
		'title' => 'Additional Fields',
		'description' => 'Display additional checkout fields from the \'contact\' and \'order\' locations.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'attributes' => array(
			'heading' => array(
				'type' => 'string'
			)
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-additional-information' => array(
		'name' => 'woocommerce/order-confirmation-additional-information',
		'version' => '1.0.0',
		'title' => 'Additional Information',
		'description' => 'Displays additional information provided by third-party extensions for the current order.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'__experimentalBorder' => array(
				'color' => true,
				'radius' => true,
				'width' => true,
				'style' => true,
				'__experimentalDefaultControls' => array(
					'width' => true,
					'color' => true
				)
			),
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			),
			'className' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-billing-address' => array(
		'name' => 'woocommerce/order-confirmation-billing-address',
		'version' => '1.0.0',
		'title' => 'Billing Address',
		'description' => 'Display the order confirmation billing address.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'inserter' => false,
			'html' => false,
			'color' => array(
				'text' => true,
				'background' => true,
				'__experimentalDefaultControls' => array(
					'background' => true,
					'text' => true
				)
			),
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true,
				'__experimentalFontFamily' => true,
				'__experimentalFontWeight' => true,
				'__experimentalFontStyle' => true,
				'__experimentalTextTransform' => true,
				'__experimentalTextDecoration' => true,
				'__experimentalLetterSpacing' => true,
				'__experimentalDefaultControls' => array(
					'fontSize' => true
				)
			),
			'__experimentalBorder' => array(
				'color' => true,
				'radius' => true,
				'width' => true,
				'style' => true,
				'__experimentalDefaultControls' => array(
					'width' => true,
					'color' => true
				)
			),
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-billing-wrapper' => array(
		'name' => 'woocommerce/order-confirmation-billing-wrapper',
		'version' => '1.0.0',
		'title' => 'Billing Address Section',
		'description' => 'Display the order confirmation billing section.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'attributes' => array(
			'heading' => array(
				'type' => 'string'
			)
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-create-account' => array(
		'name' => 'woocommerce/order-confirmation-create-account',
		'version' => '1.0.0',
		'title' => 'Account Creation',
		'description' => 'Allow customers to create an account after their purchase.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'attributes' => array(
			'customerEmail' => array(
				'type' => 'string',
				'default' => ''
			),
			'nonceToken' => array(
				'type' => 'string',
				'default' => ''
			),
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			),
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'hasDarkControls' => array(
				'type' => 'boolean',
				'default' => false
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true
				)
			)
		),
		'supports' => array(
			'multiple' => false,
			'inserter' => false,
			'html' => false,
			'lock' => false,
			'align' => array(
				'wide',
				'full'
			),
			'color' => array(
				'background' => true,
				'text' => true,
				'button' => true
			),
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-downloads' => array(
		'name' => 'woocommerce/order-confirmation-downloads',
		'version' => '1.0.0',
		'title' => 'Order Downloads',
		'description' => 'Display links to purchased downloads.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true,
				'__experimentalFontFamily' => true,
				'__experimentalTextDecoration' => true,
				'__experimentalFontStyle' => true,
				'__experimentalFontWeight' => true,
				'__experimentalLetterSpacing' => true,
				'__experimentalTextTransform' => true,
				'__experimentalDefaultControls' => array(
					'fontSize' => true
				)
			),
			'color' => array(
				'background' => true,
				'text' => true,
				'link' => true,
				'gradients' => true,
				'__experimentalDefaultControls' => array(
					'background' => true,
					'text' => true
				)
			),
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			),
			'__experimentalBorder' => array(
				'color' => true,
				'style' => true,
				'width' => true,
				'__experimentalDefaultControls' => array(
					'color' => true,
					'style' => true,
					'width' => true
				)
			),
			'__experimentalSelector' => '.wp-block-woocommerce-order-confirmation-totals table'
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			),
			'className' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-downloads-wrapper' => array(
		'name' => 'woocommerce/order-confirmation-downloads-wrapper',
		'version' => '1.0.0',
		'title' => 'Downloads Section',
		'description' => 'Display the downloadable products section.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'attributes' => array(
			'heading' => array(
				'type' => 'string'
			)
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-shipping-address' => array(
		'name' => 'woocommerce/order-confirmation-shipping-address',
		'version' => '1.0.0',
		'title' => 'Shipping Address',
		'description' => 'Display the order confirmation shipping address.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'inserter' => false,
			'html' => false,
			'color' => array(
				'text' => true,
				'background' => true,
				'__experimentalDefaultControls' => array(
					'background' => true,
					'text' => true
				)
			),
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true,
				'__experimentalFontFamily' => true,
				'__experimentalFontWeight' => true,
				'__experimentalFontStyle' => true,
				'__experimentalTextTransform' => true,
				'__experimentalTextDecoration' => true,
				'__experimentalLetterSpacing' => true,
				'__experimentalDefaultControls' => array(
					'fontSize' => true
				)
			),
			'__experimentalBorder' => array(
				'color' => true,
				'radius' => true,
				'width' => true,
				'style' => true,
				'__experimentalDefaultControls' => array(
					'width' => true,
					'color' => true
				)
			),
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-shipping-wrapper' => array(
		'name' => 'woocommerce/order-confirmation-shipping-wrapper',
		'version' => '1.0.0',
		'title' => 'Shipping Address Section',
		'description' => 'Display the order confirmation shipping section.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'attributes' => array(
			'heading' => array(
				'type' => 'string',
				'default' => 'Shipping'
			)
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-status' => array(
		'name' => 'woocommerce/order-confirmation-status',
		'version' => '1.0.0',
		'title' => 'Order Status',
		'description' => 'Display a "thank you" message, or a sentence regarding the current order status.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true,
				'__experimentalFontFamily' => true,
				'__experimentalTextDecoration' => true,
				'__experimentalFontStyle' => true,
				'__experimentalFontWeight' => true,
				'__experimentalLetterSpacing' => true,
				'__experimentalTextTransform' => true,
				'__experimentalDefaultControls' => array(
					'fontSize' => true
				)
			),
			'color' => array(
				'background' => true,
				'text' => true,
				'gradients' => true,
				'__experimentalDefaultControls' => array(
					'background' => true,
					'text' => true
				)
			),
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			),
			'className' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-summary' => array(
		'name' => 'woocommerce/order-confirmation-summary',
		'version' => '1.0.0',
		'title' => 'Order Summary',
		'description' => 'Display the order summary on the order confirmation page.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true,
				'__experimentalFontFamily' => true,
				'__experimentalTextDecoration' => true,
				'__experimentalFontStyle' => true,
				'__experimentalFontWeight' => true,
				'__experimentalLetterSpacing' => true,
				'__experimentalTextTransform' => true,
				'__experimentalDefaultControls' => array(
					'fontSize' => true
				)
			),
			'color' => array(
				'background' => true,
				'text' => true,
				'gradients' => true,
				'__experimentalDefaultControls' => array(
					'background' => true,
					'text' => true
				)
			),
			'__experimentalBorder' => array(
				'color' => true,
				'radius' => true,
				'width' => true,
				'__experimentalDefaultControls' => array(
					'width' => true,
					'color' => true
				)
			),
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			),
			'className' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-totals' => array(
		'name' => 'woocommerce/order-confirmation-totals',
		'version' => '1.0.0',
		'title' => 'Order Totals',
		'description' => 'Display the items purchased and order totals.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true,
				'__experimentalFontFamily' => true,
				'__experimentalTextDecoration' => true,
				'__experimentalFontStyle' => true,
				'__experimentalFontWeight' => true,
				'__experimentalLetterSpacing' => true,
				'__experimentalTextTransform' => true,
				'__experimentalDefaultControls' => array(
					'fontSize' => true
				)
			),
			'color' => array(
				'background' => true,
				'text' => true,
				'link' => true,
				'gradients' => true,
				'__experimentalDefaultControls' => array(
					'background' => true,
					'text' => true
				)
			),
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			),
			'__experimentalBorder' => array(
				'color' => true,
				'style' => true,
				'width' => true,
				'__experimentalDefaultControls' => array(
					'color' => true,
					'style' => true,
					'width' => true
				)
			),
			'__experimentalSelector' => '.wp-block-woocommerce-order-confirmation-totals table'
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			),
			'className' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'order-confirmation-totals-wrapper' => array(
		'name' => 'woocommerce/order-confirmation-totals-wrapper',
		'version' => '1.0.0',
		'title' => 'Order Totals Section',
		'description' => 'Display the order details section.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'attributes' => array(
			'heading' => array(
				'type' => 'string'
			)
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false
				)
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'page-content-wrapper' => array(
		'name' => 'woocommerce/page-content-wrapper',
		'version' => '1.0.0',
		'title' => 'WooCommerce Page',
		'description' => 'Displays WooCommerce page content.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'supports' => array(
			'html' => false,
			'multiple' => false,
			'inserter' => false
		),
		'attributes' => array(
			'page' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'providesContext' => array(
			'postId' => 'postId',
			'postType' => 'postType'
		),
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'price-filter' => array(
		'name' => 'woocommerce/price-filter',
		'version' => '1.0.0',
		'title' => 'Filter by Price Controls',
		'description' => 'Enable customers to filter the product grid by choosing a price range.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'html' => false,
			'multiple' => false,
			'color' => array(
				'text' => true,
				'background' => false
			),
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'showInputFields' => array(
				'type' => 'boolean',
				'default' => true
			),
			'inlineInput' => array(
				'type' => 'boolean',
				'default' => false
			),
			'showFilterButton' => array(
				'type' => 'boolean',
				'default' => false
			),
			'headingLevel' => array(
				'type' => 'number',
				'default' => 3
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-average-rating' => array(
		'name' => 'woocommerce/product-average-rating',
		'title' => 'Product Average Rating (Beta)',
		'description' => 'Display the average rating of a product',
		'apiVersion' => 3,
		'category' => 'woocommerce-product-elements',
		'attributes' => array(
			'textAlign' => array(
				'type' => 'string'
			)
		),
		'keywords' => array(
			'WooCommerce'
		),
		'ancestor' => array(
			'woocommerce/single-product'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-best-sellers' => array(
		'name' => 'woocommerce/product-best-sellers',
		'title' => 'Best Selling Products',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'description' => 'Display a grid of your all-time best selling products.',
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'inserter' => false
		),
		'attributes' => array(
			'columns' => array(
				'type' => 'number',
				'default' => 3
			),
			'rows' => array(
				'type' => 'number',
				'default' => 3
			),
			'alignButtons' => array(
				'type' => 'boolean',
				'default' => false
			),
			'contentVisibility' => array(
				'type' => 'object',
				'default' => array(
					'image' => true,
					'title' => true,
					'price' => true,
					'rating' => true,
					'button' => true
				),
				'properties' => array(
					'image' => array(
						'type' => 'boolean',
						'default' => true
					),
					'title' => array(
						'type' => 'boolean',
						'default' => true
					),
					'price' => array(
						'type' => 'boolean',
						'default' => true
					),
					'rating' => array(
						'type' => 'boolean',
						'default' => true
					),
					'button' => array(
						'type' => 'boolean',
						'default' => true
					)
				)
			),
			'categories' => array(
				'type' => 'array',
				'default' => array(
					
				)
			),
			'catOperator' => array(
				'type' => 'string',
				'default' => 'any'
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			),
			'stockStatus' => array(
				'type' => 'array'
			),
			'editMode' => array(
				'type' => 'boolean',
				'default' => true
			),
			'orderby' => array(
				'type' => 'string',
				'enum' => array(
					'date',
					'popularity',
					'price_asc',
					'price_desc',
					'rating',
					'title',
					'menu_order'
				),
				'default' => 'popularity'
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-button' => array(
		'name' => 'woocommerce/product-button',
		'version' => '1.0.0',
		'title' => 'Add to Cart Button',
		'description' => 'Display a call to action button which either adds the product to the cart, or links to the product page.',
		'category' => 'woocommerce-product-elements',
		'keywords' => array(
			'WooCommerce'
		),
		'usesContext' => array(
			'query',
			'queryId',
			'postId'
		),
		'textdomain' => 'woocommerce',
		'attributes' => array(
			'productId' => array(
				'type' => 'number',
				'default' => 0
			),
			'textAlign' => array(
				'type' => 'string',
				'default' => ''
			),
			'width' => array(
				'type' => 'number'
			),
			'isDescendentOfSingleProductBlock' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isDescendentOfQueryLoop' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'color' => array(
				'background' => false,
				'link' => true
			),
			'interactivity' => true,
			'html' => false,
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true
			)
		),
		'ancestor' => array(
			'woocommerce/all-products',
			'woocommerce/single-product',
			'core/post-template',
			'woocommerce/product-template'
		),
		'styles' => array(
			array(
				'name' => 'fill',
				'label' => 'Fill',
				'isDefault' => true
			),
			array(
				'name' => 'outline',
				'label' => 'Outline'
			)
		),
		'viewScript' => array(
			'wc-product-button-interactivity-frontend'
		),
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-categories' => array(
		'name' => 'woocommerce/product-categories',
		'title' => 'Product Categories List',
		'category' => 'woocommerce',
		'description' => 'Show all product categories as a list or dropdown.',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'color' => array(
				'background' => false,
				'link' => true
			),
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true
			)
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string'
			),
			'hasCount' => array(
				'type' => 'boolean',
				'default' => true
			),
			'hasImage' => array(
				'type' => 'boolean',
				'default' => false
			),
			'hasEmpty' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isDropdown' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isHierarchical' => array(
				'type' => 'boolean',
				'default' => true
			),
			'showChildrenOnly' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'example' => array(
			'attributes' => array(
				'hasCount' => true,
				'hasImage' => false
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-category' => array(
		'name' => 'woocommerce/product-category',
		'title' => 'Products by Category',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'description' => 'Display a grid of products from your selected categories.',
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'inserter' => false
		),
		'attributes' => array(
			'columns' => array(
				'type' => 'number',
				'default' => 3
			),
			'rows' => array(
				'type' => 'number',
				'default' => 3
			),
			'alignButtons' => array(
				'type' => 'boolean',
				'default' => false
			),
			'contentVisibility' => array(
				'type' => 'object',
				'default' => array(
					'image' => true,
					'title' => true,
					'price' => true,
					'rating' => true,
					'button' => true
				),
				'properties' => array(
					'image' => array(
						'type' => 'boolean',
						'default' => true
					),
					'title' => array(
						'type' => 'boolean',
						'default' => true
					),
					'price' => array(
						'type' => 'boolean',
						'default' => true
					),
					'rating' => array(
						'type' => 'boolean',
						'default' => true
					),
					'button' => array(
						'type' => 'boolean',
						'default' => true
					)
				)
			),
			'categories' => array(
				'type' => 'array',
				'default' => array(
					
				)
			),
			'catOperator' => array(
				'type' => 'string',
				'default' => 'any'
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			),
			'stockStatus' => array(
				'type' => 'array'
			),
			'editMode' => array(
				'type' => 'boolean',
				'default' => true
			),
			'orderby' => array(
				'type' => 'string',
				'enum' => array(
					'date',
					'popularity',
					'price_asc',
					'price_desc',
					'rating',
					'title',
					'menu_order'
				),
				'default' => 'date'
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-collection' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'woocommerce/product-collection',
		'version' => '1.0.0',
		'title' => 'Product Collection',
		'description' => 'Display a collection of products from your store.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce',
			'Products (Beta)',
			'all products',
			'by category',
			'by tag',
			'by attribute'
		),
		'textdomain' => 'woocommerce',
		'attributes' => array(
			'queryId' => array(
				'type' => 'number'
			),
			'query' => array(
				'type' => 'object'
			),
			'tagName' => array(
				'type' => 'string'
			),
			'displayLayout' => array(
				'type' => 'object'
			),
			'dimensions' => array(
				'type' => 'object'
			),
			'convertedFromProducts' => array(
				'type' => 'boolean',
				'default' => false
			),
			'collection' => array(
				'type' => 'string'
			),
			'hideControls' => array(
				'default' => array(
					
				),
				'type' => 'array'
			),
			'queryContextIncludes' => array(
				'type' => 'array'
			),
			'forcePageReload' => array(
				'type' => 'boolean',
				'default' => false
			),
			'__privatePreviewState' => array(
				'type' => 'object'
			)
		),
		'providesContext' => array(
			'queryId' => 'queryId',
			'query' => 'query',
			'displayLayout' => 'displayLayout',
			'dimensions' => 'dimensions',
			'queryContextIncludes' => 'queryContextIncludes',
			'collection' => 'collection',
			'__privateProductCollectionPreviewState' => '__privatePreviewState'
		),
		'usesContext' => array(
			'templateSlug',
			'postId'
		),
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'anchor' => true,
			'html' => false,
			'__experimentalLayout' => true,
			'interactivity' => true
		)
	),
	'product-collection-no-results' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'woocommerce/product-collection-no-results',
		'title' => 'No results',
		'version' => '1.0.0',
		'category' => 'woocommerce',
		'description' => 'The contents of this block will display when there are no products found.',
		'textdomain' => 'woocommerce',
		'keywords' => array(
			'Product Collection'
		),
		'usesContext' => array(
			'queryId',
			'query'
		),
		'ancestor' => array(
			'woocommerce/product-collection'
		),
		'supports' => array(
			'align' => true,
			'reusable' => false,
			'html' => false,
			'color' => array(
				'gradients' => true,
				'link' => true
			),
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true,
				'__experimentalFontFamily' => true,
				'__experimentalFontWeight' => true,
				'__experimentalFontStyle' => true,
				'__experimentalTextTransform' => true,
				'__experimentalTextDecoration' => true,
				'__experimentalLetterSpacing' => true,
				'__experimentalDefaultControls' => array(
					'fontSize' => true
				)
			)
		)
	),
	'product-details' => array(
		'name' => 'woocommerce/product-details',
		'version' => '1.0.0',
		'icon' => 'info',
		'title' => 'Product Details',
		'description' => 'Display a product\'s description, attributes, and reviews.',
		'category' => 'woocommerce-product-elements',
		'attributes' => array(
			'hideTabTitle' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'align' => true,
			'spacing' => array(
				'margin' => true
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-filter-active' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'name' => 'woocommerce/product-filter-active',
		'version' => '1.0.0',
		'title' => 'Active (Experimental)',
		'description' => 'Display the currently active filters.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'ancestor' => array(
			'woocommerce/product-filters'
		),
		'supports' => array(
			'interactivity' => true,
			'__experimentalBorder' => array(
				'color' => true,
				'radius' => true,
				'style' => true,
				'width' => true,
				'__experimentalDefaultControls' => array(
					'color' => false,
					'radius' => false,
					'style' => false,
					'width' => false
				)
			),
			'spacing' => array(
				'margin' => true,
				'padding' => true,
				'blockGap' => false,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false,
					'blockGap' => false
				)
			)
		),
		'usesContext' => array(
			'activeFilters'
		),
		'attributes' => array(
			'clearButton' => array(
				'type' => 'boolean',
				'default' => false
			)
		)
	),
	'product-filter-attribute' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'name' => 'woocommerce/product-filter-attribute',
		'version' => '1.0.0',
		'title' => 'Attribute (Experimental)',
		'description' => 'Enable customers to filter the product grid by selecting one or more attributes, such as color.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'ancestor' => array(
			'woocommerce/product-filters'
		),
		'supports' => array(
			'interactivity' => true,
			'color' => array(
				'text' => true,
				'background' => false,
				'__experimentalDefaultControls' => array(
					'text' => false
				)
			),
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true,
				'__experimentalFontWeight' => true,
				'__experimentalFontFamily' => true,
				'__experimentalFontStyle' => true,
				'__experimentalTextTransform' => true,
				'__experimentalTextDecoration' => true,
				'__experimentalLetterSpacing' => true,
				'__experimentalDefaultControls' => array(
					'fontSize' => false
				)
			),
			'spacing' => array(
				'margin' => true,
				'padding' => true,
				'blockGap' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false,
					'blockGap' => false
				)
			),
			'__experimentalBorder' => array(
				'color' => true,
				'radius' => true,
				'style' => true,
				'width' => true,
				'__experimentalDefaultControls' => array(
					'color' => false,
					'radius' => false,
					'style' => false,
					'width' => false
				)
			)
		),
		'usesContext' => array(
			'query',
			'filterParams'
		),
		'attributes' => array(
			'attributeId' => array(
				'type' => 'number',
				'default' => 0
			),
			'showCounts' => array(
				'type' => 'boolean',
				'default' => false
			),
			'queryType' => array(
				'type' => 'string',
				'default' => 'or'
			),
			'displayStyle' => array(
				'type' => 'string',
				'default' => 'woocommerce/product-filter-checkbox-list'
			),
			'selectType' => array(
				'type' => 'string',
				'default' => 'multiple'
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			),
			'sortOrder' => array(
				'type' => 'string',
				'default' => 'count-desc'
			),
			'hideEmpty' => array(
				'type' => 'boolean',
				'default' => true
			),
			'clearButton' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'example' => array(
			'attributes' => array(
				'isPreview' => true
			)
		)
	),
	'product-filter-checkbox-list' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'name' => 'woocommerce/product-filter-checkbox-list',
		'version' => '1.0.0',
		'title' => 'List',
		'description' => 'Display a list of filter options.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'ancestor' => array(
			'woocommerce/product-filter-attribute',
			'woocommerce/product-filter-status'
		),
		'supports' => array(
			'color' => array(
				'enableContrastChecker' => false
			)
		),
		'usesContext' => array(
			'filterData'
		),
		'attributes' => array(
			'optionElementBorder' => array(
				'type' => 'string',
				'default' => ''
			),
			'customOptionElementBorder' => array(
				'type' => 'string',
				'default' => ''
			),
			'optionElementSelected' => array(
				'type' => 'string',
				'default' => ''
			),
			'customOptionElementSelected' => array(
				'type' => 'string',
				'default' => ''
			),
			'optionElement' => array(
				'type' => 'string',
				'default' => ''
			),
			'customOptionElement' => array(
				'type' => 'string',
				'default' => ''
			)
		)
	),
	'product-filter-chips' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'name' => 'woocommerce/product-filter-chips',
		'version' => '1.0.0',
		'title' => 'Chips',
		'description' => 'Display filter options as chips.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'ancestor' => array(
			'woocommerce/product-filter-attribute',
			'woocommerce/product-filter-status'
		),
		'supports' => array(
			
		),
		'usesContext' => array(
			'filterData'
		),
		'attributes' => array(
			'chipText' => array(
				'type' => 'string'
			),
			'customChipText' => array(
				'type' => 'string'
			),
			'chipBackground' => array(
				'type' => 'string'
			),
			'customChipBackground' => array(
				'type' => 'string'
			),
			'chipBorder' => array(
				'type' => 'string'
			),
			'customChipBorder' => array(
				'type' => 'string'
			),
			'selectedChipText' => array(
				'type' => 'string'
			),
			'customSelectedChipText' => array(
				'type' => 'string'
			),
			'selectedChipBackground' => array(
				'type' => 'string'
			),
			'customSelectedChipBackground' => array(
				'type' => 'string'
			),
			'selectedChipBorder' => array(
				'type' => 'string'
			),
			'customSelectedChipBorder' => array(
				'type' => 'string'
			)
		)
	),
	'product-filter-clear-button' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'name' => 'woocommerce/product-filter-clear-button',
		'version' => '0.1.0',
		'title' => 'Clear (Experimental)',
		'description' => 'Allows shoppers to reset this filter.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce',
			'reset filter'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'ancestor' => array(
			'woocommerce/product-filter',
			'woocommerce/product-filter-attribute',
			'woocommerce/product-filter-price',
			'woocommerce/product-filter-rating',
			'woocommerce/product-filter-status',
			'woocommerce/product-filter-active'
		),
		'usesContext' => array(
			'filterData'
		),
		'attributes' => array(
			'clearType' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'supports' => array(
			'interactivity' => true,
			'inserter' => false
		)
	),
	'product-filter-price' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'name' => 'woocommerce/product-filter-price',
		'version' => '1.0.0',
		'title' => 'Price (Experimental)',
		'description' => 'Let shoppers filter products by choosing a price range.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'ancestor' => array(
			'woocommerce/product-filters'
		),
		'supports' => array(
			'interactivity' => true,
			'html' => false
		),
		'usesContext' => array(
			'query',
			'filterParams'
		),
		'attributes' => array(
			'clearButton' => array(
				'type' => 'boolean',
				'default' => true
			)
		)
	),
	'product-filter-price-slider' => array(
		'name' => 'woocommerce/product-filter-price-slider',
		'version' => '1.0.0',
		'title' => 'Price Slider',
		'description' => 'A slider helps shopper choose a price range.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'html' => false,
			'color' => array(
				'enableContrastChecker' => false,
				'background' => false,
				'text' => false
			)
		),
		'attributes' => array(
			'showInputFields' => array(
				'type' => 'boolean',
				'default' => true
			),
			'inlineInput' => array(
				'type' => 'boolean',
				'default' => false
			),
			'sliderHandle' => array(
				'type' => 'string',
				'default' => ''
			),
			'customSliderHandle' => array(
				'type' => 'string',
				'default' => ''
			),
			'sliderHandleBorder' => array(
				'type' => 'string',
				'default' => ''
			),
			'customSliderHandleBorder' => array(
				'type' => 'string',
				'default' => ''
			),
			'slider' => array(
				'type' => 'string',
				'default' => ''
			),
			'customSlider' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'ancestor' => array(
			'woocommerce/product-filter-price'
		),
		'usesContext' => array(
			'filterData'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-filter-rating' => array(
		'name' => 'woocommerce/product-filter-rating',
		'version' => '1.0.0',
		'title' => 'Rating (Experimental)',
		'description' => 'Enable customers to filter the product collection by rating.',
		'category' => 'woocommerce',
		'keywords' => array(
			
		),
		'supports' => array(
			'interactivity' => true,
			'color' => array(
				'background' => false,
				'text' => true
			)
		),
		'ancestor' => array(
			'woocommerce/product-filters'
		),
		'usesContext' => array(
			'query',
			'filterParams'
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'showCounts' => array(
				'type' => 'boolean',
				'default' => false
			),
			'minRating' => array(
				'type' => 'string',
				'default' => '0'
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			),
			'clearButton' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-filter-removable-chips' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'name' => 'woocommerce/product-filter-removable-chips',
		'version' => '1.0.0',
		'title' => 'Chips',
		'description' => 'Display removable active filters as chips.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'ancestor' => array(
			'woocommerce/product-filter-active'
		),
		'supports' => array(
			'layout' => array(
				'allowSwitching' => false,
				'allowInheriting' => false,
				'allowJustification' => false,
				'allowVerticalAlignment' => false,
				'default' => array(
					'type' => 'flex'
				)
			)
		),
		'usesContext' => array(
			'queryId',
			'filterData'
		),
		'attributes' => array(
			'chipText' => array(
				'type' => 'string'
			),
			'customChipText' => array(
				'type' => 'string'
			),
			'chipBackground' => array(
				'type' => 'string'
			),
			'customChipBackground' => array(
				'type' => 'string'
			),
			'chipBorder' => array(
				'type' => 'string'
			),
			'customChipBorder' => array(
				'type' => 'string'
			)
		)
	),
	'product-filter-status' => array(
		'name' => 'woocommerce/product-filter-status',
		'version' => '1.0.0',
		'title' => 'Status (Experimental)',
		'description' => 'Let shoppers filter products by choosing stock status.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'ancestor' => array(
			'woocommerce/product-filters'
		),
		'supports' => array(
			'interactivity' => true,
			'html' => false,
			'color' => array(
				'text' => true,
				'background' => false,
				'__experimentalDefaultControls' => array(
					'text' => false
				)
			),
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true,
				'__experimentalFontWeight' => true,
				'__experimentalFontFamily' => true,
				'__experimentalFontStyle' => true,
				'__experimentalTextTransform' => true,
				'__experimentalTextDecoration' => true,
				'__experimentalLetterSpacing' => true,
				'__experimentalDefaultControls' => array(
					'fontSize' => false
				)
			),
			'spacing' => array(
				'margin' => true,
				'padding' => true,
				'blockGap' => true,
				'__experimentalDefaultControls' => array(
					'margin' => false,
					'padding' => false,
					'blockGap' => false
				)
			),
			'__experimentalBorder' => array(
				'color' => true,
				'radius' => true,
				'style' => true,
				'width' => true,
				'__experimentalDefaultControls' => array(
					'color' => false,
					'radius' => false,
					'style' => false,
					'width' => false
				)
			)
		),
		'attributes' => array(
			'showCounts' => array(
				'type' => 'boolean',
				'default' => false
			),
			'displayStyle' => array(
				'type' => 'string',
				'default' => 'woocommerce/product-filter-checkbox-list'
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			),
			'hideEmpty' => array(
				'type' => 'boolean',
				'default' => true
			),
			'clearButton' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'usesContext' => array(
			'query',
			'filterParams'
		),
		'example' => array(
			'attributes' => array(
				'isPreview' => true
			)
		)
	),
	'product-filters' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'woocommerce/product-filters',
		'version' => '1.0.0',
		'title' => 'Product Filters (Experimental)',
		'description' => 'Let shoppers filter products displayed on the page.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'align' => true,
			'color' => array(
				'background' => true,
				'text' => true,
				'heading' => true,
				'enableContrastChecker' => false,
				'button' => true
			),
			'multiple' => true,
			'inserter' => true,
			'interactivity' => true,
			'typography' => array(
				'fontSize' => true
			),
			'layout' => array(
				'default' => array(
					'type' => 'flex',
					'orientation' => 'vertical',
					'flexWrap' => 'nowrap',
					'justifyContent' => 'stretch'
				),
				'allowEditing' => false
			),
			'spacing' => array(
				'blockGap' => true
			)
		),
		'textdomain' => 'woocommerce',
		'usesContext' => array(
			'postId',
			'query',
			'queryId'
		),
		'viewScript' => 'wc-product-filters-frontend',
		'example' => array(
			
		),
		'attributes' => array(
			'overlayIcon' => array(
				'type' => 'string',
				'default' => 'filter-icon-2'
			),
			'overlayIconSize' => array(
				'type' => 'number'
			),
			'overlayButtonType' => array(
				'type' => 'string',
				'default' => 'label-icon'
			)
		)
	),
	'product-gallery' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'woocommerce/product-gallery',
		'version' => '1.0.0',
		'title' => 'Product Gallery (Beta)',
		'description' => 'Showcase your products relevant images and media.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'align' => true,
			'interactivity' => true
		),
		'textdomain' => 'woocommerce',
		'usesContext' => array(
			'postId'
		),
		'providesContext' => array(
			'thumbnailsPosition' => 'thumbnailsPosition',
			'thumbnailsNumberOfThumbnails' => 'thumbnailsNumberOfThumbnails',
			'productGalleryClientId' => 'productGalleryClientId',
			'nextPreviousButtonsPosition' => 'nextPreviousButtonsPosition',
			'pagerDisplayMode' => 'pagerDisplayMode',
			'hoverZoom' => 'hoverZoom',
			'fullScreenOnClick' => 'fullScreenOnClick',
			'mode' => 'mode',
			'cropImages' => 'cropImages'
		),
		'ancestor' => array(
			'woocommerce/single-product'
		),
		'attributes' => array(
			'thumbnailsPosition' => array(
				'type' => 'string',
				'default' => 'left'
			),
			'thumbnailsNumberOfThumbnails' => array(
				'type' => 'number',
				'default' => 3
			),
			'pagerDisplayMode' => array(
				'type' => 'string',
				'default' => 'dots'
			),
			'productGalleryClientId' => array(
				'type' => 'string',
				'default' => ''
			),
			'cropImages' => array(
				'type' => 'boolean',
				'default' => false
			),
			'hoverZoom' => array(
				'type' => 'boolean',
				'default' => true
			),
			'fullScreenOnClick' => array(
				'type' => 'boolean',
				'default' => true
			),
			'nextPreviousButtonsPosition' => array(
				'type' => 'string',
				'default' => 'insideTheImage'
			),
			'mode' => array(
				'type' => 'string',
				'default' => 'standard'
			)
		),
		'viewScript' => 'wc-product-gallery-frontend',
		'example' => array(
			
		)
	),
	'product-gallery-large-image' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'woocommerce/product-gallery-large-image',
		'version' => '1.0.0',
		'title' => 'Large Image',
		'description' => 'Display the Large Image of a product.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'usesContext' => array(
			'nextPreviousButtonsPosition',
			'postId',
			'hoverZoom',
			'fullScreenOnClick',
			'cropImages'
		),
		'supports' => array(
			'interactivity' => true
		),
		'textdomain' => 'woocommerce',
		'ancestor' => array(
			'woocommerce/product-gallery'
		)
	),
	'product-gallery-large-image-next-previous' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'woocommerce/product-gallery-large-image-next-previous',
		'version' => '1.0.0',
		'title' => 'Next/Previous Buttons',
		'description' => 'Display next and previous buttons.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'usesContext' => array(
			'nextPreviousButtonsPosition',
			'productGalleryClientId',
			'postId'
		),
		'textdomain' => 'woocommerce',
		'supports' => array(
			'layout' => array(
				'default' => array(
					'type' => 'flex',
					'verticalAlignment' => 'bottom'
				),
				'allowVerticalAlignment' => true,
				'allowJustification' => false,
				'allowOrientation' => false,
				'allowInheriting' => false
			)
		),
		'ancestor' => array(
			'woocommerce/product-gallery-large-image'
		)
	),
	'product-gallery-pager' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'woocommerce/product-gallery-pager',
		'version' => '1.0.0',
		'title' => 'Pager',
		'description' => 'Display the gallery pager.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'ancestor' => array(
			'woocommerce/product-gallery'
		),
		'usesContext' => array(
			'pagerDisplayMode',
			'productGalleryClientId',
			'thumbnailsNumberOfThumbnails',
			'postId'
		)
	),
	'product-gallery-thumbnails' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'woocommerce/product-gallery-thumbnails',
		'version' => '1.0.0',
		'title' => 'Thumbnails',
		'description' => 'Display the Thumbnails of a product.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'usesContext' => array(
			'postId',
			'thumbnailsPosition',
			'thumbnailsNumberOfThumbnails',
			'productGalleryClientId',
			'mode',
			'cropImages'
		),
		'textdomain' => 'woocommerce',
		'ancestor' => array(
			'woocommerce/product-gallery'
		),
		'supports' => array(
			'spacing' => array(
				'margin' => true,
				'__experimentalDefaultControls' => array(
					'margin' => true
				)
			)
		)
	),
	'product-image' => array(
		'name' => 'woocommerce/product-image',
		'version' => '1.0.0',
		'title' => 'Product Image',
		'description' => 'Display the main product image.',
		'category' => 'woocommerce-product-elements',
		'attributes' => array(
			'showProductLink' => array(
				'type' => 'boolean',
				'default' => true
			),
			'showSaleBadge' => array(
				'type' => 'boolean',
				'default' => true
			),
			'saleBadgeAlign' => array(
				'type' => 'string',
				'default' => 'right'
			),
			'imageSizing' => array(
				'type' => 'string',
				'default' => 'single'
			),
			'productId' => array(
				'type' => 'number',
				'default' => 0
			),
			'isDescendentOfQueryLoop' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isDescendentOfSingleProductBlock' => array(
				'type' => 'boolean',
				'default' => false
			),
			'width' => array(
				'type' => 'string'
			),
			'height' => array(
				'type' => 'string'
			),
			'scale' => array(
				'type' => 'string',
				'default' => 'cover'
			),
			'aspectRatio' => array(
				'type' => 'string'
			)
		),
		'usesContext' => array(
			'query',
			'queryId',
			'postId'
		),
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-image-gallery' => array(
		'name' => 'woocommerce/product-image-gallery',
		'version' => '1.0.0',
		'title' => 'Product Image Gallery',
		'icon' => 'gallery',
		'description' => 'Display a product\'s images.',
		'category' => 'woocommerce-product-elements',
		'supports' => array(
			'align' => true,
			'multiple' => false
		),
		'keywords' => array(
			'WooCommerce'
		),
		'usesContext' => array(
			'postId',
			'postType',
			'queryId'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-meta' => array(
		'name' => 'woocommerce/product-meta',
		'version' => '1.0.0',
		'title' => 'Product Meta',
		'icon' => 'product',
		'description' => 'Display a product’s SKU, categories, tags, and more.',
		'category' => 'woocommerce-product-elements',
		'supports' => array(
			'align' => true,
			'reusable' => false
		),
		'keywords' => array(
			'WooCommerce'
		),
		'usesContext' => array(
			'postId',
			'postType',
			'queryId'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-new' => array(
		'name' => 'woocommerce/product-new',
		'title' => 'Newest Products',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'description' => 'Display a grid of your newest products.',
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'inserter' => false
		),
		'attributes' => array(
			'columns' => array(
				'type' => 'number',
				'default' => 3
			),
			'rows' => array(
				'type' => 'number',
				'default' => 3
			),
			'alignButtons' => array(
				'type' => 'boolean',
				'default' => false
			),
			'contentVisibility' => array(
				'type' => 'object',
				'default' => array(
					'image' => true,
					'title' => true,
					'price' => true,
					'rating' => true,
					'button' => true
				),
				'properties' => array(
					'image' => array(
						'type' => 'boolean',
						'default' => true
					),
					'title' => array(
						'type' => 'boolean',
						'default' => true
					),
					'price' => array(
						'type' => 'boolean',
						'default' => true
					),
					'rating' => array(
						'type' => 'boolean',
						'default' => true
					),
					'button' => array(
						'type' => 'boolean',
						'default' => true
					)
				)
			),
			'categories' => array(
				'type' => 'array',
				'default' => array(
					
				)
			),
			'catOperator' => array(
				'type' => 'string',
				'default' => 'any'
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			),
			'stockStatus' => array(
				'type' => 'array'
			),
			'editMode' => array(
				'type' => 'boolean',
				'default' => true
			),
			'orderby' => array(
				'type' => 'string',
				'enum' => array(
					'date',
					'popularity',
					'price_asc',
					'price_desc',
					'rating',
					'title',
					'menu_order'
				),
				'default' => 'date'
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-price' => array(
		'name' => 'woocommerce/product-price',
		'version' => '1.0.0',
		'title' => 'Product Price',
		'description' => 'Display the price of a product.',
		'category' => 'woocommerce-product-elements',
		'attributes' => array(
			'productId' => array(
				'type' => 'number',
				'default' => 0
			),
			'isDescendentOfQueryLoop' => array(
				'type' => 'boolean',
				'default' => false
			),
			'textAlign' => array(
				'type' => 'string',
				'default' => ''
			),
			'isDescendentOfSingleProductTemplate' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isDescendentOfSingleProductBlock' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'usesContext' => array(
			'query',
			'queryId',
			'postId'
		),
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'supports' => array(
			'html' => false
		),
		'ancestor' => array(
			'woocommerce/all-products',
			'woocommerce/single-product',
			'woocommerce/product-template',
			'core/post-template'
		),
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-rating' => array(
		'name' => 'woocommerce/product-rating',
		'version' => '1.0.0',
		'icon' => 'info',
		'title' => 'Product Rating',
		'description' => 'Display the average rating of a product.',
		'attributes' => array(
			'productId' => array(
				'type' => 'number',
				'default' => 0
			),
			'isDescendentOfQueryLoop' => array(
				'type' => 'boolean',
				'default' => false
			),
			'textAlign' => array(
				'type' => 'string',
				'default' => ''
			),
			'isDescendentOfSingleProductBlock' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isDescendentOfSingleProductTemplate' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'usesContext' => array(
			'query',
			'queryId',
			'postId'
		),
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'align' => true
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-rating-counter' => array(
		'name' => 'woocommerce/product-rating-counter',
		'version' => '1.0.0',
		'title' => 'Product Rating Counter',
		'description' => 'Display the review count of a product',
		'category' => 'woocommerce-product-elements',
		'attributes' => array(
			'productId' => array(
				'type' => 'number',
				'default' => 0
			),
			'isDescendentOfQueryLoop' => array(
				'type' => 'boolean',
				'default' => false
			),
			'textAlign' => array(
				'type' => 'string',
				'default' => ''
			),
			'isDescendentOfSingleProductBlock' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isDescendentOfSingleProductTemplate' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'usesContext' => array(
			'query',
			'queryId',
			'postId'
		),
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'align' => true
		),
		'ancestor' => array(
			'woocommerce/single-product'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-rating-stars' => array(
		'name' => 'woocommerce/product-rating-stars',
		'title' => 'Product Rating Stars',
		'description' => 'Display the average rating of a product with stars',
		'category' => 'woocommerce-product-elements',
		'attributes' => array(
			'productId' => array(
				'type' => 'number',
				'default' => 0
			),
			'isDescendentOfQueryLoop' => array(
				'type' => 'boolean',
				'default' => false
			),
			'textAlign' => array(
				'type' => 'string',
				'default' => ''
			),
			'isDescendentOfSingleProductBlock' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isDescendentOfSingleProductTemplate' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'usesContext' => array(
			'query',
			'queryId',
			'postId'
		),
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'align' => true
		),
		'ancestor' => array(
			'woocommerce/single-product'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-results-count' => array(
		'name' => 'woocommerce/product-results-count',
		'version' => '1.0.0',
		'title' => 'Product Results Count',
		'description' => 'Display the number of products on the archive page or search result page.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'color' => array(
				'text' => true,
				'background' => false
			),
			'typography' => array(
				'fontSize' => true
			)
		),
		'attributes' => array(
			
		),
		'usesContext' => array(
			'queryId'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-reviews' => array(
		'name' => 'woocommerce/product-reviews',
		'version' => '1.0.0',
		'icon' => 'admin-comments',
		'title' => 'Product Reviews',
		'description' => 'A block that shows the reviews for a product.',
		'category' => 'woocommerce-product-elements',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			
		),
		'attributes' => array(
			
		),
		'usesContext' => array(
			'postId'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-sale-badge' => array(
		'name' => 'woocommerce/product-sale-badge',
		'version' => '1.0.0',
		'title' => 'On-Sale Badge',
		'description' => 'Displays an on-sale badge if the product is on-sale.',
		'category' => 'woocommerce-product-elements',
		'attributes' => array(
			'productId' => array(
				'type' => 'number',
				'default' => 0
			),
			'isDescendentOfQueryLoop' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isDescendentOfSingleProductTemplate' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'usesContext' => array(
			'query',
			'queryId',
			'postId'
		),
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-summary' => array(
		'name' => 'woocommerce/product-summary',
		'version' => '1.0.0',
		'icon' => 'page',
		'title' => 'Product Summary',
		'description' => 'Display a short description about a product.',
		'attributes' => array(
			'productId' => array(
				'type' => 'number',
				'default' => 0
			),
			'isDescendentOfQueryLoop' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isDescendentOfSingleProductTemplate' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isDescendentOfSingleProductBlock' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isDescendantOfAllProducts' => array(
				'type' => 'boolean',
				'default' => false
			),
			'showDescriptionIfEmpty' => array(
				'type' => 'boolean',
				'default' => false
			),
			'showLink' => array(
				'type' => 'boolean',
				'default' => false
			),
			'summaryLength' => array(
				'type' => 'number',
				'default' => 0
			),
			'linkText' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'usesContext' => array(
			'query',
			'queryId',
			'postId'
		),
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-tag' => array(
		'name' => 'woocommerce/product-tag',
		'title' => 'Products by Tag',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'description' => 'Display a grid of products with selected tags.',
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'inserter' => false
		),
		'attributes' => array(
			'columns' => array(
				'type' => 'number',
				'default' => 3
			),
			'rows' => array(
				'type' => 'number',
				'default' => 3
			),
			'alignButtons' => array(
				'type' => 'boolean',
				'default' => false
			),
			'contentVisibility' => array(
				'type' => 'object',
				'default' => array(
					'image' => true,
					'title' => true,
					'price' => true,
					'rating' => true,
					'button' => true
				),
				'properties' => array(
					'image' => array(
						'type' => 'boolean',
						'default' => true
					),
					'title' => array(
						'type' => 'boolean',
						'default' => true
					),
					'price' => array(
						'type' => 'boolean',
						'default' => true
					),
					'rating' => array(
						'type' => 'boolean',
						'default' => true
					),
					'button' => array(
						'type' => 'boolean',
						'default' => true
					)
				)
			),
			'tags' => array(
				'type' => 'array',
				'default' => array(
					
				)
			),
			'tagOperator' => array(
				'type' => 'string',
				'default' => 'any'
			),
			'orderby' => array(
				'type' => 'string',
				'default' => 'date'
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			),
			'stockStatus' => array(
				'type' => 'array'
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'product-template' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'woocommerce/product-template',
		'title' => 'Product Template',
		'category' => 'woocommerce',
		'description' => 'Contains the block elements used to render a product.',
		'keywords' => array(
			'WooCommerce'
		),
		'textdomain' => 'woocommerce',
		'usesContext' => array(
			'queryId',
			'query',
			'queryContext',
			'displayLayout',
			'templateSlug',
			'postId',
			'queryContextIncludes',
			'collection',
			'__privateProductCollectionPreviewState'
		),
		'supports' => array(
			'inserter' => false,
			'reusable' => false,
			'html' => false,
			'align' => array(
				'wide',
				'full'
			),
			'anchor' => true,
			'color' => array(
				'gradients' => true,
				'link' => true,
				'__experimentalDefaultControls' => array(
					'background' => true,
					'text' => true
				)
			),
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true,
				'__experimentalFontFamily' => true,
				'__experimentalFontWeight' => true,
				'__experimentalFontStyle' => true,
				'__experimentalTextTransform' => true,
				'__experimentalTextDecoration' => true,
				'__experimentalLetterSpacing' => true,
				'__experimentalDefaultControls' => array(
					'fontSize' => true
				)
			)
		)
	),
	'product-top-rated' => array(
		'name' => 'woocommerce/product-top-rated',
		'title' => 'Top Rated Products',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'description' => 'Display a grid of your top rated products.',
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'inserter' => false
		),
		'attributes' => array(
			'columns' => array(
				'type' => 'number',
				'default' => 3
			),
			'rows' => array(
				'type' => 'number',
				'default' => 3
			),
			'alignButtons' => array(
				'type' => 'boolean',
				'default' => false
			),
			'contentVisibility' => array(
				'type' => 'object',
				'default' => array(
					'image' => true,
					'title' => true,
					'price' => true,
					'rating' => true,
					'button' => true
				),
				'properties' => array(
					'image' => array(
						'type' => 'boolean',
						'default' => true
					),
					'title' => array(
						'type' => 'boolean',
						'default' => true
					),
					'price' => array(
						'type' => 'boolean',
						'default' => true
					),
					'rating' => array(
						'type' => 'boolean',
						'default' => true
					),
					'button' => array(
						'type' => 'boolean',
						'default' => true
					)
				)
			),
			'categories' => array(
				'type' => 'array',
				'default' => array(
					
				)
			),
			'catOperator' => array(
				'type' => 'string',
				'default' => 'any'
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			),
			'stockStatus' => array(
				'type' => 'array'
			),
			'editMode' => array(
				'type' => 'boolean',
				'default' => true
			),
			'orderby' => array(
				'type' => 'string',
				'enum' => array(
					'date',
					'popularity',
					'price_asc',
					'price_desc',
					'rating',
					'title',
					'menu_order'
				),
				'default' => 'rating'
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'products-by-attribute' => array(
		'name' => 'woocommerce/products-by-attribute',
		'title' => 'Products by Attribute',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'description' => 'Display a grid of products with selected attributes.',
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'inserter' => false
		),
		'attributes' => array(
			'attributes' => array(
				'type' => 'array',
				'default' => array(
					
				)
			),
			'attrOperator' => array(
				'type' => 'string',
				'enum' => array(
					'all',
					'any'
				),
				'default' => 'any'
			),
			'columns' => array(
				'type' => 'number',
				'default' => 3
			),
			'contentVisibility' => array(
				'type' => 'object',
				'default' => array(
					'image' => true,
					'title' => true,
					'price' => true,
					'rating' => true,
					'button' => true
				),
				'properties' => array(
					'image' => array(
						'type' => 'boolean',
						'default' => true
					),
					'title' => array(
						'type' => 'boolean',
						'default' => true
					),
					'price' => array(
						'type' => 'boolean',
						'default' => true
					),
					'rating' => array(
						'type' => 'boolean',
						'default' => true
					),
					'button' => array(
						'type' => 'boolean',
						'default' => true
					)
				)
			),
			'orderby' => array(
				'type' => 'string',
				'enum' => array(
					'date',
					'popularity',
					'price_asc',
					'price_desc',
					'rating',
					'title',
					'menu_order'
				),
				'default' => 'date'
			),
			'rows' => array(
				'type' => 'number',
				'default' => 3
			),
			'alignButtons' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			),
			'stockStatus' => array(
				'type' => 'array'
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'rating-filter' => array(
		'name' => 'woocommerce/rating-filter',
		'version' => '1.0.0',
		'title' => 'Filter by Rating Controls',
		'description' => 'Enable customers to filter the product grid by rating.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'html' => false,
			'multiple' => false,
			'color' => array(
				'background' => true,
				'text' => true,
				'button' => true
			),
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'showCounts' => array(
				'type' => 'boolean',
				'default' => false
			),
			'displayStyle' => array(
				'type' => 'string',
				'default' => 'list'
			),
			'showFilterButton' => array(
				'type' => 'boolean',
				'default' => false
			),
			'selectType' => array(
				'type' => 'string',
				'default' => 'multiple'
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'related-products' => array(
		'name' => 'woocommerce/related-products',
		'version' => '1.0.0',
		'title' => 'Related Products',
		'icon' => 'product',
		'description' => 'Display related products.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => true,
			'reusable' => false,
			'inserter' => false
		),
		'keywords' => array(
			'WooCommerce'
		),
		'usesContext' => array(
			'postId',
			'postType',
			'queryId'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'single-product' => array(
		'name' => 'woocommerce/single-product',
		'version' => '1.0.0',
		'icon' => 'info',
		'title' => 'Single Product',
		'description' => 'Display a single product.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			)
		),
		'attributes' => array(
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			),
			'productId' => array(
				'type' => 'number'
			)
		),
		'example' => array(
			'attributes' => array(
				'isPreview' => true
			)
		),
		'usesContext' => array(
			'postId',
			'postType',
			'queryId'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'stock-filter' => array(
		'name' => 'woocommerce/stock-filter',
		'version' => '1.0.0',
		'title' => 'Filter by Stock Controls',
		'description' => 'Enable customers to filter the product grid by stock status.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'html' => false,
			'multiple' => false,
			'color' => array(
				'background' => true,
				'text' => true,
				'button' => true
			),
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'headingLevel' => array(
				'type' => 'number',
				'default' => 3
			),
			'showCounts' => array(
				'type' => 'boolean',
				'default' => false
			),
			'showFilterButton' => array(
				'type' => 'boolean',
				'default' => false
			),
			'displayStyle' => array(
				'type' => 'string',
				'default' => 'list'
			),
			'selectType' => array(
				'type' => 'string',
				'default' => 'multiple'
			),
			'isPreview' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'store-notices' => array(
		'name' => 'woocommerce/store-notices',
		'version' => '1.0.0',
		'title' => 'Store Notices',
		'description' => 'Display shopper-facing notifications generated by WooCommerce or extensions.',
		'category' => 'woocommerce',
		'keywords' => array(
			'WooCommerce'
		),
		'supports' => array(
			'multiple' => false,
			'align' => array(
				'wide',
				'full'
			)
		),
		'attributes' => array(
			'align' => array(
				'type' => 'string',
				'default' => 'wide'
			)
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'cart-accepted-payment-methods-block' => array(
		'name' => 'woocommerce/cart-accepted-payment-methods-block',
		'version' => '1.0.0',
		'title' => 'Accepted Payment Methods',
		'description' => 'Display accepted payment methods.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => true
		),
		'parent' => array(
			'woocommerce/cart-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-cross-sells-block' => array(
		'name' => 'woocommerce/cart-cross-sells-block',
		'version' => '1.0.0',
		'title' => 'Cart Cross-Sells',
		'description' => 'Shows the Cross-Sells block.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => true
		),
		'parent' => array(
			'woocommerce/cart-items-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-cross-sells-products-block' => array(
		'name' => 'woocommerce/cart-cross-sells-products-block',
		'version' => '1.0.0',
		'title' => 'Cart Cross-Sells Products',
		'description' => 'Shows the Cross-Sells products.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'columns' => array(
				'type' => 'number',
				'default' => 3
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-cross-sells-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-express-payment-block' => array(
		'name' => 'woocommerce/cart-express-payment-block',
		'version' => '1.0.0',
		'title' => 'Express Checkout',
		'description' => 'Allow customers to breeze through with quick payment options.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'showButtonStyles' => array(
				'type' => 'boolean',
				'default' => false
			),
			'buttonHeight' => array(
				'type' => 'string',
				'default' => '48'
			),
			'buttonBorderRadius' => array(
				'type' => 'string',
				'default' => '4'
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-items-block' => array(
		'name' => 'woocommerce/cart-items-block',
		'version' => '1.0.0',
		'title' => 'Cart Items',
		'description' => 'Column containing cart items.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/filled-cart-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-line-items-block' => array(
		'name' => 'woocommerce/cart-line-items-block',
		'version' => '1.0.0',
		'title' => 'Cart Line Items',
		'description' => 'Block containing current line items in Cart.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-items-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-order-summary-block' => array(
		'name' => 'woocommerce/cart-order-summary-block',
		'version' => '1.0.0',
		'title' => 'Order Summary',
		'description' => 'Show customers a summary of their order.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-order-summary-coupon-form-block' => array(
		'name' => 'woocommerce/cart-order-summary-coupon-form-block',
		'version' => '1.0.0',
		'title' => 'Coupon Form',
		'description' => 'Shows the apply coupon form.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => false,
					'move' => false
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-order-summary-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-order-summary-discount-block' => array(
		'name' => 'woocommerce/cart-order-summary-discount-block',
		'version' => '1.0.0',
		'title' => 'Discount',
		'description' => 'Shows the cart discount row.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-order-summary-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-order-summary-fee-block' => array(
		'name' => 'woocommerce/cart-order-summary-fee-block',
		'version' => '1.0.0',
		'title' => 'Fees',
		'description' => 'Shows the cart fee row.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-order-summary-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-order-summary-heading-block' => array(
		'name' => 'woocommerce/cart-order-summary-heading-block',
		'version' => '1.0.0',
		'title' => 'Heading',
		'description' => 'Shows the heading row.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'content' => array(
				'type' => 'string',
				'default' => 'Cart totals'
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => false,
					'move' => false
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-order-summary-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-order-summary-shipping-block' => array(
		'name' => 'woocommerce/cart-order-summary-shipping-block',
		'version' => '1.0.0',
		'title' => 'Shipping',
		'description' => 'Shows the cart shipping row.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-order-summary-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-order-summary-subtotal-block' => array(
		'name' => 'woocommerce/cart-order-summary-subtotal-block',
		'version' => '1.0.0',
		'title' => 'Subtotal',
		'description' => 'Shows the cart subtotal row.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-order-summary-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-order-summary-taxes-block' => array(
		'name' => 'woocommerce/cart-order-summary-taxes-block',
		'version' => '1.0.0',
		'title' => 'Taxes',
		'description' => 'Shows the cart taxes row.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-order-summary-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-order-summary-totals-block' => array(
		'name' => 'woocommerce/cart-order-summary-totals-block',
		'version' => '1.0.0',
		'title' => 'Totals',
		'description' => 'Shows the subtotal, fees, discounts, shipping and taxes.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => false
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-order-summary-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'cart-totals-block' => array(
		'name' => 'woocommerce/cart-totals-block',
		'version' => '1.0.0',
		'title' => 'Cart Totals',
		'description' => 'Column containing the cart totals.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'checkbox' => array(
				'type' => 'boolean',
				'default' => false
			),
			'text' => array(
				'type' => 'string',
				'required' => false
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/filled-cart-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-actions-block' => array(
		'name' => 'woocommerce/checkout-actions-block',
		'version' => '1.0.0',
		'title' => 'Actions',
		'description' => 'Allow customers to place their order.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			),
			'cartPageId' => array(
				'type' => 'number',
				'default' => 0
			),
			'showReturnToCart' => array(
				'type' => 'boolean',
				'default' => true
			),
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'priceSeparator' => array(
				'type' => 'string',
				'default' => '·'
			)
		),
		'parent' => array(
			'woocommerce/checkout-fields-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-additional-information-block' => array(
		'name' => 'woocommerce/checkout-additional-information-block',
		'version' => '1.0.0',
		'title' => 'Additional information',
		'description' => 'Render additional fields in the \'Additional information\' location.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => false
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-fields-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-billing-address-block' => array(
		'name' => 'woocommerce/checkout-billing-address-block',
		'version' => '1.0.0',
		'title' => 'Billing Address',
		'description' => 'Collect your customer\'s billing address.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-fields-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-contact-information-block' => array(
		'name' => 'woocommerce/checkout-contact-information-block',
		'version' => '1.0.0',
		'title' => 'Contact Information',
		'description' => 'Collect your customer\'s contact information.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-fields-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-express-payment-block' => array(
		'name' => 'woocommerce/checkout-express-payment-block',
		'version' => '1.0.0',
		'title' => 'Express Checkout',
		'description' => 'Allow customers to breeze through with quick payment options.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'showButtonStyles' => array(
				'type' => 'boolean',
				'default' => false
			),
			'buttonHeight' => array(
				'type' => 'string',
				'default' => '48'
			),
			'buttonBorderRadius' => array(
				'type' => 'string',
				'default' => '4'
			),
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-fields-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-fields-block' => array(
		'name' => 'woocommerce/checkout-fields-block',
		'version' => '1.0.0',
		'title' => 'Checkout Fields',
		'description' => 'Column containing checkout address fields.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-order-note-block' => array(
		'name' => 'woocommerce/checkout-order-note-block',
		'version' => '1.0.0',
		'title' => 'Order Note',
		'description' => 'Allow customers to add a note to their order.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => false,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-fields-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-order-summary-block' => array(
		'name' => 'woocommerce/checkout-order-summary-block',
		'version' => '1.0.0',
		'title' => 'Order Summary',
		'description' => 'Show customers a summary of their order.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-order-summary-cart-items-block' => array(
		'name' => 'woocommerce/checkout-order-summary-cart-items-block',
		'version' => '1.0.0',
		'title' => 'Cart Items',
		'description' => 'Shows cart items.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'disableProductDescriptions' => array(
				'type' => 'boolean',
				'default' => false
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => false
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-order-summary-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-order-summary-coupon-form-block' => array(
		'name' => 'woocommerce/checkout-order-summary-coupon-form-block',
		'version' => '1.0.0',
		'title' => 'Coupon Form',
		'description' => 'Shows the apply coupon form.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => false,
					'move' => false
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-order-summary-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-order-summary-discount-block' => array(
		'name' => 'woocommerce/checkout-order-summary-discount-block',
		'version' => '1.0.0',
		'title' => 'Discount',
		'description' => 'Shows the cart discount row.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-order-summary-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-order-summary-fee-block' => array(
		'name' => 'woocommerce/checkout-order-summary-fee-block',
		'version' => '1.0.0',
		'title' => 'Fees',
		'description' => 'Shows the cart fee row.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-order-summary-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-order-summary-shipping-block' => array(
		'name' => 'woocommerce/checkout-order-summary-shipping-block',
		'version' => '1.0.0',
		'title' => 'Shipping',
		'description' => 'Shows the cart shipping row.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-order-summary-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-order-summary-subtotal-block' => array(
		'name' => 'woocommerce/checkout-order-summary-subtotal-block',
		'version' => '1.0.0',
		'title' => 'Subtotal',
		'description' => 'Shows the cart subtotal row.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-order-summary-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-order-summary-taxes-block' => array(
		'name' => 'woocommerce/checkout-order-summary-taxes-block',
		'version' => '1.0.0',
		'title' => 'Taxes',
		'description' => 'Shows the cart taxes row.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-order-summary-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-order-summary-totals-block' => array(
		'name' => 'woocommerce/checkout-order-summary-totals-block',
		'version' => '1.0.0',
		'title' => 'Totals',
		'description' => 'Shows the subtotal, fees, discounts, shipping and taxes.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => false
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-order-summary-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-payment-block' => array(
		'name' => 'woocommerce/checkout-payment-block',
		'version' => '1.0.0',
		'title' => 'Payment Options',
		'description' => 'Payment options for your store.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-fields-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-pickup-options-block' => array(
		'name' => 'woocommerce/checkout-pickup-options-block',
		'version' => '1.0.0',
		'title' => 'Pickup Method',
		'description' => 'Shows local pickup locations.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-fields-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-shipping-address-block' => array(
		'name' => 'woocommerce/checkout-shipping-address-block',
		'version' => '1.0.0',
		'title' => 'Shipping Address',
		'description' => 'Collect your customer\'s shipping address.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-fields-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-shipping-method-block' => array(
		'name' => 'woocommerce/checkout-shipping-method-block',
		'version' => '1.0.0',
		'title' => 'Delivery',
		'description' => 'Select between shipping or local pickup.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-fields-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-shipping-methods-block' => array(
		'name' => 'woocommerce/checkout-shipping-methods-block',
		'version' => '1.0.0',
		'title' => 'Shipping Options',
		'description' => 'Display shipping options and rates for your store.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/checkout-fields-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-terms-block' => array(
		'name' => 'woocommerce/checkout-terms-block',
		'version' => '1.0.0',
		'title' => 'Terms and Conditions',
		'description' => 'Ensure that customers agree to your Terms & Conditions and Privacy Policy.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'checkbox' => array(
				'type' => 'boolean',
				'default' => false
			),
			'text' => array(
				'type' => 'string',
				'required' => false
			),
			'showSeparator' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'parent' => array(
			'woocommerce/checkout-fields-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'checkout-totals-block' => array(
		'name' => 'woocommerce/checkout-totals-block',
		'version' => '1.0.0',
		'title' => 'Checkout Totals',
		'description' => 'Column containing the checkout totals.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'className' => array(
				'type' => 'string',
				'default' => ''
			),
			'checkbox' => array(
				'type' => 'boolean',
				'default' => false
			),
			'text' => array(
				'type' => 'string',
				'required' => false
			)
		),
		'parent' => array(
			'woocommerce/checkout'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'empty-cart-block' => array(
		'name' => 'woocommerce/empty-cart-block',
		'version' => '1.0.0',
		'title' => 'Empty Cart',
		'description' => 'Contains blocks that are displayed when the cart is empty.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => array(
				'wide'
			),
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/cart'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'empty-mini-cart-contents-block' => array(
		'name' => 'woocommerce/empty-mini-cart-contents-block',
		'version' => '1.0.0',
		'title' => 'Empty Mini-Cart view',
		'description' => 'Blocks that are displayed when the Mini-Cart is empty.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/mini-cart-contents'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'filled-cart-block' => array(
		'name' => 'woocommerce/filled-cart-block',
		'version' => '1.0.0',
		'title' => 'Filled Cart',
		'description' => 'Contains blocks that are displayed when the cart contains products.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => array(
				'wide'
			),
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/cart'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	),
	'filled-mini-cart-contents-block' => array(
		'name' => 'woocommerce/filled-mini-cart-contents-block',
		'version' => '1.0.0',
		'title' => 'Filled Mini-Cart view',
		'description' => 'Contains blocks that display the content of the Mini-Cart.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/mini-cart-contents'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'mini-cart-cart-button-block' => array(
		'name' => 'woocommerce/mini-cart-cart-button-block',
		'version' => '1.0.0',
		'title' => 'Mini-Cart View Cart Button',
		'description' => 'Block that displays the cart button when the Mini-Cart has products.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => true,
			'color' => array(
				'text' => true,
				'background' => true
			)
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => false,
					'move' => false
				)
			)
		),
		'styles' => array(
			array(
				'name' => 'fill',
				'label' => 'Fill'
			),
			array(
				'name' => 'outline',
				'label' => 'Outline',
				'isDefault' => true
			)
		),
		'parent' => array(
			'woocommerce/mini-cart-footer-block'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'mini-cart-checkout-button-block' => array(
		'name' => 'woocommerce/mini-cart-checkout-button-block',
		'version' => '1.0.0',
		'title' => 'Mini-Cart Proceed to Checkout Button',
		'description' => 'Block that displays the checkout button when the Mini-Cart has products.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => true,
			'color' => array(
				'text' => true,
				'background' => true
			)
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => false,
					'move' => false
				)
			)
		),
		'styles' => array(
			array(
				'name' => 'fill',
				'label' => 'Fill',
				'isDefault' => true
			),
			array(
				'name' => 'outline',
				'label' => 'Outline'
			)
		),
		'parent' => array(
			'woocommerce/mini-cart-footer-block'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'mini-cart-footer-block' => array(
		'name' => 'woocommerce/mini-cart-footer-block',
		'version' => '1.0.0',
		'title' => 'Mini-Cart Footer',
		'description' => 'Block that displays the footer of the Mini-Cart block.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/filled-mini-cart-contents-block'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'mini-cart-items-block' => array(
		'name' => 'woocommerce/mini-cart-items-block',
		'version' => '1.0.0',
		'title' => 'Mini-Cart Items',
		'description' => 'Contains the products table and other custom blocks of filled mini-cart.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/filled-mini-cart-contents-block'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'mini-cart-products-table-block' => array(
		'name' => 'woocommerce/mini-cart-products-table-block',
		'version' => '1.0.0',
		'title' => 'Mini-Cart Products Table',
		'description' => 'Block that displays the products table of the Mini-Cart block.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => false
				)
			)
		),
		'parent' => array(
			'woocommerce/mini-cart-items-block'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'mini-cart-shopping-button-block' => array(
		'name' => 'woocommerce/mini-cart-shopping-button-block',
		'version' => '1.0.0',
		'title' => 'Mini-Cart Shopping Button',
		'description' => 'Block that displays the shopping button when the Mini-Cart is empty.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => true,
			'color' => array(
				'text' => true,
				'background' => true
			)
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => false,
					'move' => false
				)
			)
		),
		'styles' => array(
			array(
				'name' => 'fill',
				'label' => 'Fill',
				'isDefault' => true
			),
			array(
				'name' => 'outline',
				'label' => 'Outline'
			)
		),
		'parent' => array(
			'woocommerce/empty-mini-cart-contents-block'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'mini-cart-title-block' => array(
		'name' => 'woocommerce/mini-cart-title-block',
		'version' => '1.0.0',
		'title' => 'Mini-Cart Title',
		'description' => 'Block that displays the title of the Mini-Cart block.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false,
			'color' => array(
				'text' => true,
				'background' => false
			),
			'typography' => array(
				'fontSize' => true
			)
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/filled-mini-cart-contents-block'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'mini-cart-title-items-counter-block' => array(
		'name' => 'woocommerce/mini-cart-title-items-counter-block',
		'version' => '1.0.0',
		'title' => 'Mini-Cart Title Items Counter',
		'description' => 'Block that displays the items counter part of the Mini-Cart Title block.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false,
			'color' => array(
				'text' => true,
				'background' => true
			),
			'typography' => array(
				'fontSize' => true
			),
			'spacing' => array(
				'padding' => true
			)
		),
		'parent' => array(
			'woocommerce/mini-cart-title-block'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'mini-cart-title-label-block' => array(
		'name' => 'woocommerce/mini-cart-title-label-block',
		'version' => '1.0.0',
		'title' => 'Mini-Cart Title Label',
		'description' => 'Block that displays the \'Your cart\' part of the Mini-Cart Title block.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false,
			'color' => array(
				'text' => true,
				'background' => true
			),
			'typography' => array(
				'fontSize' => true
			),
			'spacing' => array(
				'padding' => true
			)
		),
		'attributes' => array(
			'label' => array(
				'type' => 'string'
			)
		),
		'parent' => array(
			'woocommerce/mini-cart-title-block'
		),
		'textdomain' => 'woocommerce',
		'apiVersion' => 3,
		'$schema' => 'https://schemas.wp.org/trunk/block.json'
	),
	'proceed-to-checkout-block' => array(
		'name' => 'woocommerce/proceed-to-checkout-block',
		'version' => '1.0.0',
		'title' => 'Proceed to Checkout',
		'description' => 'Allow customers proceed to Checkout.',
		'category' => 'woocommerce',
		'supports' => array(
			'align' => false,
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => false
		),
		'attributes' => array(
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'remove' => true,
					'move' => true
				)
			)
		),
		'parent' => array(
			'woocommerce/cart-totals-block'
		),
		'textdomain' => 'woocommerce',
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3
	)
);
