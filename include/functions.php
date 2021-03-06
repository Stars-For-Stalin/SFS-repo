<?php
require_once "Mail.php";
$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
$debugging = false;
function debug_to_console($data) {
	global $debugging;
	if ($debugging) {
		$output = $data;
		if (is_array($output)) {
			//todo: ?make function recursive (check if elements are arrays)?
			$output = implode(',', $output);
		}
		echo ("<script>console.log(\"Debug Objects: $output \");</script>");
	}
}
function oops($msg = null) {
	if (!is_null($msg)) {
		debug_to_console($msg);
	}
	echo ("<h1><br/><br/><br/><br/>Ooops! Something went wrong.<br/>Try again, or contact contact our support staff.</h1>");
}
function try_connect() {
	include 'db_credentials.php';
	/** Create connection, and validate that it connected successfully **/
	/** @var $connectionInfo - from db_credentials */ /** @var $server - from db_credentials*/
	$con = sqlsrv_connect($server, $connectionInfo);
	if ($con === false) {
		print_r(sqlsrv_errors(), true);
	}
	return $con;
}
function disconnect($con) {
	sqlsrv_close($con);
}
function get_array_of_inner_keys($arr, $key) {
	$rtn = array();
	foreach ($arr as $k => $innerarr) {
		debug_to_console($innerarr);
		if (is_array($innerarr)) {
			array_push($rtn, $innerarr[$key]);
		} else {
			debug_to_console("error: no inner array");
		}
	}
	debug_to_console($rtn);
	return $rtn;
}
function wrap($data, $tag, $attributes = null) {
	$output = '<' . $tag;

	if (!empty($attributes)) {
		foreach ($attributes as $key => $value) {
			$output .= ' ' . $key . '="' . $value . '"';
		}
	}

	$output .= '>' . $data . '</' . $tag . '>';
	return $output;
}
function make_link($url, $text, $attributes = null) {
	$link="<a href='$url'";
	if (!empty($attributes)) {
		foreach ($attributes as $key => $value) {
			$link .= " $key='$value'";
		}
	}
	$link .= ">$text</a>";
	return $link;
}
function print_product($prodtuple) {
	$cells = array();
	if($prodtuple['productImageURL']) {
		$imgURL = $prodtuple['productImageURL'];
	} elseif ($prodtuple['productImage']){
		$id = $prodtuple['productId'];
		$imgURL = "displayImage.php?id=$id";
	}
	if (isset($imgURL)) {
		$prodlink = make_link(
			get_product_url($prodtuple['productId']),
			$prodtuple['productName'], array("rel" => "popover","data-img"=>"$imgURL")
		);
	} else {
		$prodlink = make_link(get_product_url($prodtuple['productId']), $prodtuple['productName']);
	}
	$prod_links = "<div class='row px-3 '>";
	$prod_links .= "<div class='align-vcenter'>";
	$prod_links .= $prodlink;
	$prod_links .= "</div><div class='align-rightside'>";
	$prod_links .= get_addcart_btn($prodtuple);
	$prod_links .= "</div></div>";
	$attr = array("class"=>"reduced-padding");
	array_push($cells, make_cell($prod_links, $attr));
	array_push($cells, make_cell('$' . format_price($prodtuple['productPrice']),array("class"=>"text-right")));
	echo (make_row($cells));
}
function get_addcart_btn($prodtuple, $leave_page = false){
	$url=get_addcart_url($prodtuple);
	if ($leave_page){
		$html = "<button class='btn btn-md btn-primary leave' onclick='window.location.href=\"$url\"'>Add To Cart</button>";
	} else {
		$html = "<button class='btn btn-md btn-primary add_cart' onclick='ajaxRequest(\"$url\",\"POST\")'>Add To Cart</button>";
	}
	return $html;
}
function format_price($price) {
	return number_format($price,2,'.',',');
}
function print_order_summary($orderData, $orderList) {
	echo ("<h1>Your Order Summary</h1>");
	$attr_t1 = array("style"=>"width:40%","class"=>"table table-bordered");
	$attr_t2 = array("style"=>"width:35%","class"=>"table table-bordered align-rightside");
	$attr1 = array("scope"=>"col");
	$ralign = array("class"=>"text-right");
	$order_align = array("class"=>"text-left");
	$make_row_pair=function($title,$value,$align=null){
		$attr = array("scope"=>"col");
		$cells=array();
		array_push($cells,make_cell($title, $attr, 'th'));
		array_push($cells,make_cell($value, $align));
		return make_row($cells);
	};
	echo("<div class='row'>");
	$rows = array(make_row(array(
		make_cell("Product Name", $attr1, 'th'),
		make_cell("Quantity", $attr1, 'th'),
		make_cell("Price", $attr1, 'th')
	)));
	foreach ($orderList as $id => $prod) {
		$cells = array();
		array_push($cells, make_cell($prod['name']));
		array_push($cells, make_cell($prod['quantity'], $ralign));
		array_push($cells, make_cell("$" . format_price($prod['price']), $ralign));
		array_push($rows, make_row($cells));
	}
	echo(make_table($rows,$attr_t1));
	$rows = array();
	array_push($rows, $make_row_pair("Order Id",$orderData['orderId'],$order_align));
	array_push($rows, $make_row_pair("Order Date",date_format($orderData['orderDate'], 'Y-m-d'),$order_align));
	array_push($rows, $make_row_pair("Customer Id",$orderData['customerId'],$order_align));
	array_push($rows, $make_row_pair("Total Amount","$" . format_price($orderData['totalAmount']),$order_align));
	array_push($rows, $make_row_pair("Address",$orderData['shiptoAddress'],$order_align));
	array_push($rows, $make_row_pair("City",$orderData['shiptoCity'],$order_align));
	array_push($rows, $make_row_pair("State",$orderData['shiptoState'],$order_align));
	array_push($rows, $make_row_pair("Postal Code",$orderData['shiptoPostalCode'],$order_align));
	array_push($rows, $make_row_pair("Country",$orderData['shiptoCountry'],$order_align));
	echo(make_table($rows,$attr_t2));
	echo("</div>");
}
function get_addcart_url($prodtuple) {
	//id=<>name=<>&price=<>
	global $root;
	$url = $root . "addcart.php?id=" . $prodtuple['productId'];
	return $url;
}
function get_product_url($product) {
	global $root;
	if (!is_numeric($product)) {
		$url = $root . "product.php?id=" . $product['id'];
	} else {
		$url = $root . "product.php?id=" . $product;
	}
	return $url;
}
function addjs($code) {
	echo ("<script type='text/javascript'> $code </script>");
}
function jsredirect($url,$delay){
	addjs("setTimeout(function(){window.location.href=\"$url\";},$delay);");
}

	/**
	 * A function that generates formatted HTML text for table cells.
	 *
	 * @param string $data String for the datacell, can be plain text or formatted html text.
	 * @param array $attributes {
	 *        Optional. An array for additional attributes.
	 *        When using this parameter, pass in an array with key=>value pair.
	 *        For example, if you want a cell with colspan of 3, and align to right, you pass in:
	 *            array ('colspan'=>3, 'align'=>'right')
	 * }
	 * @param string $type Optional. Type of the datacell, default to 'td', can be 'th' for headers.
	 * @return string Return the constructed string for the cell.
	 */
function make_cell($data, $attributes = null, $type = "td") {
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
function make_row($cells, $attributes = null) {
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

function make_table($rows, $attributes = null) {
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
function make_tableheader($cols) {
	$header_attr = array("scope" => "col");
	$cells = array();
	foreach ($cols as $cheading) {
		debug_to_console("processing column headings.. on: " . $cheading);
		array_push($cells, make_cell($cheading, $header_attr, 'th'));
	}
	$str  = '<table class="table table-bordered"><thead>';
	$str .= make_row($cells);
	$str .= "</thead>";
	return $str;
}

function get_custId($userid) {
	$con = try_connect();
	if ($con == false)
		return null;

	$sql_get_custId = 'SELECT customerId FROM customer WHERE userid = ?';
	$preparedStatement_get_custId = sqlsrv_prepare($con, $sql_get_custId, array(&$userid));
	$result_get_custId = sqlsrv_execute($preparedStatement_get_custId);
	if ($result_get_custId || !empty($result_get_custId)) {
		while ($row = sqlsrv_fetch_array($preparedStatement_get_custId, SQLSRV_FETCH_ASSOC)) {
			disconnect($con);
			return ($row['customerId']);
		}
	}
}

function send_email($to, $email_subject, $email_body)
{
	if(class_exists('Mail')) {
		$host = "smtp.mailgun.org";
		$username = "postmaster@mg.notaserver.me";
		$password = "9a97160ffd65d37e39478ff548ea72dd-4879ff27-0baa535b";
		$port = "587";

		//$to = "test@example.com";
		$email_from = "donotreply@mg.notaserver.me";
		//$email_subject = "Awesome Subject line" ;
		//$email_body = "This is the message body" ;
		$email_address = "donotreply@mg.notaserver.me";
		$content = "text/html; charset=utf-8";
		$mime = "1.0";

		$headers = array(
			'From' => $email_from,
			'To' => $to,
			'Subject' => $email_subject,
			'Reply-To' => $email_address,
			'MIME-Version' => $mime,
			'Content-type' => $content
		);

		$params = array(
			'host' => $host,
			'port' => $port,
			'auth' => true,
			'username' => $username,
			'password' => $password
		);

		$smtp = Mail::factory('smtp', $params);
		$mail = $smtp->send($to, $headers, $email_body);

		if (PEAR::isError($mail)) {
			echo("<p>" . $mail->getMessage() . "</p>");
		} else {
			//echo ("<p>Message sent successfully!</p>");
		}
	} else {
		debug_to_console("Cannot send email, necessary class does not exist.");
	}
}
