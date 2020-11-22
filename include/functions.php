<script type="text/javascript" src="include/functions.js"></script>
<?php
$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
function debug_to_console($data){
	if(true){
		$output = $data;
		if (is_array($output))
			$output = implode(',', $output);

		echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
	}
}
function try_connect(){
	include 'db_credentials.php';
	/** Create connection, and validate that it connected successfully **/
	$con = sqlsrv_connect($server, $connectionInfo);
	if ($con === false) {
		print_r(sqlsrv_errors(), true);
	}
	return $con;
}
function disconnect($con){
	sqlsrv_close($con);
}
function print_product($prodtuple){
	echo ('<br/>');
	echo ('<a href="' . get_addcart_url($prodtuple) . '">Add To Cart</a>');
	echo (" ");
	echo ($prodtuple['productName']);
	echo (" ");
	echo ($prodtuple['productPrice']);
	$picURL = $prodtuple['productImageURL'];
	if ($picURL != false) {
		echo ($picURL);
	}
}
function get_addcart_url($prodtuple){
	//id=<>name=<>&price=<>
	$url = $root . "addcart.php?id=" . $prodtuple['productId'] . "&name=" . $prodtuple['productName'] . "&price=" . $prodtuple['productPrice'];
	return $url;
}

/**
 * A function that generates formatted HTML text for table cells.
 * 
 * @param string $data String for the datacell, can be plain text or formatted html text.
 * @param string $type Optional. Type of the datacell, default to 'td', can be 'th' for headers.
 * @param array	 $attributes {
 *		Optional. An array for additional attributes.
 *		When using this parameter, pass in an array with key=>value pair.
 *		For example, if you want a cell with colspan of 3, and align to right, you pass in:
 *			array ('colspan'=>3, 'align'=>'right')
 * }
 * @return string Return the constructed string for the cell.
 */
function make_cell($data, $type = "td", $attributes = null){
	$output = '<' . $type;
	if (!empty($attributes)) {
		foreach ($attributes as $key => $value) {
			$output .= ' ' . $key . '="' . $value . '"';
		}
	}
	$output .=	'>';
	$output .= $data;
	$output .= '</' . $type . '>';
	return $output;
}

/**
 * A function that generates formatted HTML text for table rows.
 * 
 * @param array $cells {
 *		An array of table cells, as formatted html strings.
 *		Ex. array('<td>something</td>', '<td>something else</td>')
 *		Or: array(make_cell('something'), make_cell('something else'))
 * } 
 * @param array	 $attributes {
 *		Optional. An array for additional attributes.
 *		When using this parameter, pass in an array with key=>value pair.
 *		See make_cell for examples
 * }
 * @return string Return the constructed string for the row.
 */
function make_row($cells, $attributes = null){
	$output = '<tr';
	if (!empty($attributes)) {
		foreach ($attributes as $key => $value) {
			$output .= ' ' . $key . '="' . $value . '"';
		}
	}
	$output .= '>';

	foreach ($cells as $cell) {
		$output .= $cell;
	}

	$output .= '</tr>';
	return $output;
}

function make_table($rows, $attributes = null){
	$output = '<table';
	if (!empty($attributes)) {
		foreach ($attributes as $key => $value) {
			$output .= ' ' . $key . '="' . $value . '"';
		}
	}
	$output .= '>';

	foreach ($rows as $row) {
		$output .= $row;
	}

	$output .= '</table>';
	return $output;
}
function make_tableheader($cols){
	$header_attr = array("scope" => "col");
	$cells = array();
	foreach ($cols as $cheading){
		debug_to_console("processing column headings.. on: " . $cheading);
		array_push($cells,make_cell($cheading,'th',$header_attr));
	}
	echo('<table class="table table-bordered"><thead>');
	echo(make_row($cells));
	echo("</thead>");
}
?>
