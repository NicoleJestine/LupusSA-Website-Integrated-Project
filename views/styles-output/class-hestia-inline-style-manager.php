<?php
/**
 * Enqueue fonts and run functions that are needed for inline style.
 *
 * @package Hestia
 */

/**
 * Class Hestia_Inline_Style_Manager
 */
class Hestia_Inline_Style_Manager extends Hestia_Abstract_Main {
	/**
	 * Add all the hooks necessary.
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_google_font' ) );
		add_action( 'after_setup_theme', array( $this, 'sync_new_fs' ) );
	}

	/**
	 * Register the fonts that are selected in customizer.
	 */
	public function register_google_font() {

		/**
		 * Headings font family.
		 */
		$hestia_headings_font = get_theme_mod( 'hestia_headings_font', apply_filters( 'hestia_headings_default', false ) );
		if ( ! empty( $hestia_headings_font ) ) {
			$this->enqueue_google_font( $hestia_headings_font );
		}

		/**
		 * Body font family.
		 */
		$hestia_body_font = get_theme_mod( 'hestia_body_font', apply_filters( 'hestia_body_font_default', false ) );
		if ( ! empty( $hestia_body_font ) ) {
			$this->enqueue_google_font( $hestia_body_font );
		}
	}

	/**
	 * Enqueues a Google Font
	 *
	 * @since 1.1.38
	 *
	 * @param string $font font string.
	 */
	private function enqueue_google_font( $font ) {

		// Get list of all Google Fonts
		$google_fonts = $this->get_google_fonts();

		// Make sure font is in our list of fonts
		if ( ! $google_fonts || ! in_array( $font, $google_fonts ) ) {
			return;
		}

		// Sanitize handle
		$handle = trim( $font );
		$handle = strtolower( $handle );
		$handle = str_replace( ' ', '-', $handle );

		// Sanitize font name
		$font = trim( $font );

		$base_url = '//fonts.googleapis.com/css';

		// Apply the chosen subset from customizer
		$subsets     = '';
		$get_subsets = get_theme_mod( 'hestia_font_subsets', array( 'latin' ) );
		if ( ! empty( $get_subsets ) ) {
			$font_subsets = array();
			foreach ( $get_subsets as $get_subset ) {
				$font_subsets[] = $get_subset;
			}
			$subsets .= implode( ',', $font_subsets );
		}

		// Weights
		$weights = apply_filters( 'hestia_font_weights', array( '300', '400', '500', '700' ) );

		// Add weights to URL
		if ( ! empty( $weights ) ) {
			$font .= ':' . implode( ',', $weights );
		}

		$query_args = array(
			'family' => urlencode( $font ),
		);
		if ( ! empty( $subsets ) ) {
			$query_args['subset'] = urlencode( $subsets );
		}
		$url = add_query_arg( $query_args, $base_url );

		// Enqueue style
		wp_enqueue_style( 'hestia-google-font-' . $handle, $url, array(), false );
	}

	/**
	 * List of All Google fonts
	 *
	 * @since 1.1.38
	 */
	private function get_google_fonts() {
		return apply_filters( 'hestia_google_fonts_array', array( 'ABeeZee', 'Abel', 'Abril Fatface', 'Aclonica', 'Acme', 'Actor', 'Adamina', 'Advent Pro', 'Aguafina Script', 'Akronim', 'Aladin', 'Aldrich', 'Alef', 'Alegreya', 'Alegreya SC', 'Alegreya Sans', 'Alegreya Sans SC', 'Alex Brush', 'Alfa Slab One', 'Alice', 'Alike', 'Alike Angular', 'Allan', 'Allerta', 'Allerta Stencil', 'Allura', 'Almendra', 'Almendra Display', 'Almendra SC', 'Amarante', 'Amaranth', 'Amatic SC', 'Amatica SC', 'Amethysta', 'Amiko', 'Amiri', 'Amita', 'Anaheim', 'Andada', 'Andika', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Antic', 'Antic Didone', 'Antic Slab', 'Anton', 'Arapey', 'Arbutus', 'Arbutus Slab', 'Architects Daughter', 'Archivo Black', 'Archivo Narrow', 'Aref Ruqaa', 'Arima Madurai', 'Arimo', 'Arizonia', 'Armata', 'Artifika', 'Arvo', 'Arya', 'Asap', 'Asar', 'Asset', 'Assistant', 'Astloch', 'Asul', 'Athiti', 'Atma', 'Atomic Age', 'Aubrey', 'Audiowide', 'Autour One', 'Average', 'Average Sans', 'Averia Gruesa Libre', 'Averia Libre', 'Averia Sans Libre', 'Averia Serif Libre', 'Bad Script', 'Baloo', 'Baloo Bhai', 'Baloo Da', 'Baloo Thambi', 'Balthazar', 'Bangers', 'Basic', 'Battambang', 'Baumans', 'Bayon', 'Belgrano', 'Belleza', 'BenchNine', 'Bentham', 'Berkshire Swash', 'Bevan', 'Bigelow Rules', 'Bigshot One', 'Bilbo', 'Bilbo Swash Caps', 'BioRhyme', 'BioRhyme Expanded', 'Biryani', 'Bitter', 'Black Ops One', 'Bokor', 'Bonbon', 'Boogaloo', 'Bowlby One', 'Bowlby One SC', 'Brawler', 'Bree Serif', 'Bubblegum Sans', 'Bubbler One', 'Buda', 'Buenard', 'Bungee', 'Bungee Hairline', 'Bungee Inline', 'Bungee Outline', 'Bungee Shade', 'Butcherman', 'Butterfly Kids', 'Cabin', 'Cabin Condensed', 'Cabin Sketch', 'Caesar Dressing', 'Cagliostro', 'Cairo', 'Calligraffitti', 'Cambay', 'Cambo', 'Candal', 'Cantarell', 'Cantata One', 'Cantora One', 'Capriola', 'Cardo', 'Carme', 'Carrois Gothic', 'Carrois Gothic SC', 'Carter One', 'Catamaran', 'Caudex', 'Caveat', 'Caveat Brush', 'Cedarville Cursive', 'Ceviche One', 'Changa', 'Changa One', 'Chango', 'Chathura', 'Chau Philomene One', 'Chela One', 'Chelsea Market', 'Chenla', 'Cherry Cream Soda', 'Cherry Swash', 'Chewy', 'Chicle', 'Chivo', 'Chonburi', 'Cinzel', 'Cinzel Decorative', 'Clicker Script', 'Coda', 'Coda Caption', 'Codystar', 'Coiny', 'Combo', 'Comfortaa', 'Coming Soon', 'Concert One', 'Condiment', 'Content', 'Contrail One', 'Convergence', 'Cookie', 'Copse', 'Corben', 'Cormorant', 'Cormorant Garamond', 'Cormorant Infant', 'Cormorant SC', 'Cormorant Unicase', 'Cormorant Upright', 'Courgette', 'Cousine', 'Coustard', 'Covered By Your Grace', 'Crafty Girls', 'Creepster', 'Crete Round', 'Crimson Text', 'Croissant One', 'Crushed', 'Cuprum', 'Cutive', 'Cutive Mono', 'Damion', 'Dancing Script', 'Dangrek', 'David Libre', 'Dawning of a New Day', 'Days One', 'Dekko', 'Delius', 'Delius Swash Caps', 'Delius Unicase', 'Della Respira', 'Denk One', 'Devonshire', 'Dhurjati', 'Didact Gothic', 'Diplomata', 'Diplomata SC', 'Domine', 'Donegal One', 'Doppio One', 'Dorsa', 'Dosis', 'Dr Sugiyama', 'Droid Sans', 'Droid Sans Mono', 'Droid Serif', 'Duru Sans', 'Dynalight', 'EB Garamond', 'Eagle Lake', 'Eater', 'Economica', 'Eczar', 'Ek Mukta', 'El Messiri', 'Electrolize', 'Elsie', 'Elsie Swash Caps', 'Emblema One', 'Emilys Candy', 'Engagement', 'Englebert', 'Enriqueta', 'Erica One', 'Esteban', 'Euphoria Script', 'Ewert', 'Exo', 'Exo 2', 'Expletus Sans', 'Fanwood Text', 'Farsan', 'Fascinate', 'Fascinate Inline', 'Faster One', 'Fasthand', 'Fauna One', 'Federant', 'Federo', 'Felipa', 'Fenix', 'Finger Paint', 'Fira Mono', 'Fira Sans', 'Fjalla One', 'Fjord One', 'Flamenco', 'Flavors', 'Fondamento', 'Fontdiner Swanky', 'Forum', 'Francois One', 'Frank Ruhl Libre', 'Freckle Face', 'Fredericka the Great', 'Fredoka One', 'Freehand', 'Fresca', 'Frijole', 'Fruktur', 'Fugaz One', 'GFS Didot', 'GFS Neohellenic', 'Gabriela', 'Gafata', 'Galada', 'Galdeano', 'Galindo', 'Gentium Basic', 'Gentium Book Basic', 'Geo', 'Geostar', 'Geostar Fill', 'Germania One', 'Gidugu', 'Gilda Display', 'Give You Glory', 'Glass Antiqua', 'Glegoo', 'Gloria Hallelujah', 'Goblin One', 'Gochi Hand', 'Gorditas', 'Goudy Bookletter 1911', 'Graduate', 'Grand Hotel', 'Gravitas One', 'Great Vibes', 'Griffy', 'Gruppo', 'Gudea', 'Gurajada', 'Habibi', 'Halant', 'Hammersmith One', 'Hanalei', 'Hanalei Fill', 'Handlee', 'Hanuman', 'Happy Monkey', 'Harmattan', 'Headland One', 'Heebo', 'Henny Penny', 'Herr Von Muellerhoff', 'Hind', 'Hind Guntur', 'Hind Madurai', 'Hind Siliguri', 'Hind Vadodara', 'Holtwood One SC', 'Homemade Apple', 'Homenaje', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Iceberg', 'Iceland', 'Imprima', 'Inconsolata', 'Inder', 'Indie Flower', 'Inika', 'Inknut Antiqua', 'Irish Grover', 'Istok Web', 'Italiana', 'Italianno', 'Itim', 'Jacques Francois', 'Jacques Francois Shadow', 'Jaldi', 'Jim Nightshade', 'Jockey One', 'Jolly Lodger', 'Jomhuria', 'Josefin Sans', 'Josefin Slab', 'Joti One', 'Judson', 'Julee', 'Julius Sans One', 'Junge', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'Kadwa', 'Kalam', 'Kameron', 'Kanit', 'Kantumruy', 'Karla', 'Karma', 'Katibeh', 'Kaushan Script', 'Kavivanar', 'Kavoon', 'Kdam Thmor', 'Keania One', 'Kelly Slab', 'Kenia', 'Khand', 'Khmer', 'Khula', 'Kite One', 'Knewave', 'Kotta One', 'Koulen', 'Kranky', 'Kreon', 'Kristi', 'Krona One', 'Kumar One', 'Kumar One Outline', 'Kurale', 'La Belle Aurore', 'Laila', 'Lakki Reddy', 'Lalezar', 'Lancelot', 'Lateef', 'Lato', 'League Script', 'Leckerli One', 'Ledger', 'Lekton', 'Lemon', 'Lemonada', 'Libre Baskerville', 'Libre Franklin', 'Life Savers', 'Lilita One', 'Lily Script One', 'Limelight', 'Linden Hill', 'Lobster', 'Lobster Two', 'Londrina Outline', 'Londrina Shadow', 'Londrina Sketch', 'Londrina Solid', 'Lora', 'Love Ya Like A Sister', 'Loved by the King', 'Lovers Quarrel', 'Luckiest Guy', 'Lusitana', 'Lustria', 'Macondo', 'Macondo Swash Caps', 'Mada', 'Magra', 'Maiden Orange', 'Maitree', 'Mako', 'Mallanna', 'Mandali', 'Marcellus', 'Marcellus SC', 'Marck Script', 'Margarine', 'Marko One', 'Marmelad', 'Martel', 'Martel Sans', 'Marvel', 'Mate', 'Mate SC', 'Maven Pro', 'McLaren', 'Meddon', 'MedievalSharp', 'Medula One', 'Meera Inimai', 'Megrim', 'Meie Script', 'Merienda', 'Merienda One', 'Merriweather', 'Merriweather Sans', 'Metal', 'Metal Mania', 'Metamorphous', 'Metrophobic', 'Michroma', 'Milonga', 'Miltonian', 'Miltonian Tattoo', 'Miniver', 'Miriam Libre', 'Mirza', 'Miss Fajardose', 'Mitr', 'Modak', 'Modern Antiqua', 'Mogra', 'Molengo', 'Molle', 'Monda', 'Monofett', 'Monoton', 'Monsieur La Doulaise', 'Montaga', 'Montez', 'Montserrat', 'Montserrat Alternates', 'Montserrat Subrayada', 'Moul', 'Moulpali', 'Mountains of Christmas', 'Mouse Memoirs', 'Mr Bedfort', 'Mr Dafoe', 'Mr De Haviland', 'Mrs Saint Delafield', 'Mrs Sheppards', 'Mukta Vaani', 'Muli', 'Mystery Quest', 'NTR', 'Neucha', 'Neuton', 'New Rocker', 'News Cycle', 'Niconne', 'Nixie One', 'Nobile', 'Nokora', 'Norican', 'Nosifer', 'Nothing You Could Do', 'Noticia Text', 'Noto Sans', 'Noto Serif', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'Numans', 'Nunito', 'Odor Mean Chey', 'Offside', 'Old Standard TT', 'Oldenburg', 'Oleo Script', 'Oleo Script Swash Caps', 'Open Sans', 'Open Sans Condensed', 'Oranienbaum', 'Orbitron', 'Oregano', 'Orienta', 'Original Surfer', 'Oswald', 'Over the Rainbow', 'Overlock', 'Overlock SC', 'Ovo', 'Oxygen', 'Oxygen Mono', 'PT Mono', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Pacifico', 'Palanquin', 'Palanquin Dark', 'Paprika', 'Parisienne', 'Passero One', 'Passion One', 'Pathway Gothic One', 'Patrick Hand', 'Patrick Hand SC', 'Pattaya', 'Patua One', 'Pavanam', 'Paytone One', 'Peddana', 'Peralta', 'Permanent Marker', 'Petit Formal Script', 'Petrona', 'Philosopher', 'Piedra', 'Pinyon Script', 'Pirata One', 'Plaster', 'Play', 'Playball', 'Playfair Display', 'Playfair Display SC', 'Podkova', 'Poiret One', 'Poller One', 'Poly', 'Pompiere', 'Pontano Sans', 'Poppins', 'Port Lligat Sans', 'Port Lligat Slab', 'Pragati Narrow', 'Prata', 'Preahvihear', 'Press Start 2P', 'Pridi', 'Princess Sofia', 'Prociono', 'Prompt', 'Prosto One', 'Proza Libre', 'Puritan', 'Purple Purse', 'Quando', 'Quantico', 'Quattrocento', 'Quattrocento Sans', 'Questrial', 'Quicksand', 'Quintessential', 'Qwigley', 'Racing Sans One', 'Radley', 'Rajdhani', 'Rakkas', 'Raleway', 'Raleway Dots', 'Ramabhadra', 'Ramaraja', 'Rambla', 'Rammetto One', 'Ranchers', 'Rancho', 'Ranga', 'Rasa', 'Rationale', 'Ravi Prakash', 'Redressed', 'Reem Kufi', 'Reenie Beanie', 'Revalia', 'Rhodium Libre', 'Ribeye', 'Ribeye Marrow', 'Righteous', 'Risque', 'Roboto', 'Roboto Condensed', 'Roboto Mono', 'Roboto Slab', 'Rochester', 'Rock Salt', 'Rokkitt', 'Romanesco', 'Ropa Sans', 'Rosario', 'Rosarivo', 'Rouge Script', 'Rozha One', 'Rubik', 'Rubik Mono One', 'Rubik One', 'Ruda', 'Rufina', 'Ruge Boogie', 'Ruluko', 'Rum Raisin', 'Ruslan Display', 'Russo One', 'Ruthie', 'Rye', 'Sacramento', 'Sahitya', 'Sail', 'Salsa', 'Sanchez', 'Sancreek', 'Sansita One', 'Sarala', 'Sarina', 'Sarpanch', 'Satisfy', 'Scada', 'Scheherazade', 'Schoolbell', 'Scope One', 'Seaweed Script', 'Secular One', 'Sevillana', 'Seymour One', 'Shadows Into Light', 'Shadows Into Light Two', 'Shanti', 'Share', 'Share Tech', 'Share Tech Mono', 'Shojumaru', 'Short Stack', 'Shrikhand', 'Siemreap', 'Sigmar One', 'Signika', 'Signika Negative', 'Simonetta', 'Sintony', 'Sirin Stencil', 'Six Caps', 'Skranji', 'Slabo 13px', 'Slabo 27px', 'Slackey', 'Smokum', 'Smythe', 'Sniglet', 'Snippet', 'Snowburst One', 'Sofadi One', 'Sofia', 'Sonsie One', 'Sorts Mill Goudy', 'Source Code Pro', 'Source Sans Pro', 'Source Serif Pro', 'Space Mono', 'Special Elite', 'Spicy Rice', 'Spinnaker', 'Spirax', 'Squada One', 'Sree Krushnadevaraya', 'Sriracha', 'Stalemate', 'Stalinist One', 'Stardos Stencil', 'Stint Ultra Condensed', 'Stint Ultra Expanded', 'Stoke', 'Strait', 'Sue Ellen Francisco', 'Suez One', 'Sumana', 'Sunshiney', 'Supermercado One', 'Sura', 'Suranna', 'Suravaram', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate', 'Tangerine', 'Taprom', 'Tauri', 'Taviraj', 'Teko', 'Telex', 'Tenali Ramakrishna', 'Tenor Sans', 'Text Me One', 'The Girl Next Door', 'Tienne', 'Tillana', 'Timmana', 'Tinos', 'Titan One', 'Titillium Web', 'Trade Winds', 'Trirong', 'Trocchi', 'Trochut', 'Trykker', 'Tulpen One', 'Ubuntu', 'Ubuntu Condensed', 'Ubuntu Mono', 'Ultra', 'Uncial Antiqua', 'Underdog', 'Unica One', 'UnifrakturCook', 'UnifrakturMaguntia', 'Unkempt', 'Unlock', 'Unna', 'VT323', 'Vampiro One', 'Varela', 'Varela Round', 'Vast Shadow', 'Vesper Libre', 'Vibur', 'Vidaloka', 'Viga', 'Voces', 'Volkhov', 'Vollkorn', 'Voltaire', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Warnes', 'Wellfleet', 'Wendy One', 'Wire One', 'Work Sans', 'Yanone Kaffeesatz', 'Yantramanav', 'Yatra One', 'Yellowtail', 'Yeseva One', 'Yesteryear', 'Yrsa', 'Zeyada' ) );
	}

	/**
	 * This function checks if the value stored in the customizer control named '$control_name' is a json object.
	 * If the value is json it means that the customizer range control stores a value for every device ( mobile, tablet,
	 * desktop). In this case, for each of those devices it calls '$function_name' that with the following parameters:
	 * the device and the value for the control on that device.
	 * '$function_name' returns css code that will be added to inline style.
	 * If the value is not json then it's int and the '$function_name' function will be called just once for all three
	 * devices.
	 *
	 * @param string $control_name Control name.
	 * @param array  $function_name Function to be called.
	 *
	 * @since 1.1.38
	 * @return string
	 */
	protected function get_inline_style( $control_name, $function_name ) {
		$control_value = get_theme_mod( $control_name );
		if ( empty( $control_value ) ) {
			return '';
		}

		$custom_css = '';
		if ( hestia_is_json( $control_value ) ) {
			$control_value = json_decode( $control_value, true );
			if ( ! empty( $control_value ) ) {

				foreach ( $control_value as $key => $value ) {
					$custom_css .= call_user_func( $function_name, $value, $key );
				}
			}
		} else {
			$custom_css .= call_user_func( $function_name, $control_value );
		}

		return $custom_css;
	}


	/**
	 * Function to import font sizes from old controls to new ones.
	 *
	 * @since 1.1.58
	 */
	public function sync_new_fs() {
		$execute = get_option( 'hestia_sync_font_sizes' );
		if ( $execute !== false ) {
			return;
		}
		$headings_fs_old = get_theme_mod( 'hestia_headings_font_size' );
		$body_fs_old     = get_theme_mod( 'hestia_body_font_size' );
		if ( empty( $body_fs_old ) && empty( $headings_fs_old ) ) {
			return;
		}

		if ( ! empty( $headings_fs_old ) ) {
			$decoded = $this->calculate_fs_value( $headings_fs_old, 37 );
			set_theme_mod( 'hestia_section_primary_headings_fs', $decoded );
			set_theme_mod( 'hestia_section_secondary_headings_fs', $decoded );
			set_theme_mod( 'hestia_header_titles_fs', $decoded );
			set_theme_mod( 'hestia_post_page_headings_fs', $decoded );
		}

		if ( ! empty( $body_fs_old ) ) {
			$decoded = $this->calculate_fs_value( $body_fs_old, 12 );
			set_theme_mod( 'hestia_section_content_fs', $decoded );
			set_theme_mod( 'hestia_post_page_content_fs', $decoded );
		}
		update_option( 'hestia_sync_font_sizes', true );
	}

	/**
	 * Calculate new value for the new font size control based on the old control.
	 *
	 * @param string $old_value Value from the old control.
	 * @param int    $decrease_rate Value to substract from the old value.
	 *
	 * @return string
	 */
	private function calculate_fs_value( $old_value, $decrease_rate ) {
		$decoded = json_decode( $old_value );
		if ( ! hestia_is_json( $old_value ) ) {
			$tmp_array = array(
				'desktop' => floor( $decoded - $decrease_rate ) > 25 ? 25 : ( floor( $decoded - $decrease_rate ) < - 25 ? - 25 : floor( $decoded - $decrease_rate ) ),
				'mobile'  => 0,
				'tablet'  => 0,
			);
			$decoded   = json_encode( $tmp_array );
		} else {
			$decoded->desktop = floor( $decoded->desktop - $decrease_rate ) > 25 ? 25 : ( floor( $decoded->desktop - $decrease_rate ) < - 25 ? - 25 : floor( $decoded->desktop - $decrease_rate ) );
			$decoded->tablet  = floor( $decoded->tablet - $decrease_rate ) > 25 ? 25 : ( floor( $decoded->tablet - $decrease_rate ) < - 25 ? - 25 : floor( $decoded->tablet - $decrease_rate ) );
			$decoded->mobile  = floor( $decoded->mobile - $decrease_rate ) > 25 ? 25 : ( floor( $decoded->mobile - $decrease_rate ) < - 25 ? - 25 : floor( $decoded->mobile - $decrease_rate ) );
			$decoded          = json_encode( $decoded );
		}

		return $decoded;
	}

	/**
	 * This function is called by each function that adds css if the control have media queries enabled.
	 *
	 * @param string $dimension Query dimension.
	 * @param string $custom_css Css.
	 *
	 * @return string
	 */
	public function add_media_query( $dimension, $custom_css ) {
		switch ( $dimension ) {
			case 'desktop':
				$custom_css = '@media (min-width: 769px){' . $custom_css . '}';
				break;
				break;
			case 'tablet':
				$custom_css = '@media (max-width: 768px){' . $custom_css . '}';
				break;
			case 'mobile':
				$custom_css = '@media (max-width: 480px){' . $custom_css . '}';
				break;
		}

		return $custom_css;
	}


}
