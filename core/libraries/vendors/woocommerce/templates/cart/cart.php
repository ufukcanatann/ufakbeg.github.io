<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<?php if ( wp_is_mobile() ) { ?>
		<table class="shop_table cart1 woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>
					<th class="product-thumbnail"><?php esc_html_e( 'Product', 'claue' ); ?></th>
					<th class="product-name">&nbsp;</th>
					<th class="product-subtotal"><?php esc_html_e( 'Total', 'claue' ); ?></th>
					<th class="product-removed">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
					if ( class_exists( 'WPA_WCPB' ) ) {
						$is_bundle = 0;
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product   			= apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id 			= apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
							$product_bundle_class 	= isset( $cart_item['bundle-parent'] ) ? 'product-bundle-item' : '';
							$product_bundle_class 	.= isset( $cart_item['bundle-products'] ) ? 'has-product-bundle' : '';

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
								?>
								<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); echo ' '.$product_bundle_class ?>">
									<?php if ( ! empty( $cart_item['bundle-products'] ) ) :?>
										<tr class="product-bundle-title">
											<td colspan="4">
												<h4><?php esc_attr_e( 'Product bundles', 'claue' );?></h4>
											</td>
										</tr>
									<?php endif;?>
									<?php
										if ( ! isset( $cart_item['bundle-parent'] ) ) :
									?>
										<td class="product-thumbnail">
											<?php
												$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

												if ( ! $product_permalink ) {
													echo wp_kses_post( $thumbnail );
												} else {
													printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
												}
											?>
										</td>

										<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'claue' ); ?>">
											<?php
												if ( ! $product_permalink ) {
													echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
												} else {
													echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
												}

												// Meta data
												if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
													echo WC()->cart->get_item_data( $cart_item );
												} else {
													echo wc_get_formatted_cart_item_data( $cart_item );
												}

												// Backorder notification
												if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
													echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'claue' ) . '</p>' ) );

												}
											?>
											<div class="product-price">
												<?php
													echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
												?>
											</div>
											<?php
												if ( $_product->is_sold_individually() ) {
													$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
												} else {
													$product_quantity  = woocommerce_quantity_input( array(
														'input_name'   => "cart[{$cart_item_key}][qty]",
														'input_value'  => $cart_item['quantity'],
														'max_value'    => $_product->get_max_purchase_quantity(),
														'min_value'    => '0',
														'product_name' => $_product->get_name(),

													), $_product, false );
												}
												echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
											?>
										</td>
									<?php else:?>
										<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'claue' ); ?>" colspan="2">
											<?php
												if ( ! $product_permalink ) {
													echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
												} else {
													echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
												}

												// Meta data
												if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
													echo WC()->cart->get_item_data( $cart_item );
												} else {
													echo wc_get_formatted_cart_item_data( $cart_item );
												}

												// Backorder notification
												if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
													echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'claue' ) . '</p>';
												}
											?>
										</td>
									<?php endif;?>

									<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'claue' ); ?>">
										<?php
											echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
										?>
									</td>
									<td class="product-remove">
										<?php
											if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
												echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
													'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
													esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
													esc_html__( 'Remove this item', 'claue' ),
													esc_attr( $product_id ),
													esc_attr( $_product->get_sku() )
												), $cart_item_key );
											} else {
												echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
													'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
													esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
													esc_html__( 'Remove this item', 'claue' ),
													esc_attr( $product_id ),
													esc_attr( $_product->get_sku() )
												), $cart_item_key );
											}
										?>
									</td>
								</tr>

								<?php if ( ! empty( $cart_item['bundle-products'] ) ) : ?>
								<tr class="bundle-products bundle-off-<?php echo esc_attr($product_id);?>">
									<td colspan="4">
										<?php
											// Get Custom variable of bundle product
											$custom_variable 	= $cart_item['bundle-variable'];
											$bundles 			= get_post_meta( $product_id, 'wpa_wcpb', true );
											$bundles_added 		= explode( ',', $cart_item['bundle-products'] );
											if ( $bundles ) {
												echo '<ul class="product-bundle">';
												foreach( $bundles as $key => $val ){
													if ( in_array( $val['product_id'], $bundles_added ) ) {
														$product_item = wc_get_product( intval( $val['product_id'] ) );
														echo '<li class="flex middle-xs">';
														echo '<a href="'. $product_item->get_permalink() .'" title="'. $product_item->get_name() .'">'. $product_item->get_image() .'</a>';
														echo '<div><a href="'. $product_item->get_permalink() .'" title="'. $product_item->get_name() .'">'. $product_item->get_name() .'</a>';

														if ( isset( $custom_variable[$val['product_id']] ) && count( $custom_variable[$val['product_id']] ) > 0 ) {
															// Custom variable before add produt bundle to cart
															echo '<span class="db" style="text-transform: capitalize;">';
																echo wp_kses_post($custom_variable[$val['product_id']]['variable']);
															echo '</span>';
														} else {
															// Get variable
															if ( ! empty( $val['variable'] ) ) {
																$i = 0;
																foreach ( $val['variable'] as $key => $value ) { $i++;
																	echo '<span class="db" style="text-transform: capitalize;">';
																		if( $i == count( $val['variable'] ) ) {
																			echo substr( $key, 13 ) . ': ' . $value;	
																		}else {
																			echo substr( $key, 13 ) . ': ' . $value .' + ';
																		}
																	echo '</span>';
																}
															}
														}
														echo '</div></li>';
													}
												}
												echo '</ul>';
											}
										?>
									</td>
								</tr>
								<?php endif;?>
								<?php
							} 
						}
					} else {
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>
							<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
								<td class="product-thumbnail">
									<?php
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

										if ( ! $product_permalink ) {
											echo wp_kses_post( $thumbnail );
										} else {
											printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
										}
									?>
								</td>

								<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'claue' ); ?>">
									<?php
										if ( ! $product_permalink ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
										}

										// Meta data
										if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
											echo WC()->cart->get_item_data( $cart_item );
										} else {
											echo wc_get_formatted_cart_item_data( $cart_item );
										}

										// Backorder notification
										if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'claue' ) . '</p>' ) );
										}
									?>
									<div class="product-price">
										<?php
											echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
										?>
									</div>
									<?php
										if ( $_product->is_sold_individually() ) {
											$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key ); // PHPCS: XSS ok.
										} else {
											$product_quantity = woocommerce_quantity_input( array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => '0',
												'product_name' => $_product->get_name(),
											), $_product, false );
										}

										echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
									?>
								</td>

								<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'claue' ); ?>">
									<?php
										echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
									?>
								</td>
								<td class="product-remove">
									<?php
										if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
											echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
												'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
												esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
												esc_html__( 'Remove this item', 'claue' ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											), $cart_item_key );
										} else {
											echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
												'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_html__( 'Remove this item', 'claue' ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											), $cart_item_key );
										}
									?>
								</td>

							</tr>

							<?php
						}
					} 	
				}
				do_action( 'woocommerce_cart_contents' );
				?>
				<tr>
					<td colspan="4" class="actions">

						<?php if ( wc_coupons_enabled() ) { ?>
							<div class="coupon flex middle-xs">

								<label for="coupon_code"><?php _e( 'Coupon:', 'claue' ); ?></label> <input type="text" name="coupon_code" class="input-text br__40" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'claue' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'claue' ); ?>"><?php esc_attr_e( 'Apply coupon', 'claue' ); ?></button>

								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						<?php } ?>

						<button type="submit" class="button update-cart" name="update_cart" value="<?php esc_attr_e( 'Update Cart', 'claue' ); ?>"><?php esc_attr_e( 'Update Cart', 'claue' ); ?></button>

						<?php do_action( 'woocommerce_cart_actions' ); ?>

						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					</td>
				</tr>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table>

	<?php } else { ?>
		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>
					<th class="product-thumbnail">&nbsp;</th>
					<th class="product-name"><?php esc_html_e( 'Product', 'claue' ); ?></th>
					<th class="product-price"><?php esc_html_e( 'Price', 'claue' ); ?></th>
					<th class="product-quantity"><?php esc_html_e( 'Quantity', 'claue' ); ?></th>
					<th class="product-subtotal"><?php esc_html_e( 'Total', 'claue' ); ?></th>
					<th class="product-removed"><?php esc_html_e( 'Remove', 'claue' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
					if ( class_exists( 'WPA_WCPB' ) ) {
						$is_bundle = 0;
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product   			= apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id 			= apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
							$product_bundle_class 	= isset( $cart_item['bundle-parent'] ) ? 'product-bundle-item' : '';
							$product_bundle_class 	.= isset( $cart_item['bundle-products'] ) ? 'has-product-bundle' : '';

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
								?>
								<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); echo ' '.$product_bundle_class ?>">
									<?php if ( ! empty( $cart_item['bundle-products'] ) ) :?>
										<tr class="product-bundle-title">
											<td colspan="6">
												<h4><?php esc_attr_e( 'Product bundles', 'claue' );?></h4>
											</td>
										</tr>
									<?php endif;?>
									<?php
										if ( ! isset( $cart_item['bundle-parent'] ) ) :
									?>
										<td class="product-thumbnail">
											<?php
												$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

												if ( ! $product_permalink ) {
													echo wp_kses_post( $thumbnail );
												} else {
													printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
												}
											?>
										</td>

										<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'claue' ); ?>">
											<?php
												if ( ! $product_permalink ) {
													echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
												} else {
													echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
												}

												// Meta data
												if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
													echo WC()->cart->get_item_data( $cart_item );
												} else {
													echo wc_get_formatted_cart_item_data( $cart_item );
												}

												// Backorder notification
												if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
													echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'claue' ) . '</p>' ) );

												}
											?>
										</td>
									<?php else:?>
										<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'claue' ); ?>" colspan="2">
											<?php
												if ( ! $product_permalink ) {
													echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
												} else {
													echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
												}

												// Meta data
												if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
													echo WC()->cart->get_item_data( $cart_item );
												} else {
													echo wc_get_formatted_cart_item_data( $cart_item );
												}

												// Backorder notification
												if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
													echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'claue' ) . '</p>';
												}
											?>
										</td>
									<?php endif;?>

									<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'claue' ); ?>">
										<?php
											echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
										?>
									</td>
									<?php
										if ( ! isset( $cart_item['bundle-parent'] ) ) :
									?>
										<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'claue' ); ?>">
											<?php
												if ( $_product->is_sold_individually() ) {
													$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
												} else {
													$product_quantity  = woocommerce_quantity_input( array(
														'input_name'   => "cart[{$cart_item_key}][qty]",
														'input_value'  => $cart_item['quantity'],
														'max_value'    => $_product->get_max_purchase_quantity(),
														'min_value'    => '0',
														'product_name' => $_product->get_name(),

													), $_product, false );
												}
												echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
											?>
										</td>
									<?php else: ?>
										<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'claue' ); ?>">
											<?php
												$product_quantity = $cart_item['quantity'];
												echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
											?>
										</td>
									<?php endif;?>
									<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'claue' ); ?>">
										<?php
											echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
										?>
									</td>
									<td class="product-remove">
										<?php
											if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
												echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
													'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
													esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
													esc_html__( 'Remove this item', 'claue' ),
													esc_attr( $product_id ),
													esc_attr( $_product->get_sku() )
												), $cart_item_key );
											} else {
												echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
													'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
													esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
													esc_html__( 'Remove this item', 'claue' ),
													esc_attr( $product_id ),
													esc_attr( $_product->get_sku() )
												), $cart_item_key );
											}
										?>
									</td>
								</tr>

								<?php if ( ! empty( $cart_item['bundle-products'] ) ) : ?>
								<tr class="bundle-products bundle-off-<?php echo esc_attr($product_id);?>">
									<td colspan="6">
										<?php
											// Get Custom variable of bundle product
											$custom_variable 	= $cart_item['bundle-variable'];
											$bundles 			= get_post_meta( $product_id, 'wpa_wcpb', true );
											$bundles_added 		= explode( ',', $cart_item['bundle-products'] );
											if ( $bundles ) {
												echo '<ul class="product-bundle">';
												foreach( $bundles as $key => $val ){
													if ( in_array( $val['product_id'], $bundles_added ) ) {
														$product_item = wc_get_product( intval( $val['product_id'] ) );
														echo '<li class="flex middle-xs">';
														echo '<a href="'. $product_item->get_permalink() .'" title="'. $product_item->get_name() .'">'. $product_item->get_image() .'</a>';
														echo '<div><a href="'. $product_item->get_permalink() .'" title="'. $product_item->get_name() .'">'. $product_item->get_name() .'</a>';

														if ( isset( $custom_variable[$val['product_id']] ) && count( $custom_variable[$val['product_id']] ) > 0 ) {
															// Custom variable before add produt bundle to cart
															echo '<span class="db" style="text-transform: capitalize;">';
																echo wp_kses_post($custom_variable[$val['product_id']]['variable']);
															echo '</span>';
														} else {
															// Get variable
															if ( ! empty( $val['variable'] ) ) {
																$i = 0;
																foreach ( $val['variable'] as $key => $value ) { $i++;
																	echo '<span class="db" style="text-transform: capitalize;">';
																		if( $i == count( $val['variable'] ) ) {
																			echo substr( $key, 13 ) . ': ' . $value;	
																		}else {
																			echo substr( $key, 13 ) . ': ' . $value .' + ';
																		}
																	echo '</span>';
																}
															}
														}
														echo '</div></li>';
													}
												}
												echo '</ul>';
											}
										?>
									</td>
								</tr>
								<?php endif;?>
								<?php
							} 
						}
					} else {
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>
							<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
								<td class="product-thumbnail">
									<?php
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

										if ( ! $product_permalink ) {
											echo wp_kses_post( $thumbnail );
										} else {
											printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
										}
									?>
								</td>

								<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'claue' ); ?>">
									<?php
										if ( ! $product_permalink ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
										}

										// Meta data
										if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
											echo WC()->cart->get_item_data( $cart_item );
										} else {
											echo wc_get_formatted_cart_item_data( $cart_item );
										}

										// Backorder notification
										if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'claue' ) . '</p>' ) );
										}
									?>
								</td>

								<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'claue' ); ?>">
									<?php
										echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
									?>
								</td>

								<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'claue' ); ?>">
									<?php
										if ( $_product->is_sold_individually() ) {
											$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key ); // PHPCS: XSS ok.
										} else {
											$product_quantity = woocommerce_quantity_input( array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => '0',
												'product_name' => $_product->get_name(),
											), $_product, false );
										}

										echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
									?>
								</td>

								<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'claue' ); ?>">
									<?php
										echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
									?>
								</td>
								<td class="product-remove">
									<?php
										if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
											echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
												'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
												esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
												esc_html__( 'Remove this item', 'claue' ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											), $cart_item_key );
										} else {
											echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
												'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_html__( 'Remove this item', 'claue' ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											), $cart_item_key );
										}
									?>
								</td>

							</tr>

							<?php
						}
					} 	
				}
				do_action( 'woocommerce_cart_contents' );
				?>
				<tr>
					<td colspan="6" class="actions">

						<?php if ( wc_coupons_enabled() ) { ?>
							<div class="coupon flex middle-xs">

								<label for="coupon_code"><?php _e( 'Coupon:', 'claue' ); ?></label> <input type="text" name="coupon_code" class="input-text br__40" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'claue' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'claue' ); ?>"><?php esc_attr_e( 'Apply coupon', 'claue' ); ?></button>

								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						<?php } ?>

						<button type="submit" class="button update-cart" name="update_cart" value="<?php esc_attr_e( 'Update Cart', 'claue' ); ?>"><?php esc_attr_e( 'Update Cart', 'claue' ); ?></button>

						<?php do_action( 'woocommerce_cart_actions' ); ?>

						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					</td>
				</tr>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table>
	<?php } ?>

	<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">

	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>


</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
