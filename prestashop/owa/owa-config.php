<?php

//
// Open Web Analytics - An Open Source Web Analytics Framework
//
// Copyright 2006 Peter Adams. All rights reserved.
//
// Licensed under GPL v2.0 http://www.gnu.org/copyleft/gpl.html
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//
// $Id$
//

/**
 * OWA Configuration
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */
 
/**
 * DATABASE CONFIGURATION
 *
 * Connection info for databases that will be used by OWA. 
 *
 */

define('OWA_DB_TYPE', 'mysql'); // options: mysql
define('OWA_DB_NAME', 'analytics_owa'); // name of the database
define('OWA_DB_HOST', 'mysql'); // host name of the server housing the database
define('OWA_DB_USER', 'root'); // database user
define('OWA_DB_PASSWORD', 'l@tabern@'); // database user's password

/**
 * AUTHENTICATION KEYS AND SALTS
 *
 * Change these to different unique phrases.
 */
define('OWA_NONCE_KEY', '(4e9S!uG4AYqA#8T`V[rty!iAX]7zc#{SjKL,vqC-4!LLHU[3JS*g@p-R3Fi}saq');  
define('OWA_NONCE_SALT', '}PSpo{{2DmYGYpoag.(OG.z&r[LFfds&X+hdC?Aad!l Xs2,tz$::g@YV3bxpK0>');
define('OWA_AUTH_KEY', '[GLMO-ei&X4C}[J+?&9Xzf;}`<Tpx`@oPCoHW,ZJ%!P#6jyIpjRt(Q=[t=p5`z$4');
define('OWA_AUTH_SALT', 's5Y9CH|13NWp-$$S>.uINP}AGN:uk3@ CSHle0*rmz^1%t+grzb^&UN.UP]0U4:[');

/** 
 * PUBLIC URL
 *
 * Define the URL of OWA's base directory e.g. http://www.domain.com/path/to/owa/ 
 * Don't forget the slash at the end.
 */
 
define('OWA_PUBLIC_URL', 'http://localhost/owa/');  

/** 
 * OWA ERROR HANDLER
 *
 * Overide OWA error handler. This should be done through the admin GUI, but 
 * can be handy during install or development. 
 * 
 * Choices are: 
 *
 * 'production' - will log only critical errors to a log file.
 * 'development' - logs al sorts of useful debug to log file.
 */

define('OWA_ERROR_HANDLER', 'development');

/** 
 * LOG PHP ERRORS
 *
 * Log all php errors to OWA's error log file. Only do this to debug.
 */

//define('OWA_LOG_PHP_ERRORS', true);
 
/** 
 * OBJECT CACHING
 *
 * Override setting to cache objects. Caching will increase performance. 
 */

//define('OWA_CACHE_OBJECTS', true);

/**
 * CONFIGURATION ID
 *
 * Override to load an alternative user configuration
 */
 
//define('OWA_CONFIGURATION_ID', '1');

//define('OWA_MAXMIND_DATA_DIR', '/owa-data/owa-data/maxmind/'); 


?>