<?php
$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
$debugging = false;
function debug_to_console($data){
    global $debugging;
	if($debugging){
		$output = $data;
		if (is_array($output)) {
			//todo: ?make function recursive (check if elements are arrays)?
			$output = implode(',', $output);
		}
		echo("<script>console.log(\"Debug Objects: $output \");</script>");
	}
}
function oops(){
	echo("<h1><br/><br/><br/><br/>Ooops! Something went wrong.<br/>Try again, or contact contact our support staff.</h1>");
}
function try_connect(){
	include 'db_credentials.php';
	/** Create connection, and validate that it connected successfully **/
    /** @var $connectionInfo - from db_credentials *//** @var $server - from db_credentials*/
    $con = sqlsrv_connect($server, $connectionInfo);
	if ($con === false) {
		print_r(sqlsrv_errors(), true);
	}
	return $con;
}
function disconnect($con){
	sqlsrv_close($con);
}
function get_array_of_inner_keys($arr,$key){
    $rtn = array();
    foreach($arr as $k => $innerarr){
        debug_to_console($innerarr);
        if(is_array($innerarr)) {
			array_push($rtn, $innerarr[$key]);
		} else {
            debug_to_console("error: no inner array");
        }
    }
    debug_to_console($rtn);
    return $rtn;
}
function wrap($data, $tag, $attributes = null){
    $output = '<' . $tag;

    if (!empty($attributes)) {
        foreach ($attributes as $key => $value) {
            $output .= ' ' . $key . '="' . $value . '"';
        }
    }

    $output.= '>' . $data. '</' . $tag . '>';
    return $output;
}
function make_link($url,$text,$aclass=null){
	return '<a href="' . $url . '" class="'.$aclass.'">' . $text . '</a>';
}
function print_product($prodtuple){
	$cells=array();
	array_push($cells, make_cell(make_link(get_addcart_url($prodtuple),"Add To Cart")));
	array_push($cells, make_cell($prodtuple['productImageURL'] . $prodtuple['productName']));
	array_push($cells, make_cell('$' .$prodtuple['productPrice']));
	echo(make_row($cells));
}
function print_order_summary($orderData,$orderList){
	echo("<h1>Your Order Summary</h1>");
	echo(make_tableheader(array(
		"Order Id",
		"Order Date",
		"Total Amount",
		"Address",
		"City",
		"State",
		"Postal Code",
		"Country",
		"Customer Id"
	)));
	$cells = array();
	array_push($cells, make_cell($orderData['orderId']));
	array_push($cells, make_cell(date_format($orderData['orderDate'], 'Y-m-d')));
	array_push($cells, make_cell("$" . $orderData['totalAmount']));
	array_push($cells, make_cell($orderData['shiptoAddress']));
	array_push($cells, make_cell($orderData['shiptoCity']));
	array_push($cells, make_cell($orderData['shiptoState']));
	array_push($cells, make_cell($orderData['shiptoPostalCode']));
	array_push($cells, make_cell($orderData['shiptoCountry']));
	array_push($cells, make_cell($orderData['customerId']));
	echo(make_row($cells));
	echo("</table>");
	echo(make_tableheader(array(
		"Product Name",
		"Quantity",
		"Price"
	)));
	foreach ($orderList as $id => $prod) {
		$cells = array();
		array_push($cells, make_cell($prod['name']));
		array_push($cells, make_cell($prod['quantity']));
		array_push($cells, make_cell(number_format($prod['price'], 2)));
		echo(make_row($cells));
	}
	echo("</table>");
}
function get_addcart_url($prodtuple){
	//id=<>name=<>&price=<>
    global $root;
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
	$str  = '<table class="table table-bordered"><thead>';
	$str .= make_row($cells);
	$str .= "</thead>";
	return $str;
}
?>
