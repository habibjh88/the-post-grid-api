<?php
/**
 * Meta Controller class.
 *
 * @package RT_TPG_API
 */

namespace RT\ThePostGridAPI\Controllers\Admin;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
use RT\ThePostGridAPI\Controllers\Admin\Meta\TaxonomyMeta;
/**
 * Meta Controller class.
 */
class MetaController {
	/**
	 * Class constructor
	 */
	public function __construct() {
		new TaxonomyMeta();
	}


}
