# hello-world

Welcome to the untitled wiki!
ini_set( 'display_errors', 1 ); error_reporting( E_ALL ); require_once( '/data/project/jarry-common/public_html/global.php' ); $I18N->setDomain( 'templatecount' ); $oldtime = time();
list( $interfaceLang, ) = explode( '-', $I18N->getLang() ); $language = ( isset( $_GET['lang'] ) && $_GET["lang"] != "" ) ? htmlspecialchars( $_GET['lang'] ) : $interfaceLang; $namespace = ( isset( $_GET['namespace'] ) && $_GET["namespace"] != "" ) ? htmlspecialchars( $_GET['namespace'] ) : 10; //10 is template namespace $templateName = ( isset( $_GET['name'] ) && $GET["name"] != "" ) ? str_replace( "", " ", htmlspecialchars( $_GET['name'], ENT_QUOTES ) ) : ;
echo get_html( 'header', 'Template transclusion count' );
if( !preg_match( "/^[a-z-]{2,8}$/", $language ) ) { // Safety precaution echo 'Error: Language parameter with invaid format. '; die( get_html( 'footer' ) ); } if( !is_numeric( $namespace ) ) { // Safety precaution echo 'Error: Namespace parameter must be numerical. '; die( get_html( 'footer' ) ); } ?>
.wikipedia.org $templateName = str_replace( "Template:", "", $templateName ); $templateName = mb_strtoupper( mb_substr( $templateName, 0, 1 ) ) . mb_substr( $templateName, 1 ); // For Xeno $templateName = str_replace( " ", "_", $templateName ); // echo "\n"; $db = dbconnect( $language . 'wiki-p' ); $result = $db->query( "SELECT count(*) FROM templatelinks WHERE tl_title = '" . $db->real_escape_string( $templateName ) ."' AND tl_namespace = " . $db->real_escape_string( $namespace ) . ";" ); $row = $result->fetch_array(); $count = $row[0]; 
echo " " . _html( 'transclusion-count-label' ) . " \n"; $result = " " . _html( 'transclusion-count', array( 'variables' => array( $count ) ) ); if ( $count === 0 ) { $result .= " " . _html( 'error-suggestion' ); } echo $result . " \n"; $diff = time() - $oldtime; echo " " . _html( 'time-label' ) . _g( 'colon-separator' ) . " $diff " . _g( 'seconds', array( 'variables' => array( $diff ) ) ) . ". "; } ?> 
<script type="text/javascript"> $( document ).ready( function(){ $( "#translateform" ).html5form( { async:false } ); } ); </script>
