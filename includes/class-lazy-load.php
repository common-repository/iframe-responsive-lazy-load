<?php

namespace IframeResponsiveLazyLoad;

/**
* Load Autoload
*
* @since 1.0.0
*/
require_once iframeRLL_dir_path . '/assests/vendor/autoload.php';

use Masterminds\HTML5;

/**
* Class for adding lazy loading to iframe.
*
* @since 1.0.0
*/
class iframeRLL_Plugin {
	
	/**
	* Hint if the plugin is disabled for this post.
	*
	* @since 1.0.0
	*/
	private $disabled_for_current_post = null;

	/**
	* Inizialize helper
	*
	* @since 1.0.0
	*/
	public function init() {
		add_action( 'init', array( $this, 'init_content_processing' ) );
	}

	/**
	* initialize during content processing.
	*
	* @since 1.0.0
	*/
	public function init_content_processing() {

		add_action( 'template_redirect', array( $this, 'iframe_complete_process_markup' ) );

		// Filter markup of the_content() calls to modify media markup for lazy loading.
		add_filter( 'the_content', array( $this, 'iframe_filter_markup' ), 10001 );

		// Filter markup of Text widget to modify media markup for lazy loading.
		add_filter( 'widget_text', array( $this, 'iframe_filter_markup' ) );
	}

	/**
	* Run output buffering, to process the complete markup.
	*
	* @since 1.0.0
	*/
	public function iframe_complete_process_markup() {
		// If this is no content we should process, exit as early as possible.
		if ( ! $this->iframe_is_post_to_process() ) {
			return;
		}
		ob_start( array( $this, 'iframe_filter_markup' ) );
	}

	/**
	* Modifies elements to automatically enable lazy loading.
	*
	* @since 1.0.0
	*/
	public function iframe_filter_markup( $content ) {
		// If this is no content we should process, exit as early as possible.
		if ( ! $this->iframe_is_post_to_process() ) {
			return $content;
		}

		// Check if we have no content.
		if ( empty( $content ) ) {
			return $content;
		}

		// Check if content contains caption shortcode.
		if ( has_shortcode( $content, 'caption' ) ) {
			return $content;
		}

		// Disable libxml errors.
		libxml_use_internal_errors( true );

		// Create new HTML5 object.
		$html5 = new HTML5( array(
            'disable_html_ns' => true,
        ) );

		// Preserve html entities, script tags and conditional IE comments.
		$content = preg_replace( '/&([a-zA-Z]*);/', 'lazy-loading-responsive-images-entity1-$1-end', $content );
		$content = preg_replace( '/&#([0-9]*);/', 'lazy-loading-responsive-images-entity2-$1-end', $content );
		$content = preg_replace( '/<!--\[([\w ]*)\]>/', '<!--[$1]>-->', $content );
		$content = str_replace( '<![endif]-->', '<!--<![endif]-->', $content );
		$content = str_replace( '<script>', '<!--<script>', $content );
		$content = str_replace( '<script ', '<!--<script ', $content );
		$content = str_replace( '</script>', '</script>-->', $content );

		// Load the HTML.
		$dom = $html5->loadHTML( $content );

		$xpath = new \DOMXPath( $dom );

		// Get all nodes except the ones that live inside a noscript element.
		$nodes = $xpath->query( "//*[not(ancestor-or-self::noscript)][not(ancestor-or-self::*[contains(@class, 'disable-lazyload') or contains(@class, 'skip-lazy') or @data-skip-lazy])]" );

		$is_modified = false;

		foreach ( $nodes as $node ) {
			// Check if it is an element that should not be lazy loaded.
			$node_classes = explode( ' ', $node->getAttribute( 'class' ) );

			if ( $node->hasAttribute( 'data-no-lazyload' ) || in_array( 'lazyload', $node_classes, true ) ) {
				continue;
			}

			$field = get_option( 'iframeRLL_fields' );
			if ( !empty($field['lazy_load']) && 'iframe' === $node->tagName ) {
				$dom = $this->modify_iframe_markup( $node, $dom );
				$is_modified = true;
			}

		}

		if ( true === $is_modified ) {
				// If someone directly passed markup to the plugin, no doctype will be present. So we need to check for a parse error first.
				$errors = $html5->getErrors();
				$no_doctype = false;
				if ( is_array( $errors ) && ! empty( $errors ) ) {
					foreach ( $errors as $error ) {
						if ( strpos( $error, 'No DOCTYPE specified.' ) !== false ) {
							$no_doctype = true;
							$content = $this->save_html( $dom, $html5 );
							break;
						}
					}
				}
				if ( $no_doctype === false ) {
					$content = $html5->saveHTML( $dom );
				}
		}

		// Restore the entities and script tags.
		if ( strpos( $content, 'lazy-loading-responsive-images-entity') !== false || strpos( $content, '<!--<script' ) !== false ) {
			$content = preg_replace('/lazy-loading-responsive-images-entity1-(.*?)-end/', '&$1;', $content );
			$content = preg_replace('/lazy-loading-responsive-images-entity2-(.*?)-end/', '&#$1;', $content );
			$content = preg_replace( '/<!--\[([\w ]*)\]>-->/', '<!--[$1]>', $content );
			$content = str_replace( '<!--<![endif]-->', '<![endif]-->', $content );
			$content = str_replace( '<!--<script>', '<script>', $content );
			$content = str_replace( '<!--<script ', '<script ', $content );
			$content = str_replace( '</script>-->', '</script>', $content );
		}

		return $content;
	}

	/**
	* Modifies iframe markup to enable lazy loading.
	*
	* @since 1.0.0
	*/
	public function modify_iframe_markup( $iframe, $dom ) {
		// Add noscript element.
		$dom = $this->add_noscript_element( $dom, $iframe );

		// Check if the iframe has a src attribute.
		if ( $iframe->hasAttribute( 'src' ) ) {
			// Get src attribute.
			$src = $iframe->getAttribute( 'src' );

			// Set data-src value.
			$iframe->setAttribute( 'data-src', $src );
		} else {
			return $dom;
		}

		$iframe->setAttribute( 'plugin', iframeRLL_plugin_name );

		// Get the classes.
		$classes = $iframe->getAttribute( 'class' );

		// Add lazyload class.
		$classes .= ' lazyload';

		// Set the class string.
		$iframe->setAttribute( 'class', $classes );

		// Remove the src attribute.
		$iframe->removeAttribute( 'src' );

		return $dom;
	}

	/**
	* Adds noscript element before DOM node.
	*
	* @since 1.0.0
	*/
	public function add_noscript_element( $dom, $elem ) {
		// Create noscript element and add it before the element that gets lazy loading.
		$noscript = $dom->createElement( 'noscript' );
		$noscript_node = $elem->parentNode->insertBefore( $noscript, $elem );

		// Add a copy of the media element to the noscript.
		$noscript_node->appendChild( $elem->cloneNode( true ) );

		return $dom;
	}
	
	/**
	* Checks if this is a request at the backend.
	*
	* @since 1.0.0
	*/
	public function is_admin_request() {
		// Get current URL. From wp_admin_canonical_url().
		$current_url = set_url_scheme(
			sprintf(
				'http://%s%s',
				$_SERVER['HTTP_HOST'],
				$_SERVER['REQUEST_URI']
			)
		);

		/*
		 * Get admin URL and referrer.
		 *
		 */
		$admin_url = strtolower( admin_url() );
		$referrer  = strtolower( wp_get_referer() );

		// Check if this is a admin request. If true, it
		// could also be a AJAX request.
		if ( 0 === strpos( $current_url, $admin_url ) ) {
			// Check if the user comes from a admin page.
			if ( 0 === strpos( $referrer, $admin_url ) ) {
				return true;
			} else {
				// Check for AJAX requests.
				if ( function_exists( 'wp_doing_ajax' ) ) {
					return ! wp_doing_ajax();
				} else {
					return ! ( defined( 'DOING_AJAX' ) && DOING_AJAX );
				}
			}
		} else {
			if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
				return false;
			}
			return ( isset( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'] );
		}
	}

	/**
	* Checks if we are on an AMP page generated from the Automattic plugin.
	*
	* @since 1.0.0
	*/
	public function is_amp_page() {
		// Check if Automattic’s AMP plugin is active and we are on an AMP endpoint.
		if ( function_exists( 'is_amp_endpoint' ) && true === is_amp_endpoint() ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Check if the displayed content is something that the plugin should process.
	*
	* @since 1.0.0
	*/
	public function iframe_is_post_to_process() {

		// Check if we are on a feed page.
		if ( is_feed() ) {
			return false;
		}

		// Check if this is a request in the backend.
		if ( $this->is_admin_request() ) {
			return false;
		}

		// Check for AMP page.
		if ( $this->is_amp_page() ) {
			return false;
		}

		return true;
	}
	
	/**
	* Enhanced variation of \DOMDocument->saveHTML().
	*
	* @since 1.0.0
	*/
	public function save_html( \DOMDocument $dom, $html5 ) {
		$xpath      = new \DOMXPath( $dom );
		$first_item = $xpath->query( '/' )->item( 0 );

		return preg_replace(
			array(
				'/^\<\!DOCTYPE html>.*?<html>/si',
				'/<\/html>[\n\r]?$/si',
			),
			'',
			$html5->saveHTML( $first_item )
		);
	}
	
}

/**
* Load Plugin
*
* @since 1.0.0
*/
$iframe_LL = new iframeRLL_Plugin();
$iframe_LL->init();