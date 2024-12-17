<?php
/**
 * CLI script to insert posts with Gutenberg blocks
 *
 * @package CountryCard
 */

// namespace ZR\Blocks\Modules;

// use \ZR\Blocks\Interfaces\Module;
// use const \ZR\Blocks\MAIN_DIR;

/**
 * CLI module
 */
final class CLI implements Module {
	/**
	 * Register hooks
	 * 
	 * Registers the WP-CLI command if WP-CLI is available.
	 *
	 * @return void
	 */
	public static function register(): void {
		if ( class_exists( '\WP_CLI' ) ) {
			\WP_CLI::add_command( 'zachrener-blocks insert-posts', [ get_class(), 'handle_cli_request' ] );
		}
	}

	/**
	 * Handle CLI request to insert posts
	 * 
	 * Reads the cities data from a JSON file and inserts posts with Gutenberg blocks for each city.
	 *
	 * ## EXAMPLES
	 *
	 *     # Insert posts.
	 *     $ wp zachrener-blocks insert-posts
	 *
	 * @throws \Exception Exception thrown if getting cities data fails.
	 *
	 * @return void
	 */
	public static function handle_cli_request(): void {
		// Decode JSON data from cities file.
		/** @var null|array<array{countryCode?: string, countryName?: string, cities?: array<array{name?: string, population?: int}>}> $data */
		$data = wp_json_file_decode( MAIN_DIR . '/assets/cities.json', [ 'associative' => true ] );

		if ( null === $data ) {
			\WP_CLI::error( 'Couldn\'t parse cities.json file.' );
			return;
		}

		// Get or create the 'Cities' category.
		$category_id = get_cat_ID( __( 'Cities', 'zachrener-blocks' ) );

		if ( 0 === $category_id ) {
			$category_id = wp_insert_category( [ 'cat_name' => __( 'Cities', 'zachrener-blocks' ) ], true );

			if ( is_wp_error( $category_id ) ) {
				\WP_CLI::error(
					sprintf(
						'Couldn\'t insert "cities" category, reason: %s',
						$category_id->get_error_message()
					)
				);

				return;
			}
		}

		/** @var array{countryCode?: string, countryName?: string, cities?: array<array{name?: string, population?: int}>} $country */
		foreach ( $data as $country ) {
			if ( ! isset( $country['cities'] ) || empty( $country['countryCode'] ) || empty( $country['countryName'] ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				continue;
			}

			\WP_CLI::log(
				sprintf( 'Inserting %s\'s cities', $country['countryName'] ) // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			);

			foreach ( $country['cities'] as $city ) {
				// Determine population range for meta tags.
				if ( ! isset( $city['population'] ) || empty( $city['population'] ) ) {
					$population_range = 'unknown';
				} else {
					$population_range = $city['population'] >= 1000000 ? '1m+' : '100k+';
					$city['population'] = number_format( $city['population'] ); // Format population number.
				}

				// Prepare meta tags for the post.
				$meta_tags        = [
					'country_name'                               => $country['countryName'], // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					'country_code'                               => $country['countryCode'], // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					'population_range'                           => $population_range,
					'combined_country_code_and_population_range' => sprintf( '%s_%s', $country['countryCode'], $population_range ), // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				];

				// Prepare content for the Gutenberg blocks.
				$content = [
					'population' => sprintf(
						// Translators: %1$s city name, %2$s city population.
						esc_html__( 'Population of %1$s: %2$s', 'zachrener-blocks' ),
						isset( $city['name'] ) ? esc_html( $city['name'] ) : esc_html__( 'unknown', 'zachrener-blocks' ),
						isset( $city['population'] ) ? (string) $city['population'] : esc_html__( 'unknown', 'zachrener-blocks' )
					),
					'other_cities' => sprintf(
						__( 'See <a href="/tag/%s">other cities in this country</a>.', 'zachrener-blocks' ),
						! empty( $country['countryName'] ) ? sanitize_title( $country['countryName'] ) : __( 'unknown', 'zachrener-blocks' )
					)
				];

				// Create Gutenberg blocks for the post content.
				$blocks = [
					[
						'blockName'   => 'core/paragraph',
						'attrs'       => [], 
						'innerBlocks' => [],
						'innerHTML'   => wpautop( esc_html( $content['population'] ) ),
						'innerContent'=> [ wpautop( esc_html( $content['population'] ) ) ],
					],
					[
						'blockName'   => 'core/paragraph',
						'attrs'       => [],
						'innerBlocks' => [],
						'innerHTML'	  => wpautop( wp_kses_post( $content['other_cities'] ) ),
						'innerContent'=> [ wpautop( wp_kses_post( $content['other_cities'] ) ) ],
					],
				];
				
				// Serialize blocks to generate the post content.
				$post_content = '';
				foreach ( $blocks as $block ) {
					$post_content .= serialize_block( $block );
				}

				// Insert the post into the database.
				wp_insert_post(
					[
						'post_title'    => !empty( $city['name'] ) ? $city['name'] : esc_html__( 'Unnamed City', 'zachrener-blocks' ),
						'post_status'   => 'publish',
						'post_excerpt'  => $content['population'],
						'post_content'  => $post_content,
						'post_category' => [ $category_id ],
						'meta_input' => $meta_tags,
						'tags_input' => [
							wp_strip_all_tags( $country['countryName'] ), // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
						],
					]
				);
			}
		}
	}
}
