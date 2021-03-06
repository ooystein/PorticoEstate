<?php 
/**
 * basic output functions
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PSI_Output
 * @author    Michael Cramer <BigMichi1@users.sourceforge.net>
 * @copyright 2009 phpSysInfo
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License version 2, or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://phpsysinfo.sourceforge.net
 */
 /**
 * basic output functions for all output formats
 *
 * @category  PHP
 * @package   PSI_Output
 * @author    Michael Cramer <BigMichi1@users.sourceforge.net>
 * @copyright 2009 phpSysInfo
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License version 2, or (at your option) any later version
 * @version   Release: 3.0
 * @link      http://phpsysinfo.sourceforge.net
 */
abstract class Output
{
    /**
     * error object for logging errors
     *
     * @var PSI_Error
     */
    protected $error;
    
    /**
     * call the parent constructor and check for needed extensions
     */
    public function __construct()
    {
        $this->error = PSI_Error::singleton();
        $this->_checkConfig();
        CommonFunctions::checkForExtensions();
    }
    
    /**
     * read the config file and check for existence
     *
     * @return void
     */
    private function _checkConfig()
    {
        include_once APP_ROOT.'/read_config.php';

        if ($this->error->errorsExist()) {
            $this->error->errorsAsXML();
        }
    }
}
