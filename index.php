//just testing
<?php
ini_set( 'display_errors', 1 );
	error_reporting( E_ALL );
	require_once( '/data/project/jarry-common/public_html/global.php' );
	$I18N->setDomain( 'templatecount' );
	$oldtime = time();

	list( $interfaceLang, ) = explode( '-', $I18N->getLang() );
	$language = ( isset( $_GET['lang'] ) && $_GET["lang"] != "" ) ? htmlspecialchars( $_GET['lang'] ) : $interfaceLang;
	$namespace = ( isset( $_GET['namespace'] ) && $_GET["namespace"] != "" ) ? htmlspecialchars( $_GET['namespace'] ) : 10; //10 is template namespace
	$templateName = ( isset( $_GET['name'] ) && $_GET["name"] != "" ) ? str_replace( "_", " ", htmlspecialchars( $_GET['name'], ENT_QUOTES ) ) : '';


	echo get_html( 'header', 'Template transclusion count' );

	if( !preg_match( "/^[a-z-]{2,8}$/", $language ) ) { // Safety precaution
		echo '<b>Error:</b> Language parameter with invaid format.<br>';
		die( get_html( 'footer' ) );
	}
	if( !is_numeric( $namespace ) ) { // Safety precaution
		echo '<b>Error:</b> Namespace parameter must be numerical.<br>';
		die( get_html( 'footer' ) );
	}
?>
		<h3><?php echo _html( 'enter-details' ); ?></h3>
		<p><?php echo _html( 'introduction' ); ?></p>

		<form action="index.php" method="GET">
			<p><label for="lang"><?php echo _html( 'language-label' ) . _g( 'colon-separator' );?>&nbsp;</label><input type="text" name="lang" id="lang" value="<?php echo $language; ?>" style="width:80px;" maxlength="8" required="required">.wikipedia.org<br />
			<label for="namespace"><?php echo _html( 'namespace-label' ) . _g( 'colon-separator' );?>&nbsp;</label><?php echo getNamespaceSelect( $interfaceLang, $namespace ); ?><br />
			<label for="name"><?php echo _html( 'pagename-label' ) . _g( 'colon-separator' );?>&nbsp;</label><input type="text" name="name" id="name" style="width:200px;" value="<?php echo $templateName; ?>" required="required"/>
			<input type="submit" value="<?php echo _g( 'form-submit'); ?>" /></p>
		</form>
		<?php
			if( isset( $_GET['lang'] ) && $templateName != '' ){
				Counter::increment( 'templatecount/sincejune2011.txt' );

				$templateName = str_replace( "Template:", "", $templateName );
				$templateName = mb_strtoupper( mb_substr( $templateName, 0, 1 ) ) . mb_substr( $templateName, 1 ); // For Xeno
				$templateName = str_replace( " ", "_", $templateName );
				// echo "<!-- Actually checking database for query '" . htmlspecialchars( $db->real_escape_string( $templateName ) ) . "' -->\n";
				$db = dbconnect( $language . 'wiki-p' );
				$result = $db->query( "SELECT count(*) FROM templatelinks WHERE tl_title = '" .  $db->real_escape_string( $templateName ) ."' AND tl_namespace = " . $db->real_escape_string( $namespace ) . ";" );
				$row = $result->fetch_array();
				$count = $row[0];

				echo "<h3>" . _html( 'transclusion-count-label' ) . "</h3>\n";
				$result = "<p>" . _html( 'transclusion-count', array( 'variables' => array( $count ) ) );
				if ( $count === 0 ) {
					$result .= " " . _html( 'error-suggestion' );
				}
				echo $result . "</p>\n";
				$diff = time() - $oldtime;
				echo "<p style=\"font-size:60%;\">" . _html( 'time-label' ) . _g( 'colon-separator' ) . " $diff " . _g( 'seconds', array( 'variables' => array( $diff ) ) ) . ".</p>";
			}
		?>
		<a name="bottom" id="bottom"></a>
		<script type="text/javascript">
			<?php
				if( isset( $_GET['lang'] ) && $_GET["lang"] != "" && $templateName != '' ){
					echo "document.location='#bottom';\n";
				}
			?>
			$( document ).ready( function(){ $( "#translateform" ).html5form( { async:false } ); } );
		</script>
		<?php echo '<!-- Used ' . Counter::getCounter( 'templatecount/sincejune2011.txt' ) . " times since early June 2011. -->"; ?>
<?php
	echo get_html( 'footer' );
