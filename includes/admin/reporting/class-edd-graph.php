<?php
/**
 * Graphs
 *
 * This class handles building pretty report graphs
 *
 * @package     EDD
 * @subpackage  Admin/Reports
 * @copyright   Copyright (c) 2012, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.9
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * EDD_Graph Class
 *
 * @since 1.9
 */
class EDD_Graph {

	/*

	Should look something like this:

	$data = array(

		// X axis
		array(
			array( 1, 5 ),
			array( 3, 8 ),
			array( 10, 2 )
		),

		// Y axis
		array(
			array( 1, 7 ),
			array( 4, 5 ),
			array( 12, 8 )
		)
	);

	$graph = new EDD_Graph( $data );

	// Include optional methods for setting colors, sizes, etc

	$graph->display();

	*/

	private $data;

	private $id;

	private $options = array();

	/**
	 * Get things started
	 *
	 * @since 1.9
	 */
	public function __construct( $_data ) {
		
		$this->data = $_data; 
		
		// Generate unique ID
		$this->id   = md5( rand() );

		// Setup default options;
		$this->options = array(
			'ymode'         => null,
			'xmode'         => null,
			'time_format'   => '%d/%b',
			'ticksize_unit' => 'day',
			'ticksize_num'  => 1
		);

	}

	public function set( $key, $value ) {
		$this->options[ $key ] = $value;
	}

	public function display() {
?>
		<script type="text/javascript">
			jQuery( document ).ready( function($) {
				$.plot(
					$("#edd-graph-<?php echo $this->id; ?>"),
					[
						<?php foreach( $this->data as $label => $data ) : ?>
						{ 
							// data format is: [ point on x, value on y ]
							label: "<?php echo esc_attr( $label ); ?>",
							//data: [[1, 2], [4, 5], [7, 8], [17, 0]],
							data: [<?php foreach( $data as $point ) { echo '[' . implode( ',', $point ) . '],'; } ?>],
							points: {
								show: true,
							},
							lines: {
								show: true
							}
						},
						<?php endforeach; ?>
					],
					{
						// Options
						xaxis: {
							mode: "<?php echo $this->options['xmode']; ?>",
							timeFormat: "<?php echo $this->options['xmode'] == 'time' ? $this->options['time_format'] : ''; ?>",
						},
						yaxis: {
							mode: "<?php echo $this->options['ymode']; ?>",
							timeFormat: "<?php echo $this->options['ymode'] == 'time' ? $this->options['time_format'] : ''; ?>",
						}
					}

				);
			});
		</script>
		<div id="edd-graph-<?php echo $this->id; ?>" style="height: 300px;"></div>
<?php
	}
	

}

// Just for simple testing

function edd_test_graph_class() {

	$data = array(

		// Line one
		'Foo' => array(
			array( 1386048624, 3 ),
			array( 1386307825, 5 ),
			array( 1386394224, 8 ),
			array( 1386480624, 2 )
		),

		// Line 2
		'Bar' => array(
			array( 1386048624, 1 ),
			array( 1386307825, 2 ),
			array( 1386394224, 10 ),
			array( 1386480624, 3 )
		),

		// Line 3
		'Puppy' => array(
			array( 1386048624, 10 ),
			array( 1386307825, 12 ),
			array( 1386394224, 2 ),
			array( 1386480624, 4 )
		)
	);

	$graph = new EDD_Graph( $data );
	$graph->set( 'xmode', 'time' );
	$graph->display();
}
add_action( 'edd_reports_view_earnings', 'edd_test_graph_class', -1 );